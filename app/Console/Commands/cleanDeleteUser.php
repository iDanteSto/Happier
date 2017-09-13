<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\User;
use DB;

class cleanDeleteUser extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:cleanDelete {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Deletes all dependencies of the user and the user itself';

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
    $id = $this->argument('id');

        DB::table('usermeditation')->where('fk_user_Id', '=', $id)->delete();
        DB::table('avatar_permission')->where('fk_user_Id', '=', $id)->delete();
        DB::table('preferred_categories')->where('fk_user_Id', '=', $id)->delete();
        DB::table('usermeditation')->where('fk_user_Id', '=', $id)->delete();
        DB::table('userfrequency')->where('fk_user_Id', '=', $id)->delete();
        DB::table('usermood')->where('fk_user_Id', '=', $id)->delete();
        DB::table('userrecommendation')->where('fk_user_Id', '=', $id)->delete();
        DB::table('social_provider')->where('fk_user_Id', '=', $id)->delete();
        DB::table('users')->where('user_Id', '=', $id)->delete();

    }
}
