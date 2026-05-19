<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
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

            $existingUser = User::where('google_id', $googleUser->id)
                ->orWhere('email', $googleUser->email)
                ->first();

            if ($existingUser) {
                if (!$existingUser->google_id) {
                    $existingUser->google_id = $googleUser->id;
                }
                if (!$existingUser->email_verified_at) {
                    $existingUser->email_verified_at = now();
                }
                $existingUser->save();
            } else {
                $existingUser = User::create([
                    'name' => $googleUser->name,
                    'email' => $googleUser->email,
                    'google_id' => $googleUser->id,
                    'email_verified_at' => now(),
                    'password' => Hash::make(Str::random(32)),
                ]);
            }

            Auth::guard('web')->login($existingUser, true);
            request()->session()->regenerate();

            Log::info('google-login', [
                'user_id' => $existingUser->id,
                'email' => $existingUser->email,
                'has_session' => request()->hasSession(),
                'session_id' => request()->session()->getId(),
            ]);

            return redirect()->route('admin.dashboard');
        } catch (\Exception $e) {
            Log::error('google-login-failed', [
                'message' => $e->getMessage(),
            ]);

            return redirect('/login')->with('error', 'Something went wrong or You have rejected the app. Please try again.');
        }
    }
}
