<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use DB;

class cleanDeleteAllUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:cleanDeleteAllUsers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all dependencies of the users and the users itself';

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
$users = DB::table('users')->distinct()->get();

foreach ($users as $usuarios) 
{
        DB::table('usermeditation')->where('fk_user_Id', '=', $usuarios->$user_Id)->delete();
        DB::table('avatar_permission')->where('fk_user_Id', '=', $usuarios->$user_Id)->delete();
        DB::table('preferred_categories')->where('fk_user_Id', '=', $usuarios->$user_Id)->delete();
        DB::table('usermeditation')->where('fk_user_Id', '=', $usuarios->$user_Id)->delete();
        DB::table('userfrequency')->where('fk_user_Id', '=', $usuarios->$user_Id)->delete();
        DB::table('usermood')->where('fk_user_Id', '=', $usuarios->$user_Id)->delete();
        DB::table('userrecommendation')->where('fk_user_Id', '=', $usuarios->$user_Id)->delete();
        DB::table('social_provider')->where('fk_user_Id', '=', $usuarios->$user_Id)->delete();
        DB::table('users')->where('user_Id', '=', $usuarios->$user_Id)->delete();
}
    //$id = $this->argument('id');

       

    }
}
