/**
 * ไฟล์: public/js/car-zoom.js
 * ใช้สำหรับซูมรูปภาพรถยนต์หรือรูปพนักงานในทุกหน้าของระบบ
 */

window.zoomCarImage = function(event, imageUrl, carName) {
    // 1. ป้องกัน Event ไปรบกวน Element อื่น (เช่น การไปกดโดน Radio label)
    if (event) {
        if (typeof event.preventDefault === 'function') event.preventDefault();
        if (typeof event.stopPropagation === 'function') event.stopPropagation();
    }
    
    // 2. ตรวจสอบว่าโหลด SweetAlert2 มาแล้วหรือยัง
    if (typeof Swal === 'undefined') {
        console.warn('SweetAlert2 is missing. Opening image in new tab instead.');
        window.open(imageUrl, '_blank');
        return;
    }

    // 3. ตรวจสอบสถานะ Dark Mode ณ ขณะนั้น
    const isDark = document.documentElement.classList.contains('dark');

    // 4. แสดงผล Pop-up
    Swal.fire({
        title: carName,
        imageUrl: imageUrl,
        imageAlt: carName,
        // ปรับสีตามธีม
        background: isDark ? '#1e293b' : '#ffffff',
        color: isDark ? '#f1f5f9' : '#1e293b',
        
        width: '500px',
        imageWidth: '100%',
        padding: '1.25rem',
        showCloseButton: true,
        showConfirmButton: false, // ซ่อนปุ่มตกลงเพื่อให้ดูคลีนขึ้น (กดกากบาทหรือขอบนอกเพื่อปิด)
        
        customClass: {
            popup: 'rounded-3xl border border-gray-200 dark:border-slate-700 shadow-2xl',
            title: 'text-lg font-bold mb-4',
            image: 'rounded-2xl shadow-sm border border-gray-100 dark:border-slate-600 object-cover max-h-[65vh]',
            closeButton: 'focus:outline-none'
        },
        
        // แอนิเมชันตอนเปิด
        showClass: {
            popup: 'animate__animated animate__zoomIn animate__faster'
        },
        hideClass: {
            popup: 'animate__animated animate__zoomOut animate__faster'
        }
    });
}