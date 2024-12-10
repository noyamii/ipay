<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Otp extends Model
{
    // no need for updated_at column on database table
    public $timestamps = false;

    protected $guarded = ["created_at"];

    public static function sendSMS(array $attributes)
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $fromNumber = env('FROM_NUMBER');
        $data = [
            'To' => $attributes['phone'],
            'From' => $fromNumber,
            'Body' => 'Your ipay verfication code: ' . $attributes['code']];

        Http::asForm()
        ->withBasicAuth($sid, $token)
        ->post('https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages', $data);
    }

    public static function check(array $attributes)
    {
        $user = Otp::where('id', $attributes['phone'])->first();
        if (!$user) {
            return 404;
        }

        // Difference between present time and expiration date 
        $time = new DateTime($user['expire_at']);
        $currentTime = new DateTime();
        $interval = $time->getTimestamp() - $currentTime->getTimestamp();

        if (($interval > 0) && ($attributes['code'] == $user['code']) && ($attributes['ip_address'] == $user['ip_address'])){
            return 200;
        }
        return 403;
    }
}
