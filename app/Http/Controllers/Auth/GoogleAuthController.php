<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleAuthController extends Controller
{
    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception) {
            return redirect()->route('login')->withErrors([
                'email' => 'เกิดข้อผิดพลาดในการเชื่อมต่อ Google กรุณาลองใหม่อีกครั้ง',
            ]);
        }

        // 1. ตรวจสอบว่าเป็นอีเมลขององค์กร @msu.ac.th เท่านั้น
        if (!str_ends_with($googleUser->getEmail(), '@msu.ac.th')) {
            return redirect()->route('login')->withErrors([
                'email' => 'อนุญาตเฉพาะบัญชีอีเมลขององค์กร @msu.ac.th เท่านั้น',
            ]);
        }

        // 2. ตรวจสอบว่ามีอีเมลนี้อยู่ในระบบหรือไม่
        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'ไม่พบบัญชีผู้ใช้ "' . $googleUser->getEmail() . '" ในระบบ กรุณาติดต่อผู้ดูแลระบบเพื่อขอสิทธิ์การเข้าใช้งาน',
            ]);
        }

        // 3. อัปเดต google_id และ avatar เมื่อเข้าระบบสำเร็จ
        $user->update([
            'google_id' => $googleUser->getId(),
            'avatar'    => $googleUser->getAvatar(),
        ]);

        // 4. ล็อกอินเข้าระบบ
        Auth::login($user);

        // 5. redirect ตาม role
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }
        if ($user->role === 'staff_head') {
            return redirect()->intended(route('head.dashboard'));
        }
        return redirect()->intended(route('staff.dashboard'));
    }
}
