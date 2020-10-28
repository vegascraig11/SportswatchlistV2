<?php

namespace Tests\Feature;

use App\Role;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserRoleTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function userHasRoles()
    {
        $role = factory(Role::class)->create(['name' => 'admin']);
        $user = factory(User::class)->create();

        $user->roles()->attach($role->id);

        dd($user->is_admin);

        $this->assertTrue($user->isAdmin());
    }
}
