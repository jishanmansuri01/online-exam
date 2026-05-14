<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    public function redirectToGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    public function handleGoogleCallback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
            
            // 1. Find user by google_id
            $user = User::where('google_id', $googleUser->id)->first();
            
            if (!$user) {
                // 2. If not found, check by email
                $user = User::where('email', $googleUser->email)->first();
                
                if ($user) {
                    // Update existing user to include Google ID
                    $user->update(['google_id' => $googleUser->id]);
                } else {
                    // 3. Create a NEW user and EXPLICITLY set the role to student
                    $user = User::create([
                        'name'      => $googleUser->name,
                        'email'     => $googleUser->email,
                        'google_id' => $googleUser->id,
                        'password'  => Hash::make(Str::random(24)),
                        'role'      => 'student', // THIS IS THE KEY PART
                    ]);
                }
            }

            // Log the user in
            Auth::login($user);

            // 4. Redirect to the named route in your web.php
            return redirect()->route('student.dashboard');

        } catch (Exception $e) {
            // Log the error so you can see it in storage/logs/laravel.log
            \Log::error('Google Auth Error: ' . $e->getMessage());
            return redirect()->route('login')->with('error', 'Authentication failed.');
        }
    }
}