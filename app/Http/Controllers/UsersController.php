<?php

namespace App\Http\Controllers;

use App\Actions\Fortify\PasswordValidationRules;
use App\User;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UsersController extends Controller
{
    use PasswordValidationRules;

    public function index()
    {
        return response()->json(User::paginate(10), 200);
    }

    public function register()
    {
        request()->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'email',
                'max:255',
                Rule::unique(User::class),
            ],
            'password' => $this->passwordRules(),
        ]);

        $user = User::create([
            'name' => request('name'),
            'email' => request('email'),
            'password' => Hash::make(request('password')),
            'verification_token' => Str::random(32),
        ]);

        $user->sendEmailVerificationNotification();

        return response()->json($user, 200);
    }

    public function resendVerificationEmail()
    {
        $user = User::where('email', request()->email)->firstOrFail();

        if ($user->hasVerifiedEmail()) {
            abort(422, 'You have already verified your email.');
        }

        $user->sendEmailVerificationNotification();

        return response()->json([], 200);
    }

    public function verifyEmail()
    {
        $user = User::where('email', request()->email)->firstOrFail();

        if ($user->verification_token !== request()->token) {
            abort(401, 'Verification token mismatch');
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json([], 200);
        }

        if ($user->markEmailAsVerified()) {
            event(new Verified($user));
            $user->verification_token = null;
            $user->save();
        }

        return response()->json([], 200);
    }
}
