<?php 
namespace App\Http\Controllers\Traits; 
use Mail;
trait NotifyMail { 
    protected function sendNotifyEmail($data) { 
        //print_r($data); die;
        Mail::send('notify',$data, function($newObj) use($data)
        {
            $newObj->from(config('mail.from.address'), config('app.site_name'));
            $newObj->subject($data['type']." add notification - ".config('app.site_name'));
            $newObj->to($data['email']);            
        });

        return true;
    } 
}