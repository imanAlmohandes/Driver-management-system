<?php
namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class SocialAuthController extends Controller
{
    public function redirect($provider)
    {
        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function callback($provider)
    {
        $socialUser = Socialite::driver($provider)->stateless()->user();

        $user = User::where('email', $socialUser->getEmail())->where('role', 'admin')->first();

        if (! $user) {
            abort(403, 'This account is not authorized to log in');
            return redirect('/login')->withErrors(['email' => 'Unauthorized account']);
        }
        // لو أول مرة
        // $user->update([
        //     'provider'    => $provider,
        //     'provider_id' => $socialUser->getId(),
        // ]);
        Auth::login($user);
        return redirect()->route('admin.index');

    }
}
