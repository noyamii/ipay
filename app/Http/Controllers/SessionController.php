<?php

namespace App\Http\Controllers;

use App\Models\Otp;
use App\Models\User;
use Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

class SessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // TODO: valid phone number using regex
        try {
            $attributes = $request->validate(
                [
                    'phone' => 'required',
                    'code'  => 'required',
                ]
            );
            $attributes['ip_address'] = $request->ip();
        } catch (ValidationException $e) {
            return response("Your request doesn't satisfy the requirments.", 400);
        }
        $status = Otp::check($attributes);
        if ($status == 200)
        {
            $user = User::where('id', $attributes['phone'])->first();
            if($user) 
            {
                Auth::login($user);
                $request->session()->regenerate();
                return response($user);
            }
            return response('User not found', 401);
            // maybe error handling in check function !?
        } elseif ($status == 403) {
            // INFO: for validating the code and requested ip
            return response('otp validation failed', 403);
        } elseif ($status == 404) {
            return response('user not found in otp list', 404);
        } else {
            return response('unexpected error in logging in', 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy()
    {
        Auth::logout();
        return abort(200, 'successfully logged out.');
    }
}
