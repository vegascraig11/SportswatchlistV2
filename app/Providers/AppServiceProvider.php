<?php

namespace App\Providers;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // NBA Teams
        Cache::remember('nba_teams', 24 * 60 * 60, function () {
            $response = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => config('services.apiKeys.nba')
            ])->get('https://api.sportsdata.io/v3/nba/scores/json/AllTeams');

            if ($response->ok()) {
                return collect($response->json());
            }

            return collect();
        });

        // NBA Stadiums
        Cache::remember('nba_stadiums', 24 * 60 * 60, function () {
            $response = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => config('services.apiKeys.nba')
            ])->get('https://api.sportsdata.io/v3/nba/scores/json/Stadiums');

            if ($response->ok()) {
                return collect($response->json());
            }

            return collect();
        });

        // NCAAB Teams
        Cache::remember('ncaab_teams', 24 * 60 * 60, function () {
            $response = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => config('services.apiKeys.ncaab')
            ])->get('https://api.sportsdata.io/v3/cbb/scores/json/teams');

            if ($response->ok()) {
                return collect($response->json());
            }

            return collect();
        });

        // NCAAF Teams
        Cache::remember('ncaaf_teams', 24 * 60 * 60, function () {
            $response = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => config('services.apiKeys.ncaaf')
            ])->get('https://api.sportsdata.io/v3/cfb/scores/json/teams');

            if ($response->ok()) {
                return collect($response->json());
            }

            return collect();
        });


        // NHL Teams
        Cache::remember('nhl_teams', 24 * 60 * 60, function () {
            $response = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => config('services.apiKeys.nhl')
            ])->get('https://api.sportsdata.io/v3/nhl/scores/json/AllTeams');

            if ($response->ok()) {
                return collect($response->json());
            }

            return collect();
        });

        // NHL Stadiums
        Cache::remember('nhl_stadiums', 24 * 60 * 60, function () {
            $response = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => config('services.apiKeys.nhl')
            ])->get('https://api.sportsdata.io/v3/nhl/scores/json/Stadiums');

            if ($response->ok()) {
                return collect($response->json());
            }

            return collect();
        });

        // MLB Teams
        Cache::remember('mlb_teams', 24 * 60 * 60, function () {
            $response = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => config('services.apiKeys.mlb')
            ])->get('https://api.sportsdata.io/v3/mlb/scores/json/AllTeams');

            if ($response->ok()) {
                return collect($response->json());
            }

            return collect();
        });

        // MLB Stadiums
        Cache::remember('mlb_stadiums', 24 * 60 * 60, function () {
            $response = Http::withHeaders([
                'Ocp-Apim-Subscription-Key' => config('services.apiKeys.mlb')
            ])->get('https://api.sportsdata.io/v3/mlb/scores/json/Stadiums');

            if ($response->ok()) {
                return collect($response->json());
            }

            return collect();
        });
    }
}
