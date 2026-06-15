// ไฟล์: public/js/custom-datepicker.js

document.addEventListener("DOMContentLoaded", function() {
    
    // 1. สร้างฟังก์ชันดึงการตั้งค่าพื้นฐาน (เพื่อให้เช็กเงื่อนไขของแต่ละช่องได้)
    const getBaseConfig = (element) => {
        return {
            enableTime: true,        
            time_24hr: true,         
            locale: "th",            
            // ถ้าช่องนั้นมี attribute data-allow-past="true" จะไม่ล็อกวันย้อนหลัง
            minDate: element.dataset.allowPast === "true" ? null : "today",
            dateFormat: "Y-m-d H:i", 
            altInput: true,
            altFormat: "d M Y เวลา H:i น."
        };
    };

    // 2. ตั้งค่าเฉพาะช่อง "เริ่มเดินทาง" 
    const startTimeEl = document.getElementById('start_time');
    if (startTimeEl) {
        flatpickr(startTimeEl, {
            ...getBaseConfig(startTimeEl),
            defaultHour: 8,    // เริ่ม 08:00
            defaultMinute: 30  // นาทีที่ 30
        });
    }

    // 3. ตั้งค่าเฉพาะช่อง "เดินทางกลับ"
    const endTimeEl = document.getElementById('end_time');
    if (endTimeEl) {
        flatpickr(endTimeEl, {
            ...getBaseConfig(endTimeEl),
            defaultHour: 16,   // เริ่ม 16:00
            defaultMinute: 30  // นาทีที่ 30
        });
    }

    // 4. เผื่อหน้าอื่นๆ ที่ใช้คลาส .init-flatpickr ทั่วไป 
    document.querySelectorAll(".init-flatpickr:not(#start_time):not(#end_time)").forEach(el => {
        flatpickr(el, getBaseConfig(el));
    });

});