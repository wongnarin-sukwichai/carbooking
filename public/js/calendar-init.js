// 🌟 ฟังก์ชันช่วยแปลงรูปแบบวันที่และเวลาให้เป็นภาษาไทยสวยๆ (เช่น 12 มี.ค. 2567 09:00 น.)
function formatThaiDateTime(dateObject) {
    if (!dateObject) return '-';
    return dateObject.toLocaleDateString('th-TH', {
        year: 'numeric', 
        month: 'short', 
        day: 'numeric',
        hour: '2-digit', 
        minute: '2-digit'
    }) + ' น.';
}

// 🌟 ฟังก์ชันซูมรูปภาพ (สร้าง Overlay Lightbox แบบไม่ต้องง้อ Plugin)
window.zoomCalendarImage = function(e, src, title) {
    if (e) e.stopPropagation(); // ป้องกันไม่ให้ Event ไปทับซ้อนกับกล่องอื่นๆ
    
    // 💡 ทริค: ถ้าเป็นรูปภาพที่สร้างจากชื่ออัตโนมัติ (ui-avatars) จะไม่ให้ซูม เพราะรูปมันจะแตก
    if (src.includes('ui-avatars.com')) return;

    // 1. สร้างฉากหลังสีดำโปร่งแสง (Overlay)
    var overlay = document.createElement('div');
    overlay.className = 'fixed inset-0 flex items-center justify-center cursor-zoom-out transition-all duration-300 opacity-0';
    overlay.style.zIndex = '99999';
    overlay.style.backgroundColor = 'rgba(15, 23, 42, 0.85)'; // สี slate-900 โปร่งแสง 85%
    overlay.style.backdropFilter = 'blur(4px)'; // เบลอพื้นหลังนิดๆ ให้ดูแพง
    
    // กดที่ฉากหลังเพื่อปิด
    overlay.onclick = function() {
        overlay.style.opacity = '0';
        overlay.firstElementChild.classList.remove('scale-100');
        overlay.firstElementChild.classList.add('scale-95');
        setTimeout(() => overlay.remove(), 300); // รอแอนิเมชันจบแล้วลบทิ้ง
    };

    // 2. สร้างกล่องใส่รูปภาพและข้อความ
    var container = document.createElement('div');
    // ✨ เอา max-w-xl ออก แล้วใช้ style บังคับความกว้างสูงสุดแทน
    container.className = 'relative flex flex-col items-center p-4 transform scale-95 transition-transform duration-300 mx-auto';
    container.style.maxWidth = '90vw'; // กว้างได้เต็มที่แค่ 90% ของหน้าจอ
    
    // รูปภาพ
    var img = document.createElement('img');
    img.src = src;
    img.className = 'rounded-2xl shadow-2xl border-4 border-white/10';
    // ✨ ใช้ Inline CSS บังคับขนาดให้เป๊ะ ป้องกัน Tailwind ทำงานพลาดใน JS
    img.style.maxWidth = '100%';
    img.style.maxHeight = '75vh'; // สูงได้เต็มที่แค่ 75% ของความสูงหน้าจอ (ไม่ล้นจอแน่นอน)
    img.style.objectFit = 'contain'; // รักษาสัดส่วนรูปภาพ ไม่ให้เบี้ยว
    
    container.appendChild(img);

    // ป้ายชื่อ (ถ้ามีการส่ง title มา)
    if (title) {
        var text = document.createElement('div');
        text.className = 'text-white mt-4 text-sm md:text-base font-bold tracking-wider bg-slate-800/90 border border-slate-600 px-6 py-2 rounded-full shadow-lg backdrop-blur-md';
        text.innerText = title;
        container.appendChild(text);
    }

    overlay.appendChild(container);
    document.body.appendChild(overlay);

    // 3. เริ่มแสดงแอนิเมชัน
    requestAnimationFrame(() => {
        overlay.style.opacity = '1';
        container.classList.remove('scale-95');
        container.classList.add('scale-100');
    });
}

document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    if (!calendarEl) return;

    var eventsUrl = calendarEl.getAttribute('data-events-url');
    
    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        locale: 'th',
        height: 'auto',
        displayEventTime: false, // ปิดการโชว์เวลาข้างหน้าชื่อบนปฏิทิน
        eventDisplay: 'block',   // บังคับเป็นแถบสีทึบ
        
        headerToolbar: {
            left: 'prev,next today',
            center: 'title',
            right: 'dayGridMonth,timeGridWeek,listWeek'
        },
        buttonText: {
            today: 'วันนี้',
            month: 'เดือน',
            week: 'สัปดาห์',
            list: 'รายการ'
        },
        events: eventsUrl,
        
        // 🌟 อัปเกรดหน้าตา Pop-up เมื่อคลิกที่แถบปฏิทิน
        eventClick: function(info) {
            info.jsEvent.preventDefault(); // ป้องกันการเปลี่ยนหน้า

            var props = info.event.extendedProps;
            var startTime = formatThaiDateTime(info.event.start);
            var endTime = formatThaiDateTime(info.event.end);

            var statusHtml = props.status === 'approved' 
                ? '<span class="px-3 py-1 bg-green-100 text-green-700 border border-green-200 rounded-full text-xs font-bold shadow-sm">🟢 อนุมัติแล้ว</span>' 
                : '<span class="px-3 py-1 bg-yellow-100 text-yellow-700 border border-yellow-200 rounded-full text-xs font-bold shadow-sm">🟡 รอพิจารณา</span>';

            // ✨ แก้ปัญหารูปไม่ขึ้น: ดึง Base URL มาจาก eventsUrl อัตโนมัติ
            var baseUrl = eventsUrl.split('/api/')[0]; 
            var basePath = baseUrl + '/img/'; 
            
            // ถ้ารถไม่มีรูป ให้สร้างรูปชื่อรถแทน
            var carImg = props.car_pic ? (basePath + props.car_pic) : ('https://ui-avatars.com/api/?name=Car&background=e2e8f0&color=64748b');
            
            // ถ้าไม่มีรูปคนขับ ให้สร้างรูปตามชื่อ
            var driverName = props.driver_name || 'ไม่ระบุ';
            var driverImg = props.driver_pic ? (basePath + props.driver_pic) : ('https://ui-avatars.com/api/?name=' + encodeURIComponent(driverName) + '&background=eff6ff&color=1d4ed8');

            Swal.fire({
                html: `
                    <div class="text-left mt-2">
                        
                        <!-- ✨ จัดเรียงใหม่: ชื่อรถ (ซ้าย) -> รูปภาพ (กลาง) -> ทะเบียน (ขวา) -->
                        <!-- 💡 ใช้ inline style ช่วยบังคับพื้นหลังและเส้นขอบป้องกันคลาส Tailwind ไม่ทำงาน -->
                        <div class="flex flex-row items-center justify-between p-4 rounded-xl mb-4 gap-2" style="background-color: #eff6ff; border: 1px solid #dbeafe;">
                            
                            <!-- 1. ซ้าย: ชื่อรถ -->
                            <div class="flex flex-col min-w-0 flex-1">
                                <span class="block text-xs font-bold text-blue-400 tracking-wider uppercase mb-0.5">รถยนต์ที่จอง</span>
                                <span class="font-bold text-blue-700 text-lg leading-tight truncate pr-2">${props.car_name}</span>
                            </div>

                            <!-- 2. กลาง: รูปซ้อนกัน -->
                            <!-- 💡 ใช้ inline style บังคับ width, height 52px ป้องกันรูปล้นทะลุจอ -->
                            <div class="flex -space-x-3 shrink-0 justify-center">
                                <!-- รูปรถยนต์ -->
                                <div onclick="zoomCalendarImage(event, '${carImg}', 'รถยนต์: ${props.car_name}')" 
                                     class="rounded-full border-[3px] border-white bg-white shadow-sm overflow-hidden z-10 relative cursor-pointer hover:z-30" 
                                     style="width: 52px; height: 52px; transition: transform 0.2s;"
                                     onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"
                                     title="คลิกเพื่อขยายรูปภาพ">
                                    <img src="${carImg}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name=Car'">
                                </div>
                                <!-- รูปคนขับรถ -->
                                <div onclick="zoomCalendarImage(event, '${driverImg}', 'คนขับรถ: ${driverName}')" 
                                     class="rounded-full border-[3px] border-white bg-white shadow-sm overflow-hidden z-20 relative cursor-pointer hover:z-30" 
                                     style="width: 52px; height: 52px; transition: transform 0.2s;"
                                     onmouseover="this.style.transform='scale(1.1)'" onmouseout="this.style.transform='scale(1)'"
                                     title="คลิกเพื่อขยายรูปคนขับ">
                                    <img src="${driverImg}" style="width: 100%; height: 100%; object-fit: cover;" onerror="this.src='https://ui-avatars.com/api/?name=${encodeURIComponent(driverName)}'">
                                    <!-- ไอคอนคนขับเล็กๆ -->
                                    <div class="absolute bottom-0 right-0 bg-white rounded-full shadow-sm z-30" style="padding: 2px;">
                                        <svg class="w-3 h-3 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4z"/></svg>
                                    </div>
                                </div>
                            </div>

                            <!-- 3. ขวา: ทะเบียนรถ -->
                            <!-- 💡 ใช้ flex-col และ items-center เพื่อจัดกึ่งกลางทะเบียน -->
                            <div class="shrink-0 flex flex-col items-center justify-center pl-3 ml-1" style="border-left: 1px solid rgba(191, 219, 254, 0.7);">
                                <span class="block text-xs font-bold text-gray-400 tracking-wider uppercase mb-1">ทะเบียน</span>
                                <span class="inline-block bg-white border border-blue-200 px-2.5 py-1 rounded text-sm font-mono font-bold text-gray-800 shadow-sm whitespace-nowrap">${props.license_plate}</span>
                            </div>
                            
                        </div>

                        <!-- ส่วนเนื้อหาที่เหลือเหมือนเดิม -->
                        <div class="flex items-start gap-3 bg-gray-50 p-3 rounded-lg mb-4 border border-gray-100">
                            <span class="text-xl">🕒</span>
                            <div class="text-[13px] text-gray-700">
                                <p><strong class="text-gray-900">เริ่ม:</strong> ${startTime}</p>
                                <p class="mt-1"><strong class="text-gray-900">ถึง:</strong> ${endTime}</p>
                            </div>
                        </div>

                        <div class="space-y-3 text-[14px] text-gray-700 px-1">
                            <p class="flex items-start gap-2">
                                <span class="mt-0.5 text-gray-400">👤</span> 
                                <span><strong class="font-bold text-gray-900 mr-1">ผู้ขอใช้รถ:</strong> ${props.user_name}</span>
                            </p>
                            <p class="flex items-start gap-2">
                                <span class="mt-0.5 text-gray-400">📍</span> 
                                <span><strong class="font-bold text-gray-900 mr-1">ปลายทาง:</strong> ${props.destination} <span class="text-xs text-blue-600 font-bold ml-1 bg-blue-50 px-2 py-0.5 rounded">(${props.passenger_count} ท่าน)</span></span>
                            </p>
                            
                            <p class="flex items-start gap-2">
                                <span class="mt-0.5 text-gray-400">👨‍✈️</span> 
                                <span><strong class="font-bold text-gray-900 mr-1">คนขับรถ:</strong> ${props.driver_name}</span>
                            </p>

                            <p class="flex items-start gap-2">
                                <span class="mt-0.5 text-gray-400">📝</span> 
                                <span><strong class="font-bold text-gray-900 mr-1">วัตถุประสงค์:</strong> ${props.purpose}</span>
                            </p>
                        </div>

                        <div class="border-t border-gray-100 pt-4 mt-5 flex items-center justify-between px-1">
                            <strong class="text-sm text-gray-500">สถานะคิวรถ:</strong>
                            ${statusHtml}
                        </div>
                    </div>
                `,
                showCloseButton: true,
                showConfirmButton: false,
                width: '400px',
                customClass: { popup: 'rounded-2xl' }
            });
        }
    });
    
    calendar.render();
});