<?php

namespace Database\Seeders;

use App\Role;
use App\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::create(['name' => 'admin']);

        $user = User::factory()->create([
            'name' => 'Admin',
            'email' => 'admin@sportswatchlist.com',
            'password' => Hash::make('Adm1nP4$S#swl'),
            'email_verified_at' => now()
        ]);

        $user->roles()->attach($user);
    }
}
