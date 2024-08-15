<!-- Modal # Start -->
<div id="addKioskModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg modal" style="width: 95%;">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-xl font-bold">เพิ่มตู้</h2>
            <button id="closeModalBtn" class="text-gray-600 hover:text-gray-900 text-2xl">&times;</button>
        </div>
        <div class="p-4">
            <form id="addKioskForm">
                <div class="mb-4">
                    <label for="ownerName" class="block text-gray-700">ชื่อ นามสกุล (เจ้าของตู้)</label>
                    <input type="text" id="ownerName" class="mt-1 p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="ownerPhone" class="block text-gray-700">เบอร์โทรเจ้าของตู้</label>
                    <input type="text" id="ownerPhone" class="mt-1 p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="salesPhone" class="block text-gray-700">เบอร์โทรเซลล์</label>
                    <input type="text" id="salesPhone" class="mt-1 p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="adminPhone" class="block text-gray-700">เบอร์โทรแอดมิน</label>
                    <input type="text" id="adminPhone" class="mt-1 p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="kioskCode" class="block text-gray-700">รหัสตู้</label>
                    <input type="text" id="kioskCode" class="mt-1 p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="ownerAddress" class="block text-gray-700">สถานที่ (ตั้งตู้)</label>
                    <input type="text" id="ownerAddress" class="mt-1 p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="salesName" class="block text-gray-700">ชื่อ นามสกุล (เซลล์ผู้ขาย)</label>
                    <input type="text" id="salesName" class="mt-1 p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="kioskPrice" class="block text-gray-700">ราคาขายตู้ (บาท)</label>
                    <input type="number" id="kioskPrice" class="mt-1 p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="downPayment" class="block text-gray-700">จำนวนเงิน (ดาวน์)</label>
                    <input type="number" id="downPayment" class="mt-1 p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="zeroPercentMonths" class="block text-gray-700">เริ่มผ่อน 0% กี่เดือน</label>
                    <input type="number" id="zeroPercentMonths" class="mt-1 p-2 border rounded w-full" value="0" required>
                </div>
                <div class="mb-4">
                    <label for="interestPercent" class="block text-gray-700">เดือนต่อไป คิดดอกกี่ %</label>
                    <input type="number" id="interestPercent" class="mt-1 p-2 border rounded w-full" value="0" step="0.01" required>
                </div>
                <div class="mb-4">
                    <label class="block text-gray-700">วิธีชำระ:</label>
                    <div>
                        <label class="inline-flex items-center">
                            <input type="radio" id="fixedPayment" name="paymentMethod" value="fixed" class="form-radio" checked>
                            <span class="ml-2">แบบฟิก</span>
                        </label>
                        <label class="inline-flex items-center ml-6">
                            <input type="radio" id="percentagePayment" name="paymentMethod" value="percentage" class="form-radio">
                            <span class="ml-2">แบ่ง%</span>
                        </label>
                    </div>
                </div>
                <div class="mb-4">
                    <label for="installmentMonths" class="block text-gray-700">จำนวนเดือนที่ผ่อน (แบบฟิก)</label>
                    <input type="number" id="installmentMonths" class="mt-1 p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="monthlyPayment" class="block text-gray-700">ผ่อนเดือนละ (บาท)</label>
                    <input type="number" id="monthlyPayment" class="mt-1 p-2 border rounded w-full" readonly>
                </div>
                <div class="mb-4">
                    <label for="monthlyPercentagePayment" class="block text-gray-700">ผ่อนเดือนละ (%)</label>
                    <input type="number" id="monthlyPercentagePayment" class="mt-1 p-2 border rounded w-full disabled-input" disabled>
                </div>
            </form>
        </div>
        <div class="p-4 border-t">
            <button id="saveKioskBtn" class="bg-teal-400 text-white px-4 py-2 rounded hover:bg-teal-500">บันทึก</button>
            <button id="cancelBtn" class="ml-4 text-gray-700 hover:text-gray-900">ยกเลิก</button>
        </div>
    </div>
</div>
<!-- Modal # End -->

<!-- Edit Modal # Start -->
<div id="editKioskModal" class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 hidden">
    <div class="bg-white rounded-lg shadow-lg modal" style="width: 95%;">
        <div class="p-4 border-b flex justify-between items-center">
            <h2 class="text-xl font-bold">แก้ไขข้อมูลตู้</h2>
            <button id="closeEditModalBtn" class="text-gray-600 hover:text-gray-900 text-2xl">&times;</button>
        </div>
        <div class="p-4">
            <form id="editKioskForm">
                <div class="mb-4">
                    <label for="editKioskName" class="block text-gray-700">ชื่อเจ้าของตู้</label>
                    <input type="text" id="editKioskName" class="mt-1 p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="editOwnerPhone" class="block text-gray-700">เบอร์โทรเจ้าของตู้</label>
                    <input type="text" id="editOwnerPhone" class="mt-1 p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="editSalesPhone" class="block text-gray-700">เบอร์โทรเซลล์</label>
                    <input type="text" id="editSalesPhone" class="mt-1 p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="editAdminPhone" class="block text-gray-700">เบอร์โทรแอดมิน</label>
                    <input type="text" id="editAdminPhone" class="mt-1 p-2 border rounded w-full" required>
                </div>
                <div class="mb-4">
                    <label for="editOwnerAddress" class="block text-gray-700">สถานที่ (ตั้งตู้)</label>
                    <input type="text" id="editOwnerAddress" class="mt-1 p-2 border rounded w-full" required>
                </div>
                <input type="hidden" id="editKioskCode">
            </form>
        </div>
        <div class="p-4 border-t">
            <button id="saveEditKioskBtn" class="bg-teal-400 text-white px-4 py-2 rounded hover:bg-teal-500">บันทึก</button>
            <button id="cancelEditBtn" class="ml-4 text-gray-700 hover:text-gray-900">ยกเลิก</button>
        </div>
    </div>
</div>

<!-- Edit Modal # End -->