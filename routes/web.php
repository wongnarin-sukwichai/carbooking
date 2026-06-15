<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Http\Controllers\CarController;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\MainController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Staff\DashboardController as StaffDashboard;
use App\Http\Controllers\Head\DashboardController as HeadDashboard;
use App\Http\Controllers\ProfileController; 

// =======================================================
// 🌟 1. หน้าแรก (Public Routes) เข้าได้โดยไม่ต้อง Login
// =======================================================
Route::get('/', [MainController::class, 'index'])->name('main');

// ✨ วาง API ปฏิทินไว้ตรงนี้ที่เดียวเท่านั้น เพื่อให้เป็น Public เต็มตัว
Route::get('/api/calendar-events', [BookingController::class, 'getCalendarEvents'])->name('api.calendar.events');

// =======================================================
// 2. Route กลาง: สำหรับแยกทางหลัง Login
// =======================================================
Route::middleware(['auth'])->get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    if ($user->role === 'staff_head') {
        return redirect()->route('head.dashboard');
    }
    return redirect()->route('staff.dashboard');
})->name('dashboard');


/*
|--------------------------------------------------------------------------
| 🌟 Shared Routes (ส่วนกลางสำหรับทุกคนที่ล็อกอิน)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    // จองรถยนต์
    Route::get('/book-car', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/book-car', [BookingController::class, 'store'])->name('bookings.store');
    
    // ประวัติการจองของตัวเอง
    Route::get('/my-bookings', [BookingController::class, 'myHistory'])->name('bookings.my_history');

    // ✨ 1. [เพิ่มใหม่] Route สำหรับเปิดหน้ารายละเอียดแบบเต็ม (Timeline) เข้าได้ทุกคน
    Route::get('/bookings/{booking}/details', [BookingController::class, 'show'])->name('bookings.show');
    
    // ✨ 2. [เพิ่มใหม่] Route สำหรับให้พนักงานทั่วไปแก้ไขและยกเลิกคำขอตัวเองได้
    Route::get('/my-bookings/{booking}/edit', [BookingController::class, 'editStaff'])->name('bookings.edit_staff');
    Route::put('/my-bookings/{booking}', [BookingController::class, 'updateStaff'])->name('bookings.update_staff');
    Route::delete('/my-bookings/{booking}', [BookingController::class, 'destroyStaff'])->name('bookings.destroy_staff');

    // ✨ Smart Redirect สำหรับลิงก์ในอีเมล (พาไปหน้า Pending ตาม Role)
    Route::get('/smart-redirect/pending', function () {
        $role = auth()->user()->role;
        if ($role === 'admin') return redirect()->route('admin.bookings.pending');
        if ($role === 'staff_head') return redirect()->route('head.bookings.pending');
        // ถ้าพนักงานทั่วไปกดลิงก์นี้ ให้พาไปหน้าประวัติส่วนตัว
        return redirect()->route('bookings.my_history'); 
    })->name('smart.pending');
    
    // 🌟 Export ข้อมูลการจองเป็น Excel (ดักสิทธิ์ไว้ใน Controller แล้ว)
    Route::post('/export/bookings', [BookingController::class, 'export'])->name('bookings.export');

    // ✨ ส่วนที่เพิ่มใหม่: บริการส่วนตัว (แก้ไข Profile)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.update_password');
});

/*
|--------------------------------------------------------------------------
| Admin Routes (สำหรับผู้ดูแลระบบ)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    Route::resource('cars', CarController::class);
    Route::resource('drivers', DriverController::class);
    Route::resource('users', UserController::class)->except(['show']);    
    
    // เมนูคิวรออนุมัติ (ต้องอยู่ก่อนหน้าที่มี Parameter เสมอ)
    Route::get('/bookings/pending', [BookingController::class, 'pending'])->name('bookings.pending');
    
    // เมนูรายการจองทั้งหมด
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    
    // ระบบแก้ไขข้อมูลและการพิจารณา 
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::patch('/bookings/{booking}/fast-status', [BookingController::class, 'updateFastStatus'])->name('bookings.updateFastStatus');
});

/*
|--------------------------------------------------------------------------
| Head Routes (สำหรับหัวหน้า/ผู้ทำงานอนุมัติ)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff_head'])->prefix('head')->name('head.')->group(function () {
    Route::get('/dashboard', [HeadDashboard::class, 'index'])->name('dashboard');
    
    // เมนูคิวรออนุมัติ 
    Route::get('/bookings/pending', [BookingController::class, 'pending'])->name('bookings.pending');
    
    // เมนูรายการจองทั้งหมด
    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index'); 
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');
    
    // ระบบแก้ไขข้อมูลและการพิจารณา 
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::put('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::patch('/bookings/{booking}/fast-status', [BookingController::class, 'updateFastStatus'])->name('bookings.updateFastStatus');
});

/*
|--------------------------------------------------------------------------
| Staff Routes (สำหรับพนักงานทั่วไป)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:staff'])->prefix('staff')->name('staff.')->group(function () {
    Route::get('/dashboard', [StaffDashboard::class, 'index'])->name('dashboard');
});


// โหลด Route ของระบบ Auth พื้นฐาน (เช่น /login)
require __DIR__.'/auth.php';

// =======================================================
// 🌟 3. บังคับ Override การ Logout ให้ไปหน้า Login เสมอ
// =======================================================
Route::post('/logout', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    
    // บังคับเด้งไปหน้า login เสมอ
    return redirect('/login'); 
})->name('logout');