<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // บันทึกข้อมูลลงคอลัมน์เหล่านี้
    protected $fillable = [
        'user_id', 
        'car_id', 
        'driver_id',
        'start_time', 
        'end_time', 
        'destination', 
        'passenger_count', 
        'purpose', 
        'status', 
        'head_remark',
        'approved_by' // ✨ ต้องเพิ่มบรรทัดนี้ครับ ระบบถึงจะยอมบันทึกข้อมูลคนอนุมัติลง Database
    ];

    // ความสัมพันธ์: การจองนี้ เป็นของ User คนไหน
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // ความสัมพันธ์: การจองนี้ ใช้รถคันไหน
    public function car()
    {
        return $this->belongsTo(Car::class, 'car_id');
    }
    
    // ความสัมพันธ์: การจองนี้ ใช้คนขับคนไหน
    public function driver()
    {
        return $this->belongsTo(Driver::class, 'driver_id');
    }
    
    // ดึงข้อมูลผู้ที่กดอนุมัติ
    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}