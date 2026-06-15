<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('visitor_stats', function (Blueprint $table) {
            $table->id();
            $table->date('date')->unique(); // เก็บสถิติรายวัน
            $table->unsignedBigInteger('views')->default(0); // จำนวนคนเข้าชมในวันนั้น
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('visitor_stats');
    }
};