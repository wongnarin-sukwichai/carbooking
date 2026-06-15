<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewBookingMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;

    // รับตัวแปรข้อมูลการจองเข้ามา
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    // กำหนดหัวข้ออีเมล (Subject)
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '🔔 มีคำขอใช้รถยนต์ส่วนกลางรอการพิจารณาด่วน',
        );
    }

    // กำหนดไฟล์ View ที่จะใช้แสดงเนื้อหา
    public function content(): Content
    {
        return new Content(
            // ✨ 1. แก้ไขเป็น _ ให้ตรงกับชื่อไฟล์ Blade
            view: 'emails.new_booking',
            with: ['booking' => $this->booking]
        );
    }
}