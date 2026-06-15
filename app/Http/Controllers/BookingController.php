<?php

namespace App\Http\Controllers;

use App\Mail\NewBookingMail;
use App\Mail\BookingStatusMail;
use Illuminate\Support\Facades\Mail;
use App\Models\User;
use App\Models\Booking;
use App\Models\Car;
use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Exports\BookingsExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    // =======================================================
    // 1. หน้าแสดงรายการจองทั้งหมด (แยกการมองเห็นตาม Role)
    // =======================================================
    public function index(Request $request) 
    {
        $user = Auth::user();
        
        // เรียงตาม "วันที่เดินทาง" (start_time) เอาคิวล่าสุดขึ้นก่อน
        $query = Booking::with(['user', 'car', 'driver'])->orderBy('start_time', 'desc');

        // ระบบ Filter สำหรับ Admin และ Head
        if (in_array($user->role, ['admin', 'staff_head'])) {
            
            // 🔍 1. กรองตามรถยนต์
            if ($request->filled('car_id')) {
                $query->where('car_id', $request->car_id);
            }
            
            // 🔍 2. กรองตามคนขับ
            if ($request->filled('driver_id')) {
                $query->where('driver_id', $request->driver_id);
            }

            // 🔍 3. ระบบค้นหาข้อความ
            if ($request->filled('search')) {
                $searchTerm = trim($request->search);
                $query->where(function($q) use ($searchTerm) {
                    $q->whereHas('user', function($userQuery) use ($searchTerm) {
                        $userQuery->where('name', 'like', '%' . $searchTerm . '%');
                    })
                    ->orWhere('destination', 'like', '%' . $searchTerm . '%')
                    ->orWhere('purpose', 'like', '%' . $searchTerm . '%');
                });
            }
            
            $bookings = $query->paginate(10)->appends($request->all());
            
            // เรียงลำดับตัวเลือกใน Dropdown Filter
            $cars = Car::orderByRaw("CONVERT(car_name USING tis620) ASC")->get();
            $drivers = Driver::orderByRaw("CONVERT(first_name USING tis620) ASC")->get();

            $view = $user->role === 'admin' ? 'admin.bookings.index' : 'head.bookings.index';
            return view($view, compact('bookings', 'cars', 'drivers'));
        } 
        // Staff เห็นเฉพาะของตัวเอง
        else {
            $bookings = Booking::with(['car', 'driver'])
                            ->where('user_id', $user->id)
                            ->orderBy('start_time', 'desc')
                            ->paginate(7);
                            
            return view('staff.bookings.index', compact('bookings'));
        }
    }

    // =======================================================
    // 🌟 2. หน้าแสดงรายการ "รอพิจารณาอนุมัติ" (ดึงเฉพาะ Pending)
    // =======================================================
    public function pending()
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'staff_head'])) {
            return redirect()->route('bookings.my_history');
        }

        // 1. ดึงคิวรออนุมัติ (สำหรับการ์ดด้านบน)
        $pendingBookings = Booking::with(['user', 'car', 'driver'])
                            ->where('status', 'pending')
                            ->orderBy('start_time', 'asc') // เรียงจากคิวเดินทางใกล้สุดขึ้นก่อน
                            ->get();

        // 2. ดึงประวัติล่าสุด 5 รายการ (สำหรับโชว์ตารางด้านล่าง)
        $recentBookings = Booking::with(['user', 'car'])
                            ->latest() // เรียงจากใหม่สุด
                            ->take(5)  // เอาแค่ 5 รายการ
                            ->get();

        $view = $user->role === 'admin' ? 'admin.bookings.pending' : 'head.bookings.pending';
        
        return view($view, compact('pendingBookings', 'recentBookings')); 
    }

    // =======================================================
    // 3. หน้าฟอร์มจองรถ (สำหรับ Staff)
    // =======================================================
    public function create()
    {
        $cars = Car::where('status', 'available')
                    ->orderByRaw("CONVERT(car_name USING tis620) ASC")
                    ->get();
                    
        $drivers = Driver::where('status', 'available')
                    ->orderByRaw("CONVERT(first_name USING tis620) ASC")
                    ->get(); 
        
        $users = in_array(Auth::user()->role, ['admin', 'staff_head']) 
                    ? User::orderByRaw("CONVERT(name USING tis620) ASC")->get() 
                    : [];
        
        return view('staff.bookings.create', compact('cars', 'drivers', 'users'));
    }

    // =======================================================
    // 4. บันทึกข้อมูลการจองใหม่
    // =======================================================
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'car_id' => 'required|exists:cars,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'destination' => 'required|string|max:255',
            'passenger_count' => 'required|integer|min:1',
            'purpose' => 'required|string|max:1000',
        ], [
            'end_time.after' => 'เวลาสิ้นสุดการใช้งานต้องอยู่หลังเวลาเริ่มต้น',
        ]);

        // ❌ 1. เช็คคิว "รถยนต์" ซ้ำ
        $overlappingCar = Booking::with('user')
            ->where('car_id', $request->car_id)
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })->first();

        if ($overlappingCar) {
            $startTh = \Carbon\Carbon::parse($overlappingCar->start_time)->addYears(543)->format('d/m/Y H:i');
            $endTh = \Carbon\Carbon::parse($overlappingCar->end_time)->addYears(543)->format('d/m/Y H:i');
            $bookedBy = $overlappingCar->user->name ?? 'พนักงานท่านอื่น';
            
            $errorMessage = "รถคันนี้มีการจองในช่วงเวลาดังกล่าวแล้ว\nผู้จองก่อนหน้า : คุณ {$bookedBy}\nเวลาที่ชนกัน : {$startTh} น. ถึง {$endTh} น.";
            return back()->withInput()->with('error', $errorMessage);
        }

        // ❌ 2. เช็คคิว "คนขับรถ" ซ้ำ (ถ้ามีการเลือกคนขับ)
        if ($request->filled('driver_id')) {
            $overlappingDriver = Booking::with(['driver', 'user'])
                ->where('driver_id', $request->driver_id)
                ->whereIn('status', ['pending', 'approved'])
                ->where(function ($query) use ($request) {
                    $query->where('start_time', '<', $request->end_time)
                          ->where('end_time', '>', $request->start_time);
                })->first();

            if ($overlappingDriver) {
                $startTh = \Carbon\Carbon::parse($overlappingDriver->start_time)->addYears(543)->format('d/m/Y H:i');
                $endTh = \Carbon\Carbon::parse($overlappingDriver->end_time)->addYears(543)->format('d/m/Y H:i');
                $driverName = $overlappingDriver->driver->first_name . ' ' . $overlappingDriver->driver->last_name;
                
                $errorMessage = "พนักงานขับรถ ({$driverName}) ติดคิวงานอื่นในช่วงเวลานี้แล้ว\nเวลาที่ชนกัน : {$startTh} น. ถึง {$endTh} น.";
                return back()->withInput()->with('error', $errorMessage);
            }
        }

        $userRole = Auth::user()->role;
        $userIdForBooking = Auth::id();
        $isBookingForOther = false; 

        if (in_array($userRole, ['admin', 'staff_head'])) {
            $status = 'approved'; 
            
            if ($request->filled('user_id')) {
                $userIdForBooking = $request->user_id;
                if ($userIdForBooking != Auth::id()) {
                    $isBookingForOther = true;
                }
            }
        } else {
            $status = 'pending'; 
        }

        $booking = Booking::create([
            'user_id' => $userIdForBooking,
            'car_id' => $request->car_id,
            'driver_id' => $request->driver_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'destination' => $request->destination,
            'passenger_count' => $request->passenger_count,
            'purpose' => $request->purpose,
            'status' => $status,
            'approved_by' => $status === 'approved' ? Auth::id() : null, 
        ]);

        if ($booking->status === 'pending') {
            try {
                $booking->load(['user', 'car', 'driver']);
                
                $headEmails = User::where('role', 'staff_head')->pluck('email')->toArray();
                
                if (!empty($headEmails)) {
                    Mail::to($headEmails)->send(new NewBookingMail($booking));
                }
            } catch (\Exception $e) {
                Log::error('ส่งอีเมลแจ้งเตือน Head ไม่สำเร็จ: ' . $e->getMessage());
            }
        } elseif ($isBookingForOther && $booking->status === 'approved') {
            try {
                $booking->load(['user', 'car', 'driver']); 
                Mail::to($booking->user->email)->send(new BookingStatusMail($booking));
            } catch (\Exception $e) {
                Log::error('ส่งอีเมลแจ้งคนจองแทนไม่สำเร็จ: ' . $e->getMessage());
            }
        }

        if ($userRole === 'admin') {
            return redirect()->route('admin.bookings.index')->with('success', 'เพิ่มคิวรถและอนุมัติสำเร็จแล้ว');
        } elseif ($userRole === 'staff_head') {
            return redirect()->route('head.bookings.index')->with('success', 'เพิ่มคิวรถและอนุมัติสำเร็จแล้ว');
        } else {
            return redirect()->route('bookings.my_history')->with('success', 'ส่งคำขอจองรถสำเร็จ กรุณารอผลการอนุมัติครับ');
        }
    }

    // =======================================================
    // 🌟 5. เปิดหน้าแก้ไขข้อมูลการจอง (สำหรับ Admin/Head)
    // =======================================================
    public function edit(Booking $booking)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'staff_head'])) {
            return back()->with('error', 'คุณไม่มีสิทธิ์เข้าถึงหน้านี้');
        }

        $cars = Car::where('status', 'available')
                ->orWhere('id', $booking->car_id)
                ->orderByRaw("CONVERT(car_name USING tis620) ASC")
                ->get(); 
                
        $drivers = Driver::where('status', 'available')
                ->orWhere('id', $booking->driver_id)
                ->orderByRaw("CONVERT(first_name USING tis620) ASC")
                ->get(); 

        $view = $user->role === 'admin' ? 'admin.bookings.edit' : 'head.bookings.edit';
        return view($view, compact('booking', 'cars', 'drivers'));
    }

    // =======================================================
    // 🌟 6. อัปเดตข้อมูลและสถานะรวดเดียว (สำหรับ Admin/Head)
    // =======================================================
    public function update(Request $request, Booking $booking)
    {
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'staff_head'])) {
            return back()->with('error', 'คุณไม่มีสิทธิ์ทำรายการนี้');
        }

        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'destination' => 'required|string|max:255',
            'passenger_count' => 'required|integer|min:1',
            'purpose' => 'required|string|max:1000',
            'status' => 'required|in:pending,approved,rejected',
            'head_remark' => 'nullable|string|max:1000',
        ], [
            'end_time.after' => 'เวลาสิ้นสุดการใช้งานต้องอยู่หลังเวลาเริ่มต้น',
        ]);
        
        // ❌ 1. เช็คคิว "รถยนต์" ซ้ำ (ยกเว้นคิวนี้เอง)
        $overlappingCar = Booking::with('user')
            ->where('car_id', $request->car_id)
            ->where('id', '!=', $booking->id) 
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })->first();

        if ($overlappingCar) {
            $startTh = \Carbon\Carbon::parse($overlappingCar->start_time)->addYears(543)->format('d/m/Y H:i');
            $endTh = \Carbon\Carbon::parse($overlappingCar->end_time)->addYears(543)->format('d/m/Y H:i');
            $bookedBy = $overlappingCar->user->name ?? 'พนักงานท่านอื่น';
            
            return back()->withInput()->with('error', "รถคันนี้ติดคิวของ คุณ {$bookedBy} แล้ว\nเวลา: {$startTh} น. ถึง {$endTh} น.");
        }

        // ❌ 2. เช็คคิว "คนขับ" ซ้ำ (ยกเว้นคิวนี้เอง)
        if ($request->filled('driver_id')) {
            $overlappingDriver = Booking::with(['driver', 'user'])
                ->where('driver_id', $request->driver_id)
                ->where('id', '!=', $booking->id) 
                ->whereIn('status', ['pending', 'approved'])
                ->where(function ($query) use ($request) {
                    $query->where('start_time', '<', $request->end_time)
                          ->where('end_time', '>', $request->start_time);
                })->first();

            if ($overlappingDriver) {
                $startTh = \Carbon\Carbon::parse($overlappingDriver->start_time)->addYears(543)->format('d/m/Y H:i');
                $endTh = \Carbon\Carbon::parse($overlappingDriver->end_time)->addYears(543)->format('d/m/Y H:i');
                $driverName = $overlappingDriver->driver->first_name . ' ' . $overlappingDriver->driver->last_name;
                
                return back()->withInput()->with('error', "พนักงานขับรถ ({$driverName}) ติดคิวงานอื่นแล้ว\nเวลา: {$startTh} น. ถึง {$endTh} น.");
            }
        }

        $booking->update([
            'car_id' => $request->car_id,
            'driver_id' => $request->driver_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'destination' => $request->destination,
            'passenger_count' => $request->passenger_count,
            'purpose' => $request->purpose,
            'status' => $request->status,
            'head_remark' => $request->head_remark,
            'approved_by' => $request->status !== 'pending' ? Auth::id() : null,
        ]);

        if ($request->status !== 'pending') {
            try {
                $booking->load(['user', 'car', 'driver']); 
                Mail::to($booking->user->email)->send(new BookingStatusMail($booking));
            } catch (\Exception $e) {
                Log::error('ส่งอีเมลแจ้งผลไม่สำเร็จ: ' . $e->getMessage());
            }
        }

        $routePrefix = $user->role === 'admin' ? 'admin' : 'head';
        return redirect()->route($routePrefix . '.bookings.index')->with('success', 'บันทึกข้อมูลและปรับสถานะเรียบร้อยแล้ว');
    }

    // =======================================================
    // 7. ดึงประวัติการจองของตัวเอง
    // =======================================================
    public function myHistory()
    {
        $bookings = Booking::with(['car', 'driver'])
                    ->where('user_id', Auth::id())
                    ->orderBy('start_time', 'desc')
                    ->paginate(7);
                    
        return view('staff.bookings.index', compact('bookings'));
    }

    // =======================================================
    // 8. API สำหรับ FullCalendar
    // =======================================================
    public function getCalendarEvents()
    {
        $bookings = Booking::with(['user', 'car', 'driver'])
            ->whereIn('status', ['pending', 'approved'])
            ->get();

        $events = [];
        foreach ($bookings as $booking) {
            $color = $booking->status === 'approved' ? '#10b981' : '#f59e0b';
            
            $events[] = [
                'id' => $booking->id,
                'title' => ($booking->car->car_name ?? 'รถยนต์') . ' - ' . ($booking->user->name ?? 'ไม่ทราบชื่อ'),
                'start' => $booking->start_time,
                'end' => $booking->end_time,
                'color' => $color,
                'extendedProps' => [
                    'car_name' => $booking->car->car_name ?? 'ไม่ระบุชื่อรถ',
                    'license_plate' => $booking->car->license_plate ?? '-',
                    'car_pic' => $booking->car->pic ?? null, 
                    'user_name' => $booking->user->name ?? 'ไม่ทราบชื่อ',
                    'driver_name' => $booking->driver 
                        ? $booking->driver->first_name . ' ' . $booking->driver->last_name 
                        : 'ไม่ระบุคนขับ/ขับเอง',
                    'driver_pic' => $booking->driver->pic ?? null, 
                    'destination' => $booking->destination,
                    'purpose' => $booking->purpose,
                    'status' => $booking->status,
                    'passenger_count' => $booking->passenger_count
                ]
            ];
        }
        return response()->json($events);
    }

    // =======================================================
    // 9. Export ข้อมูลเป็น Excel
    // =======================================================
    public function export(Request $request)
    {
        if (!in_array(auth()->user()->role, ['admin', 'staff_head'])) {
            return back()->with('error', 'คุณไม่มีสิทธิ์ดาวน์โหลดข้อมูลส่วนนี้');
        }

        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $fileName = 'รายงานการจองรถ_' . \Carbon\Carbon::now()->format('Ymd') . '.xlsx';

        return Excel::download(new BookingsExport(
            $request->start_date, 
            $request->end_date,
            $request->car_id, 
            $request->driver_id
        ), $fileName);
    }

    // =======================================================
    // 🌟 [ปรับปรุง] อัปเดตสถานะด่วนจากหน้า Pop-up
    // =======================================================
    public function updateFastStatus(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected', 
            'head_remark' => 'nullable|string|max:1000',
        ]);

        $user = Auth::user();

        if (in_array($user->role, ['admin', 'staff_head'])) {
            
            // ❌ ถ้ากด "อนุมัติ" ต้องเช็คว่ารถและคนขับว่างไหมก่อน
            if ($request->status === 'approved') {
                
                // เช็คคิวรถซ้ำ
                $overlappingCar = Booking::with('user')
                    ->where('car_id', $booking->car_id)
                    ->where('id', '!=', $booking->id) 
                    ->where('status', 'approved') // ชนกับคิวที่อนุมัติไปแล้วเท่านั้น
                    ->where(function ($query) use ($booking) {
                        $query->where('start_time', '<', $booking->end_time)
                              ->where('end_time', '>', $booking->start_time);
                    })->first();

                if ($overlappingCar) {
                    $startTh = \Carbon\Carbon::parse($overlappingCar->start_time)->addYears(543)->format('d/m/Y H:i');
                    $endTh = \Carbon\Carbon::parse($overlappingCar->end_time)->addYears(543)->format('d/m/Y H:i');
                    $bookedBy = $overlappingCar->user->name ?? 'พนักงานท่านอื่น';
                    return back()->with('error', "ไม่อนุมัติ! รถคันนี้มีคิวชนกับ คุณ {$bookedBy}\nเวลา: {$startTh} น. ถึง {$endTh} น.");
                }

                // เช็คคิวคนขับซ้ำ
                if ($booking->driver_id) {
                    $overlappingDriver = Booking::with(['driver', 'user'])
                        ->where('driver_id', $booking->driver_id)
                        ->where('id', '!=', $booking->id) 
                        ->where('status', 'approved')
                        ->where(function ($query) use ($booking) {
                            $query->where('start_time', '<', $booking->end_time)
                                  ->where('end_time', '>', $booking->start_time);
                        })->first();

                    if ($overlappingDriver) {
                        $startTh = \Carbon\Carbon::parse($overlappingDriver->start_time)->addYears(543)->format('d/m/Y H:i');
                        $endTh = \Carbon\Carbon::parse($overlappingDriver->end_time)->addYears(543)->format('d/m/Y H:i');
                        $driverName = $overlappingDriver->driver->first_name . ' ' . $overlappingDriver->driver->last_name;
                        return back()->with('error', "ไม่อนุมัติ! คนขับรถ ({$driverName}) ติดคิวงานอื่นแล้ว\nเวลา: {$startTh} น. ถึง {$endTh} น.");
                    }
                }
            }

            // ถ้าไม่มีคิวชน ค่อยบันทึกสถานะลงระบบ
            $booking->update([
                'status' => $request->status,
                'head_remark' => $request->head_remark,
                'approved_by' => Auth::id(), 
            ]);

            // ส่งอีเมลถ้ามีการอนุมัติหรือปฏิเสธ
            if ($request->status !== 'pending') {
                try {
                    $booking->load(['user', 'car', 'driver']); 
                    Mail::to($booking->user->email)->send(new BookingStatusMail($booking));
                } catch (\Exception $e) {
                    Log::error('ส่งอีเมลแจ้งผลไม่สำเร็จ: ' . $e->getMessage());
                }
            }

            return back()->with('success', 'บันทึกการพิจารณาคิวรถ และส่งอีเมลเรียบร้อยแล้ว');
        }

        return back()->with('error', 'คุณไม่มีสิทธิ์ทำรายการนี้');
    }

    // =======================================================
    // 🌟 ฟังก์ชันลบข้อมูลการจอง (สำหรับ Admin/Head)
    // =======================================================
    public function destroy(Booking $booking)
    {
        if (!in_array(Auth::user()->role, ['admin', 'staff_head'])) {
            return back()->with('error', 'คุณไม่มีสิทธิ์ลบข้อมูลนี้');
        }

        try {
            $booking->delete();
            return back()->with('success', 'ลบข้อมูลการจองออกจากระบบเรียบร้อยแล้ว');
        } catch (\Exception $e) {
            Log::error('ลบข้อมูลไม่สำเร็จ: ' . $e->getMessage());
            return back()->with('error', 'เกิดข้อผิดพลาดในการลบข้อมูล');
        }
    }

    // =======================================================
    // 🌟 [เพิ่มใหม่] หน้าแสดงรายละเอียดการจองเต็มรูปแบบ (ดูได้ทุกคน)
    // =======================================================
    public function show(Booking $booking)
    {
        $user = Auth::user();
        
        // เช็คสิทธิ์: ถ้าเป็นพนักงานธรรมดา จะดูรายละเอียดได้เฉพาะคิวที่ตัวเองเป็นคนจองเท่านั้น
        if (!in_array($user->role, ['admin', 'staff_head']) && $booking->user_id !== $user->id) {
            abort(403, 'คุณไม่มีสิทธิ์เข้าถึงข้อมูลนี้');
        }

        // ดึงข้อมูลความสัมพันธ์ทั้งหมดมาใช้งานในหน้า View
        $booking->load(['user', 'car', 'driver']);
        
        // ส่งตัวแปร $booking ไปให้หน้า show.blade.php
        return view('staff.bookings.show', compact('booking'));
    }

    // =======================================================
    // 🌟 [เพิ่มใหม่] หน้าฟอร์มให้พนักงานแก้ไขคำขอตัวเอง 
    // =======================================================
    public function editStaff(Booking $booking)
    {
        // กฎเหล็ก: แก้ได้เฉพาะคิวตัวเอง และสถานะต้องเป็น 'รอพิจารณา' (pending) เท่านั้น
        if ($booking->user_id !== Auth::id() || $booking->status !== 'pending') {
            return back()->with('error', 'ไม่สามารถแก้ไขได้ เนื่องจากรายการนี้ถูกพิจารณาไปแล้ว หรือคุณไม่มีสิทธิ์');
        }

        // ดึงรายชื่อรถและคนขับที่ว่าง (และรวมตัวเก่าที่เคยเลือกไว้ด้วย)
        $cars = Car::where('status', 'available')
                    ->orWhere('id', $booking->car_id)
                    ->orderByRaw("CONVERT(car_name USING tis620) ASC")
                    ->get();
                    
        $drivers = Driver::where('status', 'available')
                    ->orWhere('id', $booking->driver_id)
                    ->orderByRaw("CONVERT(first_name USING tis620) ASC")
                    ->get();
        
        return view('staff.bookings.edit', compact('booking', 'cars', 'drivers'));
    }

    // =======================================================
    // 🌟 [เพิ่มใหม่] บันทึกการแก้ไขคำขอจากพนักงาน
    // =======================================================
    public function updateStaff(Request $request, Booking $booking)
    {
        // ตรวจสอบความถูกต้องของสิทธิ์อีกครั้ง (ป้องกันคนแฮกยิง API)
        if ($booking->user_id !== Auth::id() || $booking->status !== 'pending') {
            return back()->with('error', 'ไม่สามารถแก้ไขได้ เนื่องจากรายการนี้ถูกพิจารณาไปแล้ว');
        }

        // ตรวจสอบความถูกต้องของข้อมูล (Validation)
        $request->validate([
            'car_id' => 'required|exists:cars,id',
            'driver_id' => 'nullable|exists:drivers,id',
            'start_time' => 'required|date',
            'end_time' => 'required|date|after:start_time',
            'destination' => 'required|string|max:255',
            'passenger_count' => 'required|integer|min:1',
            'purpose' => 'required|string|max:1000',
        ], ['end_time.after' => 'เวลาสิ้นสุดการใช้งานต้องอยู่หลังเวลาเริ่มต้น']);

        // ❌ เช็คคิวรถซ้ำ: ดึงคิวรถคันที่กำลังจะแก้ (แต่ต้องไม่รวมคิวของตัวเอง)
        $overlappingCar = Booking::with('user')
            ->where('car_id', $request->car_id)
            ->where('id', '!=', $booking->id) 
            ->whereIn('status', ['pending', 'approved'])
            ->where(function ($query) use ($request) {
                $query->where('start_time', '<', $request->end_time)
                      ->where('end_time', '>', $request->start_time);
            })->first();

        // ถ้ารถติดคิวคันอื่น ให้เด้งกลับไปเตือน
        if ($overlappingCar) {
            $startTh = \Carbon\Carbon::parse($overlappingCar->start_time)->addYears(543)->format('d/m/Y H:i');
            $endTh = \Carbon\Carbon::parse($overlappingCar->end_time)->addYears(543)->format('d/m/Y H:i');
            $bookedBy = $overlappingCar->user->name ?? 'พนักงานท่านอื่น';
            return back()->withInput()->with('error', "รถคันนี้ติดคิวของ คุณ {$bookedBy} แล้ว\nเวลา: {$startTh} น. ถึง {$endTh} น.");
        }

        // ❌ เช็คคิวคนขับซ้ำ (ถ้ามีการระบุคนขับ)
        if ($request->filled('driver_id')) {
            $overlappingDriver = Booking::with(['driver', 'user'])
                ->where('driver_id', $request->driver_id)
                ->where('id', '!=', $booking->id) 
                ->whereIn('status', ['pending', 'approved'])
                ->where(function ($query) use ($request) {
                    $query->where('start_time', '<', $request->end_time)
                          ->where('end_time', '>', $request->start_time);
                })->first();

            // ถ้าคนขับติดคิวงานอื่น ให้เด้งกลับไปเตือน
            if ($overlappingDriver) {
                $startTh = \Carbon\Carbon::parse($overlappingDriver->start_time)->addYears(543)->format('d/m/Y H:i');
                $endTh = \Carbon\Carbon::parse($overlappingDriver->end_time)->addYears(543)->format('d/m/Y H:i');
                $driverName = $overlappingDriver->driver->first_name . ' ' . $overlappingDriver->driver->last_name;
                return back()->withInput()->with('error', "พนักงานขับรถ ({$driverName}) ติดคิวงานอื่นแล้ว\nเวลา: {$startTh} น. ถึง {$endTh} น.");
            }
        }

        // เมื่อทุกอย่างปลอดภัย ค่อยบันทึกการอัปเดตข้อมูล
        // ⚠️ สังเกตว่าเราไม่ได้ให้สิทธิ์แก้ไข 'status' (ป้องกันพนักงานแอบแก้เป็น 'approved' เอง)
        $booking->update([
            'car_id' => $request->car_id,
            'driver_id' => $request->driver_id,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'destination' => $request->destination,
            'passenger_count' => $request->passenger_count,
            'purpose' => $request->purpose,
        ]);

        return redirect()->route('bookings.my_history')->with('success', 'บันทึกการแก้ไขคำขอจองรถสำเร็จ');
    }

    // =======================================================
    // 🌟 [เพิ่มใหม่] ฟังก์ชันให้พนักงานกดยกเลิก/ลบคำขอตัวเอง
    // =======================================================
    public function destroyStaff(Booking $booking)
    {
        // กฎเหล็ก: ลบได้เฉพาะคิวตัวเอง และสถานะต้องเป็น 'รอพิจารณา' (pending) เท่านั้น
        if ($booking->user_id !== Auth::id() || $booking->status !== 'pending') {
            return back()->with('error', 'ไม่สามารถยกเลิกคำขอได้ เนื่องจากคิวถูกพิจารณาไปแล้ว หรือคุณไม่มีสิทธิ์');
        }

        // ดำเนินการลบออกจากฐานข้อมูล
        $booking->delete();
        
        return back()->with('success', 'ยกเลิกคำขอจองรถของคุณเรียบร้อยแล้ว');
    }
}