<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:export');
    }

    public function keys()
    {
        $keys = [
            'nba' => config('services.apiKeys.nba'),
            'ncaab' => config('services.apiKeys.ncaab'),
            'nfl' => config('services.apiKeys.nfl'),
            'ncaaf' => config('services.apiKeys.ncaaf'),
            'nhl' => config('services.apiKeys.nhl'),
            'mlb' => config('services.apiKeys.mlb'),
        ];

        return response($keys, 200);
    }

    public function updateKeys()
    {
        $validated = request()->validate([
            'nba' => ['sometimes', 'required'],
            'ncaab' => ['sometimes', 'required'],
            'nfl' => ['sometimes', 'required'],
            'ncaaf' => ['sometimes', 'required'],
            'mlb' => ['sometimes', 'required'],
            'nhl' => ['sometimes', 'required'],
        ]);

        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);


        foreach ($validated as $envKey => $envValue) {
            switch ($envKey) {
                case 'nba':
                    $envKey = 'NBA_API_KEY';
                    break;
                case 'ncaab':
                    $envKey = 'NCAAB_API_KEY';
                    break;
                case 'nfl':
                    $envKey = 'NFL_API_KEY';
                    break;
                case 'ncaaf':
                    $envKey = 'NCAAF_API_KEY';
                    break;
                case 'mlb':
                    $envKey = 'MLB_API_KEY';
                    break;
                case 'nhl':
                    $envKey = 'NHL_API_KEY';
                    break;
            }

            $str .= "\n"; // In case the searched variable is in the last line without \n
            $keyPosition = strpos($str, "{$envKey}=");
            $endOfLinePosition = strpos($str, PHP_EOL, $keyPosition);
            $oldLine = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);
            $str = str_replace($oldLine, "{$envKey}={$envValue}", $str);
            $str = substr($str, 0, -1);
        }


        $fp = fopen($envFile, 'w');
        fwrite($fp, $str);
        fclose($fp);

        Artisan::call('config:clear');
        Artisan::call('cache:clear');

        return response()->json([], 201); 
    }
}
