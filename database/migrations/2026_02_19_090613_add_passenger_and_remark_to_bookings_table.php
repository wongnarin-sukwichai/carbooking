<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            
            // เพิ่มจำนวนผู้โดยสาร (ไว้หลัง destination)
            $table->integer('passenger_count')
                ->after('destination')
                ->comment('จำนวนผู้โดยสาร');

            // เพิ่มหมายเหตุจากหัวหน้า (ให้เป็น nullable เพราะตอนจองแรกๆ จะยังไม่มีข้อมูลนี้)
            $table->text('head_remark')
                ->nullable()
                ->after('status')
                ->comment('หมายเหตุหรือเหตุผลประกอบการอนุมัติ/ไม่อนุมัติจากหัวหน้า');            
        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            // คำสั่งสำหรับลบคอลัมน์ทิ้ง กรณีที่คุณสั่ง Rollback คืนค่าฐานข้อมูล
            $table->dropColumn(['passenger_count', 'head_remark']);
        });
    }
};