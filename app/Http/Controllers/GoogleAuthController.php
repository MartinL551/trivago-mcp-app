<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->user();

        $user = User::updateOrCreate([
            'provider' => 'Google',
            'provider_id' => $googleUser->getId(),
        ],
        [
            'provider_id' => $googleUser->getId(),
            'name' => $googleUser->getName(),
        ]);

        Auth::login($user);

        return redirect('/search');
    }
}
