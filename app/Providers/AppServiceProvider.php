<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // 🌟 ตั้งค่าให้ใช้ไฟล์ View ภาษาไทยสำหรับลืมรหัสผ่าน
        ResetPassword::toMailUsing(function (object $notifiable, string $token) {
            
            // 1. สร้างลิงก์สำหรับรีเซ็ตรหัสผ่าน (แนบ Token และ Email ไปด้วย)
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            // 2. เรียกใช้ไฟล์ View ที่เราจะสร้างขึ้นมาใหม่ และกำหนดหัวข้ออีเมล (Subject)
            return (new MailMessage)
                ->subject('🔑 ขอตั้งรหัสผ่านใหม่ | ระบบจองรถยนต์ สำนักวิทยบริการ')
                ->view('emails.reset-password', [
                    'url' => $url,
                    'user' => $notifiable
                ]);
        });
        // 3. สั่งให้ใช้ Tailwind สำหรับ Pagination
        Paginator::useTailwind();
    }
}
