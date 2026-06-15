// public/js/remark-manager.js

function showFullRemark(remark) {
    Swal.fire({
        title: '<span class="text-lg font-bold text-gray-800">หมายเหตุเพิ่มเติม</span>',
        html: `<div class="p-4 bg-gray-50 rounded-lg text-sm text-gray-700 text-left border border-gray-100 mt-2">${remark}</div>`,
        icon: 'info',
        confirmButtonText: 'ปิดหน้าต่าง',
        confirmButtonColor: '#10b981', // สีเขียวมรกต
        customClass: {
            popup: 'rounded-2xl',
            confirmButton: 'rounded-xl font-bold px-6 py-2.5'
        }
    });
}