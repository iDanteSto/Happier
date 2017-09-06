<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class moodSetter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mood:setter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Checks if the user can receive a new mood , if it can creates the holder of the mood';

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
       




    }
}
