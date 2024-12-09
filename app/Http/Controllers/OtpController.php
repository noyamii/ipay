<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use DateInterval;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class OtpController extends Controller
{
    

    public function send(Request $request)
    {
        // TODO: won't allow everyone to request an otp
        // TODO: every 2min from the same user
         try {
            $attributes = $request->validate(
                [
                    'phone' => 'required',
                ]
            );
        } catch (ValidationException $e) {
            return response("Your request doesn't satisfy the requirments.", 400);
        }

        $user = Otp::where('id', $attributes['phone'])->first();
        if (!$user)
        {

            $code = rand(10000, 99999);

            // for expiration date
            $time = new DateTime();
            // + 10 minutes
            $time->add(new DateInterval('PT10M'));
            $expiration = $time->format('H:i');

            Otp::create([
                'id' => $attributes['phone'],
                'code' => $code,
                'ip_address' => $request->ip(),
                'expire_at' => $expiration,
            ]);
        } else {
            $code = $user['code'];
            // this return will exist till turning on the sendSMS function
            return response($code);
        }

        // 0.35$ az to job nayomadeh *_*
        // Otp::sendSMS([
        //     'phone' => $request['phone'],
        //     'code' => $code,
        //     'ip' => $request->ip(),
        // ]);

        return response('SMS sent.');
        

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
