<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use DB;
use DateTime;
use Carbon\Carbon;
use LaravelFCM\Message\OptionsBuilder;
use LaravelFCM\Message\PayloadDataBuilder;
use LaravelFCM\Message\PayloadNotificationBuilder;
use FCM;

class scheduledRecomNotifier extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'scheduled:notifier';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a notification to the users that has scheduled recommendations on the scheduled date';

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
        //array of all users that has schedulables recommendations
        $users = DB::select('
        SELECT 
            user_Id, devicetoken, displayname
        FROM
            users
        WHERE
            status = ?
        AND user_Id IN (SELECT 
            fk_user_Id
        FROM
            userrecommendation
        WHERE
            fk_status_Id = ?)', [2,5]);
        //$users = User::where('status', '=', 2)->get();
        if (count($users))
        {
            //for every user , do the logic
            foreach ($users as $user) 
            {
                $userid = $user->user_Id;
                $userDeviceToken = $user->devicetoken;
                $username = $user->displayname;
                $todayDate = Carbon::now();
                $scheduledDate = DB::table('userrecommendation')
                ->where('fk_user_Id', $userid)
                ->where('fk_status_Id', 5)
                ->where('schedule_date', $todayDate)
                ->pluck('schedule_date')->first();
                if (count($scheduledDate)) 
                {   
                    $optionBuilder = new OptionsBuilder();
                    $optionBuilder->setTimeToLive(60*20);
                    $notificationBuilder = new PayloadNotificationBuilder('Aviso Fecha Agendada Recomendacion');
                    $notificationBuilder->setBody($username.'Estas listo para completar esta recomendacion?')
                    ->setClickAction('ACTIVITY_REC')
                    ->setSound('default');
                    $dataBuilder = new PayloadDataBuilder();
                    $dataBuilder->addData(['scheduleRecomOn' => 'yes']);
                    $option = $optionBuilder->build();
                    $notification = $notificationBuilder->build();
                    $data = $dataBuilder->build();
                    $token = $userDeviceToken;
                    $downstreamResponse = FCM::sendTo($token, $option, $notification, $data);
                    $downstreamResponse->numberSuccess();
                    $downstreamResponse->numberFailure();
                    $downstreamResponse->numberModification();
                }
                else
                {
                    //return "Did  nothing!";
                } 
            }//close foreach
        }//close if   
    }
}
