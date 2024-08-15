<?php
require_once '../config/config.php';

$sql = "SELECT k.sales_name, i.installment_no, i.kiosk_number, i.month_date, ps.slip_path
        FROM kiosks k
        INNER JOIN installments i ON k.id = i.kiosk_id
        LEFT JOIN payment_slips ps ON i.kiosk_number = ps.kiosk_number AND i.installment_no = ps.installment_no";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$sales_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="th">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title #Start -->
    <?php include '../config/config.php'; ?>
    <title><?php echo $title; ?></title>
    <!-- Title #End -->

    <!-- Link #Start -->
    <link rel="stylesheet" href="../assets/css/main.style.css">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Link #End -->
</head>

<body class="bg-gray-100">
    <div class="container mx-auto mt-5">
        <!-- Header Btn # Start -->
        <div class="flex justify-between mt-5">
            <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700" onclick="window.history.back()">ย้อนกลับ</button>
        </div>
        <!-- Header Btn # End -->
        <h1 class="text-3xl font-bold text-center mb-6 text-blue-600">รายได้</h1>
        <div class="overflow-x-auto">
            <table class="min-w-full bg-white rounded-lg shadow-md">
                <thead class="bg-blue-500 text-white">
                    <tr>
                        <th class="px-4 py-3 border-b">ชื่อพนักงานขาย</th>
                        <th class="px-4 py-3 border-b">งวดที่</th>
                        <th class="px-4 py-3 border-b">รหัสตู้</th>
                        <th class="px-4 py-3 border-b">เดือนที่</th>
                        <th class="px-4 py-3 border-b">การดำเนินการ</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php foreach ($sales_data as $row) : ?>
                        <tr class="hover:bg-gray-100">
                            <td class="border px-4 py-3"><?php echo htmlspecialchars($row['sales_name']); ?></td>
                            <td class="border px-4 py-3 text-center"><?php echo htmlspecialchars($row['installment_no']); ?></td>
                            <td class="border px-4 py-3 text-center"><?php echo htmlspecialchars($row['kiosk_number']); ?></td>
                            <td class="border px-4 py-3 text-center"><?php echo htmlspecialchars($row['month_date']); ?></td>
                            <td class="border px-4 py-3 text-center">
                                <?php if (empty($row['slip_path'])) : ?>
                                    <button class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-700"
                                        onclick="openUploadSlipModal('<?php echo $row['kiosk_number']; ?>', '<?php echo $row['month_date']; ?>', '<?php echo $row['installment_no']; ?>')">
                                        แนบสลิป
                                    </button>
                                <?php else : ?>
                                    <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-700"
                                        onclick="viewSlip('<?php echo '../image/sales/' . htmlspecialchars($row['slip_path']); ?>')">
                                        ดูสลิป
                                    </button>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function openUploadSlipModal(kiosk_number, month_date, installment_no) {
            Swal.fire({
                title: 'อัพโหลดสลิปการชำระเงิน',
                html: `
                    <input type="file" id="slipFile" class="swal2-file" accept="image/*" onchange="previewSlipImage(this)">
                    <img id="slipPreview" src="" alt="ตัวอย่างสลิป" style="display: none; margin-top: 10px; max-width: 100%; border-radius: 5px;">
                `,
                showCancelButton: true,
                confirmButtonText: 'อัพโหลด',
                preConfirm: () => {
                    const file = Swal.getPopup().querySelector('#slipFile').files[0];
                    if (!file) {
                        Swal.showValidationMessage('กรุณาเลือกไฟล์');
                    }
                    return file;
                }
            }).then((result) => {
                if (result.isConfirmed) {
                    const formData = new FormData();
                    formData.append('slip', result.value);
                    formData.append('kiosk_number', kiosk_number);
                    formData.append('month_date', month_date);
                    formData.append('installment_no', installment_no);

                    fetch('../api/upload_payment_sales.php', {
                            method: 'POST',
                            body: formData
                        }).then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    title: 'สำเร็จ!',
                                    text: 'อัพโหลดสลิปการชำระเงินสำเร็จ',
                                    icon: 'success',
                                    timer: 5000,
                                    timerProgressBar: true,
                                    willClose: () => {
                                        location.reload();
                                    }
                                });
                            } else {
                                Swal.fire('เกิดข้อผิดพลาด!', 'เกิดข้อผิดพลาดในการอัพโหลดสลิป.', 'error');
                            }
                        })
                        .catch(() => {
                            Swal.fire('เกิดข้อผิดพลาด!', 'เกิดข้อผิดพลาดในการอัพโหลดสลิป.', 'error');
                        });
                }
            });
        }

        function previewSlipImage(input) {
            const file = input.files[0];
            const reader = new FileReader();

            reader.onload = function(e) {
                const img = document.getElementById('slipPreview');
                img.src = e.target.result;
                img.style.display = 'block';
            }

            if (file) {
                reader.readAsDataURL(file);
            } else {
                const img = document.getElementById('slipPreview');
                img.style.display = 'none';
            }
        }

        function viewSlip(slipUrl) {
            Swal.fire({
                imageUrl: slipUrl,
                imageAlt: 'ภาพสลิปการชำระเงิน',
                showCloseButton: true,
                showConfirmButton: false
            });
        }
    </script>
</body>

</html>