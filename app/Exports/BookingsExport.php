<?php

namespace App\Exports;

use App\Models\Booking;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class BookingsExport implements FromQuery, WithHeadings, WithMapping, ShouldAutoSize
{
    use Exportable;

    protected $startDate;
    protected $endDate;
    protected $carId;      
    protected $driverId; 

    // ✨ ปรับปรุง Constructor ให้รับค่าเพิ่ม (ใส่ = null ไว้เพื่อไม่ให้พังถ้าไม่ได้ส่งมา)
    public function __construct($startDate, $endDate, $carId = null, $driverId = null)
    {
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->carId = $carId;
        $this->driverId = $driverId;
    }

    public function query()
    {
        $query = Booking::query()
            ->with(['user', 'car', 'driver']) 
            ->whereDate('start_time', '>=', $this->startDate)
            ->whereDate('start_time', '<=', $this->endDate);

        // 🎯 [เพิ่ม] เงื่อนไข Filter ถ้ามีการเลือกจากหน้าเว็บ
        if ($this->carId) {
            $query->where('car_id', $this->carId);
        }

        if ($this->driverId) {
            $query->where('driver_id', $this->driverId);
        }

        return $query->orderBy('start_time', 'asc');
    }

    public function headings(): array
    {
        return [
            'รหัสการจอง', 
            'ผู้จอง', 
            'รถยนต์', 
            'ทะเบียน', 
            'คนขับรถ',        
            'ปลายทาง', 
            'จำนวนผู้โดยสาร', 
            'วัตถุประสงค์', 
            'เวลาเริ่ม', 
            'เวลาสิ้นสุด', 
            'สถานะ', 
            'หมายเหตุผู้พิจารณา'
        ];
    }

    public function map($booking): array
    {
        $statusTh = 'รอพิจารณา';
        if ($booking->status === 'approved') $statusTh = 'อนุมัติแล้ว';
        if ($booking->status === 'rejected') $statusTh = 'ไม่อนุมัติ';

        // ✨ ดึงชื่อคนขับ ถ้าไม่มีให้ขึ้นว่า "ไม่ระบุ"
        $driverName = $booking->driver 
            ? $booking->driver->first_name . ' ' . $booking->driver->last_name 
            : 'ไม่ระบุ/พนักงานขับเอง';

        return [
            $booking->id,
            $booking->user->name ?? '-',
            $booking->car->car_name ?? '-',
            $booking->car->license_plate ?? '-',
            $driverName,                     // ✨ [แสดงชื่อคนขับใน Excel]
            $booking->destination,
            $booking->passenger_count . ' ท่าน',
            $booking->purpose,
            Carbon::parse($booking->start_time)->addYears(543)->format('d/m/Y H:i'),
            Carbon::parse($booking->end_time)->addYears(543)->format('d/m/Y H:i'),
            $statusTh,
            $booking->head_remark ?? '-'
        ];
    }
}