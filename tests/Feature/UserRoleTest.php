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
        $role = Role::factory()->create(['name' => 'admin']);
        $user = User::factory()->create();

        $user->roles()->attach($role->id);

        $this->assertTrue($user->isAdmin());
    }
}
