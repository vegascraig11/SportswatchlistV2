<?php

namespace App\Jobs;

use App\Events\ExportedToCSV;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class ExportToCSV implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $filename = 'users.csv';
        $file = Storage::put('exports/'.$filename, '');

        $handle = fopen(Storage::path('exports/'.$filename), 'w');

        fputcsv($handle, ['name', 'email']); // CSV header

        $users = User::all();
        foreach ($users as $user) {
            $row['name'] = $user->name;
            $row['email'] = $user->email;

            fputcsv($handle, $row);
        }

        fclose($handle);

        event(new ExportedToCSV);
    }
}
