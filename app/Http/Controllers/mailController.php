<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Mail;
class mailController extends Controller
{
    //



public function basic_email()
{
	$data =['name' =>'harrison jones'];
	Mail::send('emails.sendTest', $data, function ($message) {
    $message->from('kikeciqno@gmail.com', 'Laravel');

    $message->to('kike@ciqno.com')->cc('bar@example.com');
});
}



}
