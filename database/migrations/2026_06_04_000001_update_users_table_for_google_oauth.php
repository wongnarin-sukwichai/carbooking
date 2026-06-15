<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // เพิ่ม google_id หลัง id
            $table->string('google_id')->nullable()->unique()->after('id')->comment('Google OAuth ID');

            // เพิ่ม avatar หลัง email
            $table->string('avatar')->nullable()->after('email')->comment('URL รูปโปรไฟล์จาก Google');

            // ลบ columns ที่ไม่ใช้แล้ว
            $table->dropColumn(['password', 'remember_token']);
        });

        // อัปเดต enum ของ role ให้ชัดเจน
        \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','staff_head','staff') NOT NULL DEFAULT 'staff' COMMENT 'ระดับสิทธิ์ผู้ใช้งาน'");
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['google_id', 'avatar']);
            $table->string('password')->after('email');
            $table->rememberToken();
        });

        \DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('admin','staff_head','staff') NOT NULL DEFAULT 'staff'");
    }
};
