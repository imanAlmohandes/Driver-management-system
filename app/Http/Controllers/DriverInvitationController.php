<?php
namespace App\Http\Controllers;

use App\Mail\DriverInvitationMail;
use App\Models\Driver;
use App\Models\DriverInvitation;
use App\Models\User;
use App\Notifications\DriverRegisteredNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class DriverInvitationController extends Controller
{
    public function create()
    {
        return view('admin.drivers.invite');
    }

    public function send(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email', 'unique:users,email'],
        ]);

        try {
            $token = Str::random(64);

            DriverInvitation::create([
                'email'      => $request->email,
                'token'      => hash('sha256', $token), //   store hashed
                'expires_at' => now()->addHours(24),
            ]);

            $activationUrl = route('driver.invite.show', ['token' => $token]); //   correct route name

            Mail::to($request->email)->send(new DriverInvitationMail($activationUrl));

            return back()->with('msg', __('driver.invitation_sent'))->with('type', 'success');
        } catch (\Throwable $e) {
            Log::error('Driver Invitation Mail Failed', [
                'email' => $request->email,
                'error' => $e->getMessage(),
            ]);
            return back()->with('msg', 'Mail failed: ' . $e->getMessage())->with('type', 'danger');
        }
    }

    public function show(string $token)
    {
        $invitation = DriverInvitation::where('token', hash('sha256', $token))->first();

        if (! $invitation || $invitation->expires_at->isPast() || $invitation->used_at) {
            abort(403, 'الرابط غير صالح أو منتهي');
        }

        return view('driver.activate', [
            'invitation' => $invitation,
            'token'      => $token, //   pass RAW token to form action
        ]);
    }

    public function store(Request $request, string $token)
    {
        $invitation = DriverInvitation::where('token', hash('sha256', $token))->firstOrFail();

        abort_if($invitation->expires_at->isPast() || $invitation->used_at, 403);

        $request->validate([
            'name'           => 'required|string|max:255',
            'password'       => 'required|confirmed|min:8',
            'license_number' => 'required|unique:drivers,license_number',
        ]);

        $user = User::create([
            'name'              => $request->name,
            'email'             => $invitation->email,
            'password'          => bcrypt($request->password),
            'role'              => 'driver',
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        Driver::create([
            'user_id'             => $user->id,
            'license_number'      => $request->license_number,
            'license_type'        => $request->license_type,
            'license_expiry_date' => $request->license_expiry_date,
        ]);

        $admin = User::where('role', 'admin')->first();
        if ($admin) {
            $admin->notify(new DriverRegisteredNotification([
                'text'  => 'تم إضافة سائق جديد: ' . $user->name,
                'icon'  => 'fa-user-plus',
                'color' => 'success',
                'route' => route('admin.drivers.index'),
            ]));
        }

        $invitation->update(['used_at' => now()]);

        auth()->login($user);

        return redirect()->route('driver.dashboard');
    }
    public function resend($token)
    {
        $invitation = DriverInvitation::where('token', hash('sha256', $token))->firstOrFail();

        // إذا لسا صالح
        if (! $invitation->isExpired() && ! $invitation->isUsed()) {
            return back()->with('msg', 'الرابط الحالي ما زال صالحًا، جرّبي لاحقًا')->with('type', 'warning');
        }

        // إعادة تهيئة
        $plainToken = Str::random(64);

        $invitation->update([
            'token'      => hash('sha256', $plainToken),
            'expires_at' => now()->addHours(24),
            'used_at'    => null,
        ]);

        $activationUrl = route('driver.invite.show', ['token' => $plainToken]);

        Mail::to($invitation->email)->send(new DriverInvitationMail($activationUrl));

        return back()->with('msg', 'تم إرسال رابط جديد إلى بريدك الإلكتروني')->with('type', 'success');
    }
}
