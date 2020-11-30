<?php

namespace Tests\Feature;

use App\Events\ExportedToCSV;
use App\Jobs\ExportToCSV;
use App\Role;
use App\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Queue;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserExportToCSVTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function dispatchesTheExportJob()
    {
        $this->withoutExceptionHandling();

        Queue::fake();

        $admin = factory(User::class)->create([
            'name' => 'Test Name',
            'email' => 'email@domain.com',
        ]);
        $role = factory(Role::class)->create(['name' => 'admin']);
        $admin->roles()->attach($role->id);

        $this->actingAs($admin)
            ->postJson('/api/export')
            ->assertStatus(200);
        Queue::assertPushed(ExportToCSV::class);
    }

    /** @test */
    public function canExportToCSV()
    {
        Event::fake();
        Storage::fake();

        $admin = factory(User::class)->create([
            'name' => 'Test Name',
            'email' => 'email@domain.com',
        ]);
        $otherUser = factory(User::class)->create([
            'name' => 'Other User',
            'email' => 'otheremail@service.com',
        ]);
        $role = factory(Role::class)->create(['name' => 'admin']);
        $admin->roles()->attach($role->id);

        $job = new ExportToCSV;
        $job->handle();

        Event::assertDispatched(ExportedToCSV::class);
        Storage::assertExists('exports/users.csv');
        $contents = <<<EOD
name,email
"Test Name",email@domain.com
"Other User",otheremail@service.com

EOD;
        $this->assertEquals(Storage::get('exports/users.csv'), $contents);
    }

    /** @test */
    public function canDownloadTheExportedCSV()
    {
        $this->withoutExceptionHandling();

        $contents = <<<EOD
name,email
"Test Name",email@domain.com
"Other User",otheremail@service.com

EOD;

        Storage::fake();
        Storage::put('exports/users.csv', $contents);

        $admin = factory(User::class)->create([
            'name' => 'Test Name',
            'email' => 'email@domain.com',
        ]);
        
        $role = factory(Role::class)->create(['name' => 'admin']);
        $admin->roles()->attach($role->id);

        $response = $this->actingAs($admin)->get('/export/download');

        $response->assertStatus(200)
                ->assertHeader('Content-Type', 'text/csv; charset=UTF-8')
                ->assertHeader("Content-Disposition", "attachment; filename=users.csv")
                ->assertHeader("Pragma", "no-cache")
                ->assertHeader("Cache-Control", "must-revalidate, post-check=0, pre-check=0, private")
                ->assertHeader("Expires", "0");
    }

    /** @test */
    public function onlyAdminsCanExport()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->postJson('/api/export')
            ->assertStatus(403);
    }

    /** @test */
    public function onlyAdminsCanDownloadExportedCSV()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user)
            ->get('/export/download')
            ->assertStatus(403);
    }
}
