<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class DriverController extends Controller
{
    // 1. หน้าแสดงรายการคนขับ
    public function index(Request $request)
    {
        $query = Driver::query();

        // 🔍 ตรวจสอบว่ามีการพิมพ์คำค้นหา (search) เข้ามาหรือไม่
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('first_name', 'LIKE', "%{$search}%")
                  ->orWhere('last_name', 'LIKE', "%{$search}%");
            });
        }

        // ✨ ดึงข้อมูลทีละ 10 รายการ โดยเรียงตาม "ชื่อจริง (first_name)" ให้ถูกต้องตามหลักภาษาไทย
        $drivers = $query->orderByRaw("CONVERT(first_name USING tis620) ASC")->paginate(10); 
        
        return view('admin.drivers.index', compact('drivers'));
    }

    // 2. หน้าฟอร์มเพิ่มคนขับ
    public function create()
    {
        return view('admin.drivers.create');
    }

    // 3. บันทึกข้อมูลคนขับใหม่
    public function store(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'status' => 'required|in:available,unavailable',
            'pic' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120', // ขนาด 5MB
        ]);

        // ถ้ามีการอัปโหลดรูปคนขับมา
        if ($request->hasFile('pic')) {
            $file = $request->file('pic');
            // เติมคำว่า driver_ นำหน้า เพื่อให้แยกกับรูปรถได้ง่ายๆ
            $filename = time() . '_driver_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $filename);
            $validated['pic'] = $filename;
        }

        Driver::create($validated);

        return redirect()->route('admin.drivers.index')->with('success', 'เพิ่มข้อมูลคนขับสำเร็จ!');
    }

    // 4. หน้าฟอร์มแก้ไขคนขับ
    public function edit(Driver $driver)
    {
        return view('admin.drivers.edit', compact('driver'));
    }

    // 5. บันทึกการแก้ไขข้อมูลคนขับ
    public function update(Request $request, Driver $driver)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'status' => 'required|in:available,unavailable',
            'pic' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ]);

        // ถ้ามีการอัปโหลดรูปใหม่มาทับ
        if ($request->hasFile('pic')) {
            // ลบรูปเก่าทิ้งก่อน (ถ้ามี)
            if ($driver->pic && File::exists(public_path('img/' . $driver->pic))) {
                File::delete(public_path('img/' . $driver->pic));
            }
            
            // เซฟรูปใหม่
            $file = $request->file('pic');
            $filename = time() . '_driver_' . $file->getClientOriginalName();
            $file->move(public_path('img'), $filename);
            $validated['pic'] = $filename;
        }

        $driver->update($validated);

        return redirect()->route('admin.drivers.index')->with('success', 'อัปเดตข้อมูลคนขับสำเร็จ!');
    }

    // 6. ลบข้อมูลคนขับ
    public function destroy(Driver $driver)
    {
        // 🛡️ เช็กก่อนว่าคนขับรถคนนี้เคยมีประวัติขับรถหรือไม่
        $hasBookings = \App\Models\Booking::where('driver_id', $driver->id)->exists();
        
        if ($hasBookings) {
            return redirect()->route('admin.drivers.index')->with('error', 'ไม่อนุญาตให้ลบ! พนักงานขับรถท่านนี้มีประวัติการขับรถอยู่ในระบบ แนะนำให้เปลี่ยนสถานะเป็น "ลางาน / ไม่พร้อม" แทนครับ');
        }

        // เช็กว่ามีรูปไหม ถ้ามีให้ตามไปลบไฟล์ในโฟลเดอร์ img ทิ้งด้วย
        if ($driver->pic && File::exists(public_path('img/' . $driver->pic))) {
            File::delete(public_path('img/' . $driver->pic));
        }

        $driver->delete();
        
        return redirect()->route('admin.drivers.index')->with('success', 'ลบข้อมูลคนขับสำเร็จ!');
    }
}