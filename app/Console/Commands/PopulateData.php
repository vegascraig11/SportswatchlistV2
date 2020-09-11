<?php

namespace App\Console\Commands;

use App\API\MLB;
use App\API\NBA;
use App\API\NCAAB;
use App\API\NCAAF;
use App\API\NFL;
use App\API\NHL;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class PopulateData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'swl:populate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populate the database with data from the API';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        DB::table('stadiums')->truncate();
        DB::table('teams')->truncate();
        // DB::table('games')->truncate();

        $nfl = new NFL();
        $nba = new NBA();
        $mlb = new MLB();
        $nhl = new NHL();
        $ncaaf = new NCAAF();
        $ncaab = new NCAAB();
        
        try {
            $nfl->populateTeams();
            $nba->populateTeams();
            $mlb->populateTeams();
            $nhl->populateTeams();
            $ncaaf->populateTeams();
            $ncaab->populateTeams();
            $nfl->populateStadiums();
            $nba->populateStadiums();
            $mlb->populateStadiums();
            $nhl->populateStadiums();
            $ncaaf->populateStadiums();
            $ncaab->populateStadiums();

            return true;
        } catch (Exception $e) {
            return false;
        }
    }
}
