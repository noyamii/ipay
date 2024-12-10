<?php

namespace App\Models;

use DateTime;
use DateInterval;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Otp extends Model
{
    // no need for updated_at column on database table
    public $timestamps = false;

    protected $guarded = ["created_at"];

    public static function codeGenerator(array $attributes) 
    {
        $user = self::where('id', $attributes['phone'])->first();
        if (!$user)
        {

            $code = rand(10000, 99999);

            // for expiration date
            $time = new DateTime();
            // + 10 minutes
            $time->add(new DateInterval('PT10M'));
            $expiration = $time->format('Y-m-d H:i:s');

            Otp::create([
                'id' => $attributes['phone'],
                'code' => $code,
                'ip_address' => $attributes['ip_address'],
                'expire_at' => $expiration,
            ]);
        } else {
            $code = $user['code'];
        }
        return $code;

    }
    public static function sendSMS(array $attributes)
    {
        $sid = env('TWILIO_SID');
        $token = env('TWILIO_TOKEN');
        $fromNumber = env('FROM_NUMBER');
        $data = [
            'To' => $attributes['phone'],
            'From' => $fromNumber,
            'Body' => 'Your ipay verfication code: ' . $attributes['code']];
        
        // TODO: add error handling for twilio's error
        Http::asForm()
        ->withBasicAuth($sid, $token)
        ->post('https://api.twilio.com/2010-04-01/Accounts/{$sid}/Messages', $data);

        return ['SMS sent', 200];
    }

    public static function check(array $attributes)
    {
        $user = self::where('id', $attributes['phone'])->first();
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
