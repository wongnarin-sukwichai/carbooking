<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    // อนุญาตให้บันทึกข้อมูลเหล่านี้ได้ (ปลอดภัยจาก Mass Assignment)
    protected $fillable = [
        'first_name',
        'last_name',
        'pic',
        'status',
    ];

    // คนขับ 1 คน มีประวัติการขับรถ (Bookings) หลายงาน
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}