<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Car;
use App\Models\User;
use App\Models\Booking;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. ดึงข้อมูลและนับจำนวน
        $totalCars = Car::count();
        $totalUsers = User::count();
        $pendingBookings = Booking::where('status', 'pending')->count();
        
        $today = Carbon::today();
        $activeToday = Booking::where('status', 'approved')
            ->whereDate('start_time', '<=', $today)
            ->whereDate('end_time', '>=', $today)
            ->count();

        $recentBookings = Booking::with(['user', 'car'])->latest()->take(5)->get();

        // 2. ส่งตัวแปรทั้งหมดไปที่หน้า View
        return view('admin.dashboard', compact(
            'totalCars', 
            'totalUsers', 
            'pendingBookings', 
            'activeToday', 
            'recentBookings'
        ));
    }
}