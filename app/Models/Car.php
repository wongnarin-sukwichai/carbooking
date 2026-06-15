<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    use HasFactory;

    protected $fillable = [
        'pic',
        'car_name',
        'license_plate',
        'brand',
        'color',
        'status',
    ];
    // รถ 1 คัน สามารถถูกจองได้หลายรอบ
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}