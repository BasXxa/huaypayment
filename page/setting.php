<?php

session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: ../index.php');
    exit();
}
?>

<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Title # Start -->
    <?php include '../config/config.php'; ?>
    <title><?php echo $title; ?></title>
    <!-- Title # End -->

    <!-- Link # Start -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../assets/css/main.style.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <!-- Link # End -->
</head>
<!-- Body # Start -->
<div class="container mx-auto px-1">
    <!-- Header Btn # Start -->
    <div class="flex justify-between mt-5">
        <button class="text-white bg-emerald-400 hover:bg-emerald-500 px-4 py-2 rounded-full" onclick="window.history.back()">ย้อนกลับ</button>
        <button class="text-white bg-teal-400 hover:bg-teal-500 px-4 py-2 rounded-full" id="addKioskBtn">เพิ่มตู้</button>
    </div>
    <!-- Header Btn # End -->
    <div class="w-100 h-50 flex flex-col border border-cyan-900 p-1 rounded-xl bg-cyan-200 p-3 mt-5">
        <span class="text-xl underline">ข้อมูลผู้ใช้</span>
        <div class="account-group mt-2 flex gap-2">
            <div class="icon">
                <i class="fa-solid fa-user"></i>
            </div>
            <span>ชื่อผู้ใช้ : <span class="text-blue-800"> <?php echo $_SESSION['user_firstname'] . ' ' . $_SESSION['user_lastname']; ?></span></span>
        </div>
        <div class="account-group flex gap-2">
            <div class="icon">
                <i class="fa-solid fa-phone"></i>
            </div>
            <span>เบอร์โทรเจ้าของตู้ : <?php echo $_SESSION['user_phone']; ?></span>
        </div>
        <div class="account-group flex gap-2">
            <div class="icon">
                <i class="fa-solid fa-phone"></i>
            </div>
            <?php include '../api/get_info.php'; ?>
            <span>เบอร์โทรเซลล์ : <?php echo $_SESSION['sales_phone']; ?></span>
        </div>
        <div class="account-group flex gap-2">
            <div class="icon">
                <i class="fa-solid fa-phone"></i>
            </div>
            <span>เบอร์โทรแอดมิน : <?php echo $_SESSION['admin_phone']; ?></span>
        </div>
        <div class="account-group flex gap-2">
            <div class="icon">
                <i class="fa-solid fa-location-dot"></i>
            </div>
            <span>ที่อยู่ : บ้านเลขที่.<?php echo $_SESSION['user_address']; ?> ต.<?php echo $_SESSION['user_subdistrict']; ?> อ.<?php echo $_SESSION['user_district']; ?> จ.<?php echo $_SESSION['user_province']; ?></span>
        </div>
        <div class="account-group flex gap-2">
            <div class="icon">
                <i class="fa-solid fa-cube"></i>
            </div>
            <?php include '../api/get_kiosk.php'; ?>
            <span>จำนวนตู้ : <?php echo count($kiosks); ?></span>
        </div>
    </div>

    <!-- Kiosk # Start -->
    <div class="kiosk-index mt-5">
        <?php if (empty($kiosks)) : ?>
            <div class="text-red-500 flex items-center">ไม่มีตู้</div>
        <?php else : ?>
            <?php foreach ($kiosks as $index => $kiosk) : ?>
                <?php
                $kiosk_id = $kiosk['id'];
                $remaining_amount = 0;
                $installments_paid = 0;

                $sql = "SELECT remaining_amount, status, kiosk_number, installment_no, month_date AS month_year FROM installments WHERE kiosk_id = ?";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$kiosk_id]);
                $installments = $stmt->fetchAll(PDO::FETCH_ASSOC);

                foreach ($installments as $installment) {
                    if ($installment['status'] == 'paid') {
                        $installments_paid++;
                    } else {
                        $remaining_amount += $installment['remaining_amount'];
                    }
                }

                $months_remaining = $kiosk['installment_months'] - $installments_paid;

                $kiosk_border_class = $months_remaining == 0 ? 'border-green-500 bg-green-100' : 'border-slate-900 bg-rose-200';
                ?>
                <div class="kiosk-container <?php echo $kiosk_border_class; ?> p-1 w-full border rounded-lg mb-4">
                    <div class="kiosk-header flex items-center gap-3" onclick="showPayment(<?php echo $index; ?>)">
                        <!-- Kiosk Id # Start -->
                        <div class="kiosk-id flex items-center justify-center border rounded-lg bg-red-400" style="width: 45px; height: 45px;">
                            <span class="text-xl"><?php echo $index + 1; ?></span>
                        </div>
                        <!-- Kiosk Id # End -->
                        <!-- Kiosk Info # Start -->
                        <div class="kiosk-info flex items-center gap-3">
                            <div class="flex flex-col">
                                <!-- ข้อมูลตู้และเจ้าของ -->
                                <span>ชื่อเจ้าของตู้: <span class="text-red-600 text-md"><?php echo htmlspecialchars($kiosk['owner_name']); ?></span></span>
                                <span>รหัสตู้: <span class="text-red-600 text-md"><?php echo htmlspecialchars($kiosk['kiosk_code']); ?></span></span>
                                <span>ราคาตู้: <span class="text-red-600 text-md"><?php echo htmlspecialchars($kiosk['kiosk_price']); ?></span> บาท</span>
                                <span>เงินดาวน์: <span class="text-red-600 text-md"><?php echo htmlspecialchars($kiosk['down_payment']); ?></span> บาท</span>
                                <span>ผ่อนเดือนละ: <span class="text-red-600 text-md"><?php echo htmlspecialchars($kiosk['monthly_fixed_payment']) ?: 'N/A'; ?></span> บาท</span>
                                <span>ผ่อนทั้งหมด: <span class="text-red-600 text-md"><?php echo htmlspecialchars($kiosk['installment_months']); ?></span> เดือน</span>
                                <span>สถานที่: <span class="text-red-600 text-md"><?php echo htmlspecialchars($kiosk['owner_address']); ?></span></span>
                                <span>เบอร์โทรเจ้าของตู้: <span class="text-red-600 text-md"><?php echo $kiosk['owner_phone']; ?></span></span>
                                <span>เบอร์โทรเซลล์: <span class="text-red-600 text-md"><?php echo htmlspecialchars($kiosk['sales_phone']); ?></span></span>
                                <span>เบอร์โทรแอดมิน: <span class="text-red-600 text-md"><?php echo htmlspecialchars($kiosk['admin_phone']); ?></span></span>
                                <span>ยอดเงินผ่อนคงเหลือ: <span class="text-red-600 text-md"><?php echo $remaining_amount; ?></span> บาท</span>
                                <span>เดือนที่ผ่อนคงเหลือ: <span class="text-red-600 text-md"><?php echo $months_remaining; ?></span> เดือน</span>
                                <span>ผ่อน 0% จำนวน: <span class="text-red-600 text-md"><?php echo htmlspecialchars($kiosk['installment_zero_percent_months']); ?></span> เดือน</span>
                                <span>คิดดอก: <span class="text-red-600 text-md"><?php echo htmlspecialchars($kiosk['installment_interest_percent']); ?></span> %</span>
                            </div>
                        </div>
                        <!-- Kiosk Info # End -->
                    </div>
                    <!-- Kiosk Show Month # Start -->
                    <div class="flex justify-end gap-2">
                        <?php if (!empty($installments)) : ?>
                            <button class="bg-green-500 text-white px-4 py-2 rounded-full"
                                onclick="window.location.href='sales.php?kiosk_code=<?php echo htmlspecialchars($installments[0]['kiosk_number']); ?>&installment_no=<?php echo htmlspecialchars($installments[0]['installment_no']); ?>&month_year=<?php echo urlencode($installments[0]['month_year']); ?>'">
                                รายได้เซลล์
                            </button>
                        <?php else : ?>
                            <button class="bg-yellow-500 text-white px-4 py-2 rounded-full" disabled>
                                รายได้เซลล์ (ไม่มีข้อมูล)
                            </button>
                        <?php endif; ?>

                        <button class="bg-yellow-500 text-white px-4 py-2 rounded-full" onclick="openEditModal('<?php echo $kiosk['kiosk_code']; ?>')">แก้ไข</button>
                        <button class="bg-red-500 text-white px-4 py-2 rounded-full" onclick="removeKiosk('<?php echo $kiosk['kiosk_code']; ?>')">ลบตู้</button>
                    </div>

                    <div class="kiosk-showmonth fade mt-5">
                        <?php include '../api/kiosk_payment.php'; ?>

                        <?php
                        $current_kiosk_code = $kiosk['kiosk_code'];
                        $current_installments = isset($groupedInstallments[$current_kiosk_code]) ? $groupedInstallments[$current_kiosk_code] : [];
                        ?>

                        <?php if (!empty($current_installments)) : ?>
                            <table class="min-w-full rounded-lg bg-white">
                                <thead>
                                    <tr>
                                        <th class="px-4 py-2">งวดที่</th>
                                        <th class="px-4 py-2">วันเดือนปี</th>
                                        <th class="px-4 py-2">สถานะ</th>
                                        <th class="px-4 py-2">ยอดผ่อน</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($current_installments as $installment) : ?>
                                        <?php
                                        switch ($installment['status']) {
                                            case 'paid':
                                                $status_icon = 'fas fa-check status-paid';
                                                $status_text = 'ชำระแล้ว';
                                                $status_class = 'text-green-500';
                                                break;
                                            case 'overdue':
                                                $status_icon = 'fas fa-exclamation-triangle status-overdue';
                                                $status_text = 'เกินกำหนดชำระ';
                                                $status_class = 'text-red-500';
                                                break;
                                            case 'pending':
                                            default:
                                                $status_icon = 'fas fa-clock status-pending';
                                                $status_text = 'รอชำระ';
                                                $status_class = 'text-red-500';
                                                break;
                                        }
                                        ?>
                                        <tr>
                                            <td class="border px-4 py-2"><?php echo htmlspecialchars($installment['installment_no']); ?></td>
                                            <td class="border px-4 py-2"><?php echo htmlspecialchars($installment['month_year']); ?></td>
                                            <td class="border px-4 py-2 <?php echo $status_class; ?>">
                                                <i class="<?php echo $status_icon; ?>"></i>
                                                <?php echo htmlspecialchars($status_text); ?>
                                            </td>
                                            <td class="border px-4 py-2"><?php echo htmlspecialchars($installment['remaining_amount']); ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <p>ไม่มีข้อมูลการผ่อนชำระ</p>
                        <?php endif; ?>
                    </div>
                    <!-- Kiosk Show Month # End -->
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>

    <!-- Kiosk # End -->

</div>
<!-- Body # End -->

<!-- Modal -->
<?php include '../components/modal.php'; ?>
<!-- Modal -->

<!-- Script # Start -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function removeKiosk(kioskCode) {
        Swal.fire({
            title: 'คุณแน่ใจหรือไม่?',
            text: "คุณไม่สามารถย้อนกลับการลบนี้ได้!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'ใช่, ลบเลย!',
            cancelButtonText: 'ยกเลิก'
        }).then((result) => {
            if (result.isConfirmed) {
                fetch('../api/delete_kiosk.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            kiosk_code: kioskCode
                        })
                    })
                    .then(response => response.json())
                    .then(result => {
                        if (result.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'ลบตู้สำเร็จ',
                                timer: 2000,
                                timerProgressBar: true,
                                showConfirmButton: false
                            }).then(() => {
                                window.location.reload();
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'เกิดข้อผิดพลาด',
                                text: result.message
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        Swal.fire({
                            icon: 'error',
                            title: 'เกิดข้อผิดพลาด',
                            text: 'ไม่สามารถลบตู้ได้ กรุณาลองใหม่'
                        });
                    });
            }
        });
    }

    function openEditModal(kioskCode) {
        fetch('../api/get_kiosk_info.php?kiosk_code=' + kioskCode)
            .then(response => response.json())
            .then(data => {
                document.getElementById('editKioskName').value = data.owner_name;
                document.getElementById('editOwnerPhone').value = data.owner_phone;
                document.getElementById('editSalesPhone').value = data.sales_phone;
                document.getElementById('editAdminPhone').value = data.admin_phone;
                document.getElementById('editOwnerAddress').value = data.owner_address;
                document.getElementById('editKioskCode').value = data.kiosk_code;

                document.getElementById('editKioskModal').classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error:', error);
            });
    }

    document.getElementById('closeEditModalBtn').addEventListener('click', function() {
        document.getElementById('editKioskModal').classList.add('hidden');
    });

    document.getElementById('cancelEditBtn').addEventListener('click', function() {
        document.getElementById('editKioskModal').classList.add('hidden');
    });

    document.getElementById('saveEditKioskBtn').addEventListener('click', function() {
        const kioskName = document.getElementById('editKioskName').value;
        const ownerPhone = document.getElementById('editOwnerPhone').value;
        const salesPhone = document.getElementById('editSalesPhone').value;
        const adminPhone = document.getElementById('editAdminPhone').value;
        const ownerAddress = document.getElementById('editOwnerAddress').value;
        const kioskCode = document.getElementById('editKioskCode').value;

        fetch('../api/update_kiosk.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    kioskName,
                    ownerPhone,
                    salesPhone,
                    adminPhone,
                    ownerAddress,
                    kioskCode
                })
            })
            .then(response => response.json())
            .then(result => {
                if (result.status === 'success') {
                    Swal.fire({
                        icon: 'success',
                        title: 'อัปเดตสำเร็จ',
                        timer: 2000,
                        timerProgressBar: true,
                        showConfirmButton: false,
                        text: result.message
                    }).then(() => {
                        document.getElementById('editKioskModal').classList.add('hidden');
                        window.location.reload();
                    });
                } else {
                    Swal.fire('Error', result.message, 'error');
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
    });

    function calculateMonthlyPayment() {
        const kioskPriceElement = document.getElementById('kioskPrice');
        const downPaymentElement = document.getElementById('downPayment');
        const installmentMonthsElement = document.getElementById('installmentMonths');
        const monthlyPaymentElement = document.getElementById('monthlyPayment');

        if (kioskPriceElement && downPaymentElement && installmentMonthsElement && monthlyPaymentElement) {
            const kioskPrice = parseFloat(kioskPriceElement.value);
            const downPayment = parseFloat(downPaymentElement.value);
            const installmentMonths = parseFloat(installmentMonthsElement.value);

            if (!isNaN(kioskPrice) && !isNaN(downPayment) && !isNaN(installmentMonths) && installmentMonths > 0) {
                const monthlyPayment = (kioskPrice - downPayment) / installmentMonths;
                monthlyPaymentElement.value = monthlyPayment.toFixed(2);
            } else {
                monthlyPaymentElement.value = '';
            }
        }
    }

    document.getElementById('kioskPrice').addEventListener('input', calculateMonthlyPayment);
    document.getElementById('downPayment').addEventListener('input', calculateMonthlyPayment);
    document.getElementById('installmentMonths').addEventListener('input', calculateMonthlyPayment);

    function showPayment(index) {
        const showMonthDivs = document.querySelectorAll('.kiosk-showmonth');
        const selectedDiv = showMonthDivs[index];
        const isVisible = selectedDiv.classList.contains('show');

        showMonthDivs.forEach(div => {
            div.classList.remove('show');
            div.style.display = 'none';
        });

        if (!isVisible) {
            selectedDiv.style.display = 'block';
            selectedDiv.classList.add('show');
        }
    }

    document.getElementById('addKioskBtn').addEventListener('click', function() {
        document.getElementById('addKioskModal').classList.remove('hidden');
    });

    document.getElementById('closeModalBtn').addEventListener('click', function() {
        document.getElementById('addKioskModal').classList.add('hidden');
    });

    document.getElementById('cancelBtn').addEventListener('click', function() {
        document.getElementById('addKioskModal').classList.add('hidden');
    });

    document.getElementById('fixedPayment').addEventListener('change', function() {
        const monthlyPaymentElement = document.getElementById('monthlyPayment');
        const monthlyPercentagePaymentElement = document.getElementById('monthlyPercentagePayment');

        if (monthlyPaymentElement) {
            monthlyPaymentElement.classList.remove('disabled-input');
            monthlyPaymentElement.removeAttribute('disabled');
        }

        if (monthlyPercentagePaymentElement) {
            monthlyPercentagePaymentElement.classList.add('disabled-input');
            monthlyPercentagePaymentElement.setAttribute('disabled', 'true');
        }
    });

    document.getElementById('percentagePayment').addEventListener('change', function() {
        const monthlyPaymentElement = document.getElementById('monthlyPayment');
        const monthlyPercentagePaymentElement = document.getElementById('monthlyPercentagePayment');

        if (monthlyPaymentElement) {
            monthlyPaymentElement.classList.add('disabled-input');
            monthlyPaymentElement.setAttribute('disabled', 'true');
        }

        if (monthlyPercentagePaymentElement) {
            monthlyPercentagePaymentElement.classList.remove('disabled-input');
            monthlyPercentagePaymentElement.removeAttribute('disabled');
        }
    });



    document.getElementById('saveKioskBtn').addEventListener('click', async function() {
        const ownerName = document.getElementById('ownerName').value;
        const ownerPhone = document.getElementById('ownerPhone').value;
        const salesPhone = document.getElementById('salesPhone').value;
        const adminPhone = document.getElementById('adminPhone').value;
        const kioskCode = document.getElementById('kioskCode').value;
        const ownerAddress = document.getElementById('ownerAddress').value;
        const salesName = document.getElementById('salesName').value;
        const kioskPrice = document.getElementById('kioskPrice').value;
        const downPayment = document.getElementById('downPayment').value;
        const paymentMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
        const monthlyFixedPayment = document.getElementById('monthlyPayment').value;
        const monthlyPercentagePayment = paymentMethod === 'percentage' ? document.getElementById('monthlyPercentagePayment').value : null;
        const installmentMonths = document.getElementById('installmentMonths').value;
        const zeroPercentMonths = document.getElementById('zeroPercentMonths').value;
        const interestPercent = document.getElementById('interestPercent').value;

        if (!ownerName || !ownerPhone || !salesPhone || !adminPhone || !kioskCode || !ownerAddress || !salesName || !kioskPrice || !installmentMonths || !downPayment || !zeroPercentMonths || !interestPercent) {
            Swal.fire('กรุณากรอกข้อมูลให้ครบถ้วน');
            return;
        }

        const response = await fetch('../api/add_kiosk.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                ownerPhone,
                ownerName,
                salesPhone,
                adminPhone,
                kioskCode,
                ownerAddress,
                salesName,
                kioskPrice,
                paymentMethod,
                downPayment,
                monthlyFixedPayment,
                monthlyPercentagePayment,
                installmentMonths,
                zeroPercentMonths,
                interestPercent
            })
        });

        const result = await response.json();

        if (result.status === 'success') {
            Swal.fire({
                icon: 'success',
                title: 'สำเร็จ',
                timer: 2000,
                timerProgressBar: true,
                showConfirmButton: false,
                text: result.message
            }).then(() => {
                document.getElementById('addKioskModal').classList.add('hidden');
                document.body.classList.remove('overflow-hidden');
                window.location.reload();
            });
        } else {
            Swal.fire('Error', result.message, 'error');
        }
    });
</script>
<!-- Script # End -->

<body>

</body>

</html>