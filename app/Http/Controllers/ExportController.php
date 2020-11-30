<?php

namespace App\Http\Controllers;

use App\Jobs\ExportToCSV;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:export');
    }

    public function index()
    {
        ExportToCSV::dispatchNow();

        return response()->json([], 200);
    }
}
