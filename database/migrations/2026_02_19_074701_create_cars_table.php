<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {

    public function up(): void
    {
        Schema::create('cars', function (Blueprint $table) {

            $table->id()->comment('รหัสรถ (Primary Key)');

            $table->string('pic')->nullable()->comment('เก็บรูปภาพรถ (URL หรือ Path)');
            
            $table->string('car_name')
                ->comment('ชื่อรถภายในองค์กร');

            $table->string('license_plate')
                ->unique()
                ->comment('หมายเลขทะเบียนรถ (ต้องไม่ซ้ำ)');

            $table->string('brand')
                ->nullable()
                ->comment('ยี่ห้อรถ');

            $table->string('color')
                ->nullable()
                ->comment('สีรถ');

            $table->enum('status',['available','maintenance'])
                ->default('available')
                ->comment('สถานะการใช้งานรถ');

            $table->timestamps();
        });

        DB::statement("ALTER TABLE cars COMMENT = 'ตารางเก็บข้อมูลรถยนต์ขององค์กร'");
    }

    public function down(): void
    {
        Schema::dropIfExists('cars');
    }
};

