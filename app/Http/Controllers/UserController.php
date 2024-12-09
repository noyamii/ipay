<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use PhpParser\Node\Stmt\TryCatch;

class UserController extends Controller
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
        // not every one allow to request for this
        try {
            $attributes = $request->validate([
                'phone' => 'required',
                'first_name' => ['required', 'between:3,25'],
                'last_name' => ['required', 'between:3,25'],
                'email' => 'required', 'email',
            ]);
        } catch (ValidationException $e) {
            return response($e->getMessage(), 400);
        }

        $user = User::create([

            'id' => $attributes['phone'],
            'first_name' => $attributes['first_name'],
            'last_name' => $attributes['last_name'],
            'email' => $attributes['email'],
        ]);

        Auth::login($user);

        return response($user);
        
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        //
    }
}
