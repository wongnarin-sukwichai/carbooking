<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {

            $table->id()->comment('รหัสผู้ใช้งาน (Primary Key)');

            $table->string('name')
                ->comment('ชื่อ-นามสกุลผู้ใช้งาน');

            $table->string('email')
                ->unique()
                ->comment('อีเมลสำหรับเข้าสู่ระบบ (ต้องไม่ซ้ำ)');

            $table->string('password')
                ->comment('รหัสผ่านที่เข้ารหัสแล้ว');

            $table->enum('role',['admin','staff_head','staff'])
                ->default('staff')
                ->comment('ระดับสิทธิ์ผู้ใช้งาน');
                
            $table->rememberToken()->comment('Token สำหรับจดจำการเข้าสู่ระบบ');


            $table->timestamps(); // created_at, updated_at
        });

        DB::statement("ALTER TABLE users COMMENT = 'ตารางเก็บข้อมูลผู้ใช้งานระบบ'");
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};

