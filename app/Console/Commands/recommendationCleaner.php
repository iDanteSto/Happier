<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;

class recommendationCleaner extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'recommendation:cleaner';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans all the pending recommendations';

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
        
  //Cleans all the pending recommendations
  DB::table('userrecommendation')->where('fk_status_Id', '=', 2)->delete();

    }
}
