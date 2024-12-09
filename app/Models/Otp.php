<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Http;

class Otp extends Model
{
    // no need for updated_at column
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
        } elseif (($attributes['code'] == $user['code']) && ($attributes['ip_address'] == $user['ip_address'])){
            $user->delete();
            return 200;
        }
        return 403;
    }
}
