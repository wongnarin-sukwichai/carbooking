// ไฟล์: public/js/export-manager.js

function openExportModal(exportUrl, csrfToken, drivers = []) {
    
    // ดึงค่า car_id และ driver_id จาก URL มาเป็นค่าเริ่มต้น
    const urlParams = new URLSearchParams(window.location.search);
    const currentCarId = urlParams.get('car_id') || '';
    const currentDriverId = urlParams.get('driver_id') || '';

    // 👨‍✈️ สร้างรายการตัวเลือกคนขับรถ
    let driverOptions = '<option value="">-- พนักงานขับรถทุกคน --</option>';
    drivers.forEach(driver => {
        const selected = (driver.id == currentDriverId) ? 'selected' : '';
        driverOptions += `<option value="${driver.id}" ${selected}>${driver.first_name} ${driver.last_name}</option>`;
    });

    Swal.fire({
        title: '<div class="text-xl font-bold text-emerald-700">📥 ออกรายงานตามคนขับ</div>',
        html: `
            <form id="exportForm" action="${exportUrl}" method="POST" class="mt-4 text-left">
                <input type="hidden" name="_token" value="${csrfToken}">
                
                <input type="hidden" name="car_id" value="${currentCarId}">

                <div class="space-y-4">
                    
                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <label class="block text-sm font-bold text-gray-700 mb-2">👨‍✈️ เลือกพนักงานขับรถ:</label>
                        <select name="driver_id" class="w-full border border-gray-300 rounded-lg px-3 py-2.5 bg-white focus:ring-2 focus:ring-emerald-500 outline-none transition">
                            ${driverOptions}
                        </select>
                        <p class="text-[10px] text-gray-500 mt-2">* หากไม่ได้เลือกคนขับ ระบบจะดึงข้อมูลทุกคนตามช่วงวันที่ระบุ</p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 mb-1 uppercase">เริ่มวันที่</label>
                            <input type="text" id="start_date" name="start_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-1 focus:ring-emerald-500 text-sm outline-none cursor-pointer" placeholder="ว/ด/ป" readonly required>
                        </div>
                        <div>
                            <label class="block text-[11px] font-bold text-gray-500 mb-1 uppercase">ถึงวันที่</label>
                            <input type="text" id="end_date" name="end_date" class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-white focus:ring-1 focus:ring-emerald-500 text-sm outline-none cursor-pointer" placeholder="ว/ด/ป" readonly required>
                        </div>
                    </div>

                </div>
            </form>
        `,
        showCancelButton: true,
        confirmButtonText: 'ดาวน์โหลด Excel',
        cancelButtonText: 'ยกเลิก',
        confirmButtonColor: '#059669', 
        cancelButtonColor: '#64748b',
        customClass: { popup: 'rounded-2xl' },
        didOpen: () => {
            flatpickr("#start_date", { dateFormat: "Y-m-d", locale: "th", disableMobile: true });
            flatpickr("#end_date", { dateFormat: "Y-m-d", locale: "th", disableMobile: true });
        },
        preConfirm: () => {
            const start = document.getElementById('start_date').value;
            const end = document.getElementById('end_date').value;
            if (!start || !end) {
                Swal.showValidationMessage('กรุณาเลือกช่วงเวลาให้ครบถ้วน');
                return false;
            }
            document.getElementById('exportForm').submit();
        }
    });
}