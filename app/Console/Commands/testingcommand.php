<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use DB;

class testingcommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to test functionalitys of cron';

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
        //


DB::table('test_filler')->insert(
    ['recommendation_Id' => 5]
);




    }
}
