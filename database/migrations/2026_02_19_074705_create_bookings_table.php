<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {

            $table->id()->comment('รหัสการจองรถ (Primary Key)');

            $table->foreignId('user_id')
                ->constrained('users')
                ->onDelete('cascade')
                ->comment('อ้างอิงผู้ทำการจอง (FK ไปยัง users)');

            $table->foreignId('car_id')
                ->constrained('cars')
                ->onDelete('cascade')
                ->comment('อ้างอิงรถที่ถูกจอง (FK ไปยัง cars)');

            $table->dateTime('start_time')
                ->comment('วันเวลาเริ่มต้นใช้งานรถ');

            $table->dateTime('end_time')
                ->comment('วันเวลาสิ้นสุดการใช้งานรถ');

            $table->string('destination')
                ->comment('สถานที่ปลายทาง');

            $table->text('purpose')
                ->nullable()
                ->comment('วัตถุประสงค์ในการใช้รถ');

            $table->enum('status',['pending','approved','rejected'])
                ->default('pending')
                ->comment('สถานะการอนุมัติการจอง');

            $table->timestamps();
        });

        DB::statement("ALTER TABLE bookings COMMENT = 'ตารางเก็บข้อมูลการจองรถยนต์'");
    }

    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};

