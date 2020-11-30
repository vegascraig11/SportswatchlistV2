<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DownloadCSVController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:export');
    }
    
    public function index()
    {
        try {
            return Storage::download('exports/users.csv', 'users.csv', [
                "Content-Type"        => "text/csv",
                "Content-Disposition" => "attachment; filename=users.csv",
                "Pragma"              => "no-cache",
                "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
                "Expires"             => "0"
            ]);
        } catch (\Throwable $e) {
            abort(404);
        }
    }
}
