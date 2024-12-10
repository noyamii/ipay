<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\RateLimiter;

class OtpController extends Controller
{
    

    public function send(Request $request)
    {
        // TODO: won't allow everyone to request an otp

        // validating for a valid phone number 
         try {
            $attributes = $request->validate(
                [
                    'phone' => 'required',
                ]
            );
            $attributes['ip_address'] = $request->ip();
        } catch (ValidationException $e) {
            return response("Your request doesn't satisfy the requirments.", 400);
        }

 
        // every 2min from the same user
        $executed = RateLimiter::attempt(
            'send-message:'.$request['phone'],
            $perTwoMinutes = 1,
            function() use($attributes){
                $attributes['code'] = Otp::codeGenerator($attributes);
                // this return will exist till sendSMS is commented.
                return [$attributes['code'], 200];

                // 0.35$ az to job nayomadeh *_*
                // return Otp::sendSMS($attributes);
            },
            $decayRate = 120,
        );
        if (! $executed) {
            return response('Too many messages sent!', 403);
        }

        return response($executed[0], $executed[1]);
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
