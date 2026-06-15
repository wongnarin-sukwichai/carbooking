<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingStatusMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function envelope(): Envelope
    {
        // กำหนดหัวข้ออีเมลให้เปลี่ยนตามสถานะ
        $statusText = $this->booking->status === 'approved' ? '✅ อนุมัติ' : '❌ ไม่อนุมัติ';
        
        return new Envelope(
            subject: "แจ้งผลการพิจารณาคำขอใช้รถยนต์: {$statusText}",
        );
    }

    public function content(): Content
    {
        return new Content(
            // ✨ 1. แก้ไขเป็น _ ให้ตรงกับชื่อไฟล์ Blade
            view: 'emails.booking_status', 
            // ✨ 2. เพิ่มการส่งตัวแปร $booking ไปให้ View
            with: ['booking' => $this->booking] 
        );
    }
}