<?php

namespace App\Http\Controllers;

use App\Subscription;
use Illuminate\Http\Request;

class SubscriptionsController extends Controller
{
    public function store()
    {
        request()->validate([
            'email' => ['required', 'email'],
        ]);

        if (!Subscription::where('email', request()->email)->exists()) {
            Subscription::create(['email' => request()->email]);
        }

        return response()->json([], 201);
    }
}
