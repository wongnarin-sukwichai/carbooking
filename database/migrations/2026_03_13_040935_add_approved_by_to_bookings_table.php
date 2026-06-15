<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
        public function up()
    {
        Schema::table('bookings', function (Blueprint $table) {
            // สร้างคอลัมน์เก็บ ID ของคนอนุมัติ (ปล่อยว่างได้ถ้ายังไม่อนุมัติ)
            $table->unsignedBigInteger('approved_by')->nullable()->after('status');
        });
    }

        public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('approved_by');
        });
    }
    };
