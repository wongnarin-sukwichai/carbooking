<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Illuminate\Support\Str; // ✨ นำเข้าคลาส Str เพื่อใช้สุ่มตัวอักษร

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        // ✨ 1. สร้าง Text CAPTCHA สุ่มความยาว 5 ตัวอักษร (ตัดตัวอักษรที่ดูสับสนออก เช่น O, 0, I, l)
        $characters = 'ABCDEFGHJKLMNPQRSTUVWXYZ123456789';
        $captchaText = '';
        for ($i = 0; $i < 5; $i++) {
            $captchaText .= $characters[rand(0, strlen($characters) - 1)];
        }
        
        // ✨ 2. บันทึกคำตอบลง Session โดยแปลงเป็นตัวพิมพ์เล็กหรือใหญ่ให้เช็คได้หมด (Case-insensitive)
        session(['captcha_answer' => strtoupper($captchaText)]);

        // ✨ 3. ส่งตัวแปร $captchaText ไปให้หน้า View แสดงผล โดยแอบเติมเว้นวรรคให้ดูสวยงาม
        $displayCaptcha = implode(' ', str_split($captchaText));
        session()->flash('captcha_text', $displayCaptcha);

        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        // ✨ 1. เช็ค CAPTCHA ก่อนเป็นอันดับแรกเลย
        $userCaptcha = strtoupper(trim($request->input('captcha')));
        $correctCaptcha = session('captcha_answer');

        if ($userCaptcha !== $correctCaptcha) {
            // ถ้าตอบผิด ให้เด้งกลับไปและโชว์ Error
            return back()->withInput($request->only('email'))->withErrors([
                'captcha' => '❌ รหัสยืนยันตัวอักษรไม่ถูกต้อง กรุณาลองอีกครั้ง',
            ]);
        }

        // ✨ ถ้า CAPTCHA ผ่าน ถึงจะยอมให้ลุยตรวจสอบอีเมลและรหัสผ่าน
        $request->authenticate();

        $request->session()->regenerate();

        $user = $request->user();

        // 🌟 เปลี่ยนมาใช้ intended() เพื่อให้ระบบจำลิงก์จากอีเมลได้
        if ($user->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        if ($user->role === 'staff_head') {
            return redirect()->intended(route('head.dashboard'));
        }

        if ($user->role === 'staff') {
            return redirect()->intended(route('staff.dashboard'));
        }

        return redirect('/login');
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}