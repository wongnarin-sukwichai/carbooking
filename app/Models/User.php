<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'google_id',
        'name',
        'email',
        'avatar',
        'role',
    ];

    protected $hidden = [];

    // ปิดการใช้งาน remember_token เพราะไม่มี column นี้ในฐานข้อมูล
    public function getRememberToken() { return null; }
    public function setRememberToken($value) {}
    public function getRememberTokenName() { return ''; }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
