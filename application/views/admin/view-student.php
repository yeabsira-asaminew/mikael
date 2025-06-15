<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon -->
    <link rel="icon" href="<?php echo base_url('assets/images/favicon.ico'); ?>" type="image/x-icon">
    <title>የተማሪ መረጃዎች | ሰንበት አቴንዳንስ ሲስተም</title>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/tabs.css'); ?>">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .charts-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 15px;
            justify-content: center;
            align-items: center;
        }

        canvas {
            max-width: 250px !important;
            max-height: 250px !important;
            margin: auto;
        }
    </style>

</head>

<body>
    <?php include APPPATH . 'views/admin/includes/sidebar.php'; ?>

    <section id="content">
        <?php include APPPATH . 'views/admin/includes/topbar.php'; ?>

        <main>
            <div class="head-title">
                <div class="left">
                    <h2><?php echo "የተማሪ " .  $student['fname'] . " " . $student['mname'] . " ዝርዝር መረጃዎች"; ?></h2>
                    <ul class="breadcrumb">
                        <li> <a href="<?php echo base_url('dashboard'); ?>">ዳሽቦርድ</a> </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li> <a>የተማሪዎች ዝርዝር</a> </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="<?= base_url('student/view/' . $student['id']) ?>">የተማሪ መረጃ</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="students-details-container">
                <div class="students-details-tabs-container">
                    <div class="students-details-tab active" data-target="personal"><i class='bx bx-user'></i> ግላዊ መረጃ
                    </div>
                    <div class="students-details-tab" data-target="academic"><i class='bx bx-book'></i> የትምህርት መረጃ</div>
                    <div class="students-details-tab" data-target="attendance"><i class='bx bx-chart'></i> አቴንዳንስ</div>

                    <?php if ($this->session->userdata('role') == 'superadmin'): ?>
                        <div class="students-details-tab" data-target="qr"><i class='bx bx-qr'></i> QR መለያ</div>
                    <?php endif ?>
                </div>




                <!-- Personal Information Tab -->
                <div class="students-details-content active" id="personal">

                    <div class="student-details-image-container">
                        <?php if (!empty($student)) : ?>
                            <img src="<?php echo base_url('uploads/photos/') . $student['photo']; ?>"
                                alt="<?php echo "Photo of " .  $student['fname']; ?>" class="student-image">
                            <h2><?php echo $student['fname'] . ' ' . $student['mname'] . ' ' . $student['lname']; ?></h2>
                            <h3>መታወቂያ ቁ.: <?php echo $student['student_id']; ?></h3>
                    </div>
                    <br>

                    <div class="details-grid">
                        <div class="detail-card"><i class='bx bx-female-sign'></i><strong> የእናት ስም:</strong>
                            <?php echo $student['mother_name']; ?></div>
                        <div class="detail-card"><i class='bx bx-user-circle'></i><strong> ጾታ:</strong>
                            <?php echo $student['sex_amharic']; ?></div>
                        <div class="detail-card"><i class='bx bx-calendar'></i><strong> የትውልድ ዘመን:</strong>
                            <?php echo $student['dob']; ?></div>
                        <div class="detail-card"><i class='bx bx-map'></i><strong> የትውልድ ቦታ:</strong>
                            <?php echo $student['pob']; ?></div>
                        <div class="detail-card"><i class='bx bx-calendar'></i><strong> እድሜ:</strong>
                            <?php echo $student['age']; ?></div>
                        <div class="detail-card"><i class='bx bx-church'></i><strong> የክርስትና ስም:</strong>
                            <?php echo !empty($student['christian_name']) ? $student['christian_name'] : '-'; ?></div>
                        <div class="detail-card"><i class='bx bx-user-voice'></i><strong> የክርስትና አባት:</strong>
                            <?php echo !empty($student['God_father']) ? $student['God_father'] : '-'; ?></div>
                        <div class="detail-card"><i class='bx bx-church'></i><strong> የንስሃ አባት ደብር:</strong>
                            <?php echo !empty($student['repentance_father_church']) ? $student['repentance_father_church'] : '-'; ?></div>
                        <div class="detail-card"><i class=' bx bx-mobile-alt'></i><strong> ስልክ ቁጥር:</strong>
                            <?php echo $student['phone1']; ?></div>
                        <div class="detail-card"><i class='bx bx-mobile-alt'></i><strong> ተጨማሪ ስልክ ቁጥር:</strong>
                            <?php echo !empty($student['phone2']) ? $student['phone2'] : '-'; ?></div>
                        <div class="detail-card"><i class='bx bx-map'></i><strong> አድራሻ:</strong>
                            <?php echo $student['address']; ?></div>
                        <div class="detail-card"><i class='bx bx-calendar'></i><strong> የምዝገባ ቀን:</strong>
                            <?php echo $student['registration_date']; ?></div>
                    </div>
                <?php else : ?>
                    <h5 style="color:red; text-align:center">የተማሪው መረጃ አልተገኘም!</h5>
                <?php endif; ?>
                </div>

                <!-- Academic Details Tab -->
                <div class="students-details-content" id="academic">
                    <div class="details-grid">
                        <div class="detail-card"><i class='bx bx-category'></i><strong> የእድሜ ክፍል:</strong>
                            <?php echo $student['age_category_name']; ?></div>
                        <div class="detail-card"><i class='bx bx-category'></i><strong> የሐዋርያዊ ክፍል:</strong>
                            <?php echo !empty($student['apostolic_name']) ? $student['apostolic_name'] : '-'; ?></div>
                        <div class="detail-card"><i class='bx bx-category'></i><strong> የስርአተ ት/ክፍል:</strong>
                            <?php echo !empty($student['curriculum_name']) ? $student['curriculum_name'] : '-'; ?></div>
                        <div class="detail-card"><i class='bx bx-category'></i><strong> የአገልግሎት ክፍል:</strong>
                            <?php echo !empty($student['department_name']) ? $student['department_name'] : '-'; ?></div>
                        <div class="detail-card"><i class='bx bx-category'></i><strong> የመዝሙር ክፍል:</strong>
                            <?php echo !empty($student['choir_name']) ? $student['choir_name'] : '-'; ?></div>
                        <div class="detail-card"><i class='bx bx-briefcase'></i><strong> ስራ/ትምህርት:</strong>
                            <?php echo $student['occupation']; ?></div>
                        <div class="detail-card"><i class='bx bx-building'></i><strong> የት/ደረጃ:</strong>
                            <?php echo $student['education_level']; ?></div>
                        <div class="detail-card"><i class='bx bx-building'></i><strong> የትምህርት ዘርፍ:</strong>
                            <?php echo !empty($student['academic_field']) ? $student['academic_field'] : '-'; ?></div>
                        <div class="detail-card"><i class='bx bx-building'></i><strong> የስራ ቦታ:</strong>
                            <?php echo !empty($student['workplace']) ? $student['workplace'] : '-'; ?></div>
                        <div class="detail-card"><i class='bx bx-time'></i><strong> መርሐግብር:</strong>
                            <?php if (!empty($student['schedule_datetime'])): ?>
                                <?php echo $student['schedule_datetime']; ?></div>
                    <?php else: ?>
                        <?php echo "የተማሪው የት/ክፍል መርሐግብር አልተፈጠረለትም!"; ?>
                    </div>
                <?php endif; ?>
                </div>
            </div>

            <!-- Attendance Tab with 6 Charts -->
            <div class="students-details-content" id="attendance">
                <div class="charts-container">
                    <canvas id="barChart"></canvas>
                    <canvas id="scheduleChart"></canvas>
                </div>
            </div>

            <!-- QR Code Tab -->
            <div class="students-details-content" id="qr">
                <h2>QR Code</h2>

                <!-- check whether the image is available in the folder or not -->
                <?php
                $qr_path = FCPATH . 'uploads/qr_codes/' . $student['qr_code'];
                $qr_url = base_url('uploads/qr_codes/') . $student['qr_code'];
                ?>
                <!-- if file exists display it if not display generate qr code button -->
                <?php if (!empty($student['qr_code']) && file_exists($qr_path)) : ?>
                    <img id="qr-image" src="<?php echo $qr_url; ?>" alt="QR Code">
                    <a href="<?php echo $qr_url; ?>" download class="btn"><i class='bx bx-download'></i> QR አውርድ</a>
                    <button class="btn" onclick="printQRCode()"><i class='bx bx-printer'></i> QR አትም</button>
                <?php else : ?>
                    <h5 style="color:red; text-align:center">የተማሪው QR Code አልተገኘም ወይም ተሰርዟል!</h5>
                    <a href="<?= base_url('student/generate_new_qr/' . $student['id']) ?>" class="btn btn-primary btn-sm">
                        <i class='bx bx-qr'></i> QR ፍጠር
                    </a>
                <?php endif; ?>

            </div>
            </div>
        </main>
    </section>
    <!--
    <script>
    document.addEventListener('DOMContentLoaded', () => {
        const studentTabs = document.querySelectorAll('.students-details-tab');
        const studentContents = document.querySelectorAll('.students-details-content');
        const studentContainer = document.querySelector('.students-details-container');

        // Initially hide all content sections except the first one
        studentContents.forEach(content => content.style.display = 'none');
        const firstContent = document.getElementById(studentTabs[0].dataset.target);
        if (firstContent) firstContent.style.display = 'block';

        studentTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active class from all tabs
                document.querySelector('.students-details-tab.active')?.classList.remove(
                    'active');
                tab.classList.add('active');

                // Hide all content sections
                studentContents.forEach(content => content.style.display = 'none');

                // Show the targeted content section
                const target = document.getElementById(tab.dataset.target);
                if (target) {
                    target.style.display = 'block';

                    // Adjust container height smoothly
                    studentContainer.style.minHeight = target.offsetHeight + 'px';
                }
            });
        });

        // Set initial active tab
        studentTabs[0].classList.add('active');
    });
    </script>
                    -->
</body>

<script src="<?php echo base_url('assets/js/script.js'); ?>"></script>

<script>
    function printQRCode() {
        var qrImage = document.getElementById('qr-image');
        var printWindow = window.open('', '', 'width=600,height=400');

        // Add the QR image to the print window
        printWindow.document.write(
            '<html><head><title>ደብረ ታቦር ቅዱስ እግዚአብሄር አብ ጽርሐ ጽዮን ሰ/ት/ቤት ተማሪ QR Code</title></head><body>');
        printWindow.document.write('<img src="' + qrImage.src + '" alt="QR Code" style="width:100%; max-width:400px;" />');
        printWindow.document.write('</body></html>');

        // Trigger the print dialog
        printWindow.document.close();
        printWindow.print();
    }
</script>
<script>
    // Tab Switching Logic
    document.addEventListener('DOMContentLoaded', () => {
        const studentTabs = document.querySelectorAll('.students-details-tab');
        const studentContents = document.querySelectorAll('.students-details-content');

        studentContents.forEach(content => content.style.display = 'none');
        document.getElementById(studentTabs[0].dataset.target).style.display = 'block';

        studentTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                document.querySelector('.students-details-tab.active')?.classList.remove('active');
                tab.classList.add('active');

                studentContents.forEach(content => content.style.display = 'none');
                document.getElementById(tab.dataset.target).style.display = 'block';
            });
        });

        studentTabs[0].classList.add('active');
    });

    function printQRCode() {
        const qrWindow = window.open('', '_blank');
        qrWindow.document.write('<html><head><title>Print QR Code</title></head><body>');
        qrWindow.document.write('<img src="' + document.getElementById('qr-image').src +
            '" style="width:300px;height:300px;">');
        qrWindow.document.write('</body></html>');
        qrWindow.document.close();
        qrWindow.print();
    }
</script>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const ctx1 = document.getElementById('barChart').getContext('2d');
        const ctx2 = document.getElementById('scheduleChart').getContext('2d');

        const attendanceData = <?= json_encode($attendance) ?>;
        const scheduleData = <?= json_encode($schedule) ?>;

        const labels = Object.keys(scheduleData);
        const presentData = labels.map(day => scheduleData[day].present);
        const absentData = labels.map(day => scheduleData[day].absent);

        new Chart(ctx1, {
            type: 'bar',
            data: {
                labels: ['ጠቅላላ', 'የተገኘበት', 'ቀሪ'],
                datasets: [{
                    label: 'ጠቅላላ አቴንዳንስ በቁጥር',
                    data: [attendanceData.total, attendanceData.present, attendanceData.absent],
                    backgroundColor: ['rgba(54, 162, 235, 0.6)', 'rgba(75, 192, 192, 0.6)',
                        'rgba(255, 99, 132, 0.6)'
                    ],
                    borderColor: ['rgba(54, 162, 235, 1)', 'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)'
                    ],
                    borderWidth: 1,
                    borderRadius: 5
                }]
            },
            options: {
                responsive: true
            }
        });

        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: labels,
                datasets: [{
                        label: 'የተገኘበት',
                        data: presentData,
                        backgroundColor: 'rgba(75, 192, 192, 0.6)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1,
                        borderRadius: 5
                    },
                    {
                        label: 'ቀሪ',
                        data: absentData,
                        backgroundColor: 'rgba(255, 99, 132, 0.6)',
                        borderColor: 'rgba(255, 99, 132, 1)',
                        borderWidth: 1,
                        borderRadius: 5
                    }
                ]
            },
            options: {
                responsive: true
            }
        });
    });
</script>

</html>