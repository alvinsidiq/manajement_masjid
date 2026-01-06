<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\{UpdateProfileRequest, UpdatePasswordRequest};
use App\Models\User;
use App\Services\AuditService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Http\Request;

class ProfileController extends Controller
{
    public function __construct(private AuditService $audit)
    {
        $this->middleware(['auth','verified','active']);
    }

    public function edit(Request $request)
    {
        $u = $request->user();
        return view('user.profile.edit', compact('u'));
    }

    public function update(UpdateProfileRequest $request)
    {
        $u = $request->user();
        $old = $u->only(['username','email','no_telephone']);

        $emailChanged = $request->email !== $u->email;
        $u->fill($request->validated());

        if ($emailChanged) {
            // Reset verifikasi email bila email berubah
            $u->email_verified_at = null;
        }
        $u->save();

        // Kirim ulang verifikasi bila email berubah
        if ($emailChanged) {
            $u->sendEmailVerificationNotification();
        }

        // Audit log
        $this->audit->log('profile.update','User',$u->user_id,[
            'before' => $old,
            'after' => $u->only(['username','email','no_telephone'])
        ], $u->user_id);

        return back()->with('status','Profil berhasil diperbarui'.($emailChanged ? ' — cek email untuk verifikasi.' : '.'));
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        $u = $request->user();
        $u->password = Hash::make($request->password);
        $u->save();

        // Opsional: keluarkan sesi lain
        if (method_exists(Auth::guard(), 'logoutOtherDevices')) {
            Auth::logoutOtherDevices($request->password);
        }

        // Audit
        $this->audit->log('profile.password_change','User',$u->user_id,[], $u->user_id);

        return back()->with('status_password','Password berhasil diperbarui.');
    }
}