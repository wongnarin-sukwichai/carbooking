document.addEventListener('DOMContentLoaded', function() {
    
    // ตรวจสอบว่าโหลด TomSelect มาแล้วหรือยัง
    if (typeof TomSelect === 'function') {

        // ==========================================
        // 1. จัดการ Select สำหรับ "ค้นหาผู้ขอใช้รถ" (สำหรับ Admin/Head)
        // ==========================================
        var userSelect = document.getElementById('user_search_select');
        if (userSelect) {
            new TomSelect(userSelect, {
                create: false,
                sortField: { field: "text", direction: "asc" },
                dropdownParent: 'body',
                // คลิกครั้งเดียวเปิด และพิมพ์หาได้เลยโดยไม่ต้องลบของเก่า
                onDropdownOpen: function() {
                    this.clearCurrentOption(); 
                }
            });
        }

// ==========================================
// 2. จัดการ Select สำหรับ "เลือกคนขับรถ" (โชว์รูปภาพ)
// ==========================================
var driverSelect = document.getElementById('driver_select');
if (driverSelect) {
    new TomSelect(driverSelect, {
        placeholder: "-- ค้นหาหรือเลือกคนขับรถ --",
        allowEmptyOption: true,
        render: {
            option: function(data, escape) {
                // ถ้าเป็นค่าว่าง (Placeholder) ไม่ต้องโชว์รูป
                if (!data.pic) {
                    return `<div class="px-3 py-2 text-gray-500">${escape(data.text)}</div>`;
                }
                return `<div class="flex items-center gap-3 px-3 py-2 cursor-pointer hover:bg-gray-50">
                            <div class="w-12 h-12 rounded-full overflow-hidden border border-gray-200 flex-shrink-0 bg-gray-100">
                                <img src="${data.pic}" class="w-full h-full object-cover">
                            </div>
                            <span class="font-bold text-gray-700 text-[15px]">${escape(data.text)}</span>
                        </div>`;
            },
            item: function(data, escape) {
                // ถ้ายังไม่ได้เลือก (แสดง Placeholder) หรือไม่มีรูป
                if (!data.pic || data.value === "") {
                    return `<div class="text-gray-400">${escape(data.text)}</div>`;
                }
                // ปรับให้รูปอยู่ซ้ายมือ กึ่งกลางพอดีชื่อ
                return `<div class="flex items-center gap-3 py-0.5">
                            <div class="w-8 h-8 rounded-full overflow-hidden border border-gray-200 flex-shrink-0 bg-gray-100">
                                <img src="${data.pic}" class="w-full h-full object-cover">
                            </div>
                            <span class="font-semibold text-gray-800 leading-none">${escape(data.text)}</span>
                        </div>`;
            }
        }
    });
}

    } // End if TomSelect check
});