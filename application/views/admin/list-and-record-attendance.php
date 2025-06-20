<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon -->
    <link rel="icon" href="<?php echo base_url('assets/images/favicon.ico'); ?>" type="image/x-icon">
    <title>የተማሪዎች አቴንዳንስ መመዝገቢያ | ሰንበት አቴንዳንስ ሲስተም</title>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/icon.css'); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo base_url('assets/icon/simple-line-icons/css/simple-line-icons.css') ?>">
    <style>
        /* Filter Dropdown Styles - Improved to match your theme */
        .filter-container {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
            margin-bottom: 20px;
            background: rgb(255, 255, 255);
            padding: 15px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.05);
        }

        .filter-dropdown {
            position: relative;
            min-width: 180px;
            flex: 1;
            border: 2px white;
            border-radius: 8px;
            padding: 2px;
            background: #3C91E6;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            /* Subtle shadow for depth */
        }

        .filter-btn {
            width: 100%;
            padding: 10px 15px;
            background: var(--primary-color);
            color: white;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            text-align: left;
            font-weight: 500;
            font-size: 14px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            transition: all 0.3s ease;
            box-shadow: none;
        }

        /* Rest of your existing CSS remains the same */
        .filter-btn:hover {
            background: var(--primary-hover);
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }

        .filter-btn::after {
            content: '▼';
            font-size: 10px;
            margin-left: 8px;
        }

        .filter-content {
            border: 2px solid #3C91E6;
            border-top: none;
            border-radius: 0 0 6px 6px;
            display: none;
            position: absolute;
            background: white;
            width: 100%;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
            z-index: 100;
            max-height: 300px;
            overflow-y: auto;
            border-radius: 6px;
            margin-top: 5px;
        }

        .filter-content a {
            display: block;
            padding: 10px 15px;
            color: #444;
            text-decoration: none;
            font-size: 13px;
            transition: all 0.2s;
            border-bottom: 1px solid #f0f0f0;
        }

        .filter-content a:hover {
            background: #f5f5f5;
            color: var(--primary-color);
        }

        .filter-content a:first-child {
            border-radius: 6px 6px 0 0;
        }

        .filter-content a:last-child {
            border-bottom: none;
            border-radius: 0 0 6px 6px;
        }

        .show {
            display: block;
            animation: fadeIn 0.3s;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-5px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Attendance Form Styles */
        .attendance-form {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .date-input {
            padding: 8px 10px;
            width: 110px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 13px;
            transition: all 0.3s;
        }

        .date-input:focus {
            border-color: var(--primary-color);
            outline: none;
            box-shadow: 0 0 0 2px rgba(74, 111, 165, 0.2);
        }

        /* Attendance Buttons - Improved */
        .attendance-btns {
            display: flex;
            gap: 6px;
        }

        .present-btn,
        .absent-btn {
            border: none;
            padding: 6px 12px;
            border-radius: 6px;
            cursor: pointer;
            font-size: 13px;
            font-weight: 500;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .present-btn {
            background: #28a745;
            color: white;
        }

        .absent-btn {
            background: #dc3545;
            color: white;
        }

        .present-btn:hover {
            background: #218838;
            transform: translateY(-1px);
        }

        .absent-btn:hover {
            background: #c82333;
            transform: translateY(-1px);
        }

        /* Responsive Styles */
        @media (max-width: 768px) {
            .filter-dropdown {
                min-width: 100%;
            }

            .attendance-form {
                flex-direction: column;
                align-items: flex-start;
            }

            .attendance-btns {
                width: 100%;
            }

            .present-btn,
            .absent-btn {
                width: 100%;
                padding: 8px;
            }
        }
    </style>
</head>

<body>
    <?php include APPPATH . 'views/admin/includes/sidebar.php'; ?>
    <!-- CONTENT -->
    <section id="content">
        <?php include APPPATH . 'views/admin/includes/topbar.php'; ?>

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h2>የተማሪዎች አቴንዳንስ መመዝገቢያ ገጽ</h2>

                    <ul class="breadcrumb">
                        <li> <a href="<?php echo base_url('admin/dashboard'); ?>">ዳሽቦርድ</a> </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li> <a class="active" href="<?php echo base_url('attendance/list'); ?>">የተማሪዎች አቴንዳንስ ዝርዝር</a>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li> <a class="active" href="<?php echo base_url('attendance/list'); ?>">የተማሪዎች አቴንዳንስ መመዝገቢያ</a>
                        </li>
                    </ul>
                </div>

                <!-- error and success message-->
                <?php if ($this->session->flashdata('attendance_message')): ?>
                    <div id="flash-message"
                        class="message-box <?php echo $this->session->flashdata('attendance_message')['type']; ?>">
                        <?php echo $this->session->flashdata('attendance_message')['text']; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="table-container">
                <div class="order">

                    <div class="filter-container">
                        <!-- Age Category Dropdown -->
                        <div class="filter-dropdown">
                            <button class="filter-btn" type="button">እድሜ ክፍል</button>
                            <div class="filter-content">
                                <a href="<?= base_url('attendance/list_and_record') ?>">ሁሉም</a>
                                <?php foreach ($categories['age'] as $age): ?>
                                    <a href="<?= base_url('attendance/list_and_record?age_category_id=' . $age->id) ?>">
                                        <?= $age->name ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Curriculum Dropdown -->
                        <div class="filter-dropdown">
                            <button class="filter-btn" type="button">ስርአተ ትምህርት ክፍል</button>
                            <div class="filter-content">
                                <a href="<?= base_url('attendance/list_and_record') ?>">ሁሉም</a>
                                <?php foreach ($categories['curriculum'] as $curriculum): ?>
                                    <a href="<?= base_url('attendance/list_and_record?curriculum_id=' . $curriculum->id) ?>">
                                        <?= $curriculum->name ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Department Dropdown -->
                        <div class="filter-dropdown">
                            <button class="filter-btn" type="button">አገልግሎት </button>
                            <div class="filter-content">
                                <a href="<?= base_url('attendance/list_and_record') ?>">ሁሉም</a>
                                <?php foreach ($categories['department'] as $department): ?>
                                    <a href="<?= base_url('attendance/list_and_record?department_id=' . $department->id) ?>">
                                        <?= $department->name ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <!-- Choir Dropdown -->
                        <div class="filter-dropdown">
                            <button class="filter-btn" type="button">መዘምራን ክፍል</button>
                            <div class="filter-content">
                                <a href="<?= base_url('attendance/list_and_record') ?>">ሁሉም</a>
                                <?php foreach ($categories['choir'] as $choir): ?>
                                    <a href="<?= base_url('attendance/list_and_record?choir_id=' . $choir->id) ?>">
                                        <?= $choir->name ?>
                                    </a>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    </div>

                    <?php if (!empty($students)): ?>

                        <table class="table-container-table">
                            <thead>
                                <tr>
                                    <th>መታወቂያ ቁ.</th>
                                    <th>ሙሉ ስም</th>
                                    <th>የእድሜ ክፍል</th>
                                    <th>የስርአተ ት/ክፍል</th>
                                    <th>የአገልግሎት ክፍል</th>
                                    <th>የመዝሙር ክፍል</th>
                                    <th>አቴንዳንስ</th>

                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td><?= $student->student_id ?></td>
                                        <td><?= $student->fname . ' ' . $student->mname . ' ' . $student->lname ?></td>
                                        <td><?= $student->age_category_name ?></td>
                                        <td><?= $student->curriculum_name ?></td>
                                        <td><?= $student->department_name ?></td>
                                        <td><?= $student->choir_name ?></td>
                                        <td>
                                            <form class="attendance-form" method="post" action="<?= base_url('attendance/mark_attendance') ?>">
                                                <input type="hidden" name="student_id" value="<?= $student->student_id ?>">
                                                <input type="hidden" name="redirect_url" value="<?= current_url() . '?' . $_SERVER['QUERY_STRING'] ?>">
                                                <input type="text" name="date" class="date-input" placeholder="ቀቀ/ወወ/አአአአ" required>
                                                <input type="time" name="time" class="time" required>
                                                <div class="attendance-btns">
                                                    <button type="submit" name="status" value="present" class="present-btn">ተገኝቷል</button>
                                                    <button type="submit" name="status" value="absent" class="absent-btn">ቀሪ</button>
                                                </div>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>


                            </tbody>
                        </table>


                    <?php else: ?>
                        <br><br><br><br><br><br>
                        <h2 style="text-align: center; ">የተመረጠው ምድብ ውስጥ ምንም ተማሪ አልተገኘም!</h2>
                    <?php endif; ?>
                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
</body>
<script>
    // Toggle dropdowns
    document.addEventListener('DOMContentLoaded', function() {
        const dropdowns = document.getElementsByClassName("filter-btn");
        for (let i = 0; i < dropdowns.length; i++) {
            dropdowns[i].addEventListener("click", function() {
                this.nextElementSibling.classList.toggle("show");
            });
        }

        // Close dropdowns when clicking outside
        window.onclick = function(event) {
            if (!event.target.matches('.filter-btn')) {
                const dropdowns = document.getElementsByClassName("filter-content");
                for (let i = 0; i < dropdowns.length; i++) {
                    if (dropdowns[i].classList.contains('show')) {
                        dropdowns[i].classList.remove('show');
                    }
                }
            }
        }

        // Ethiopian date input formatting
        function formatEthiopianDateInput(input) {
            input.addEventListener('input', function(e) {
                let value = e.target.value.replace(/\D/g, '');
                if (value.length > 2) value = value.slice(0, 2) + '/' + value.slice(2);
                if (value.length > 5) value = value.slice(0, 5) + '/' + value.slice(5, 9);
                e.target.value = value;
            });

            input.addEventListener('keydown', function(e) {
                if (e.key === 'Backspace' || e.key === 'Delete' || e.key.startsWith('Arrow')) return;
                if (/\D/.test(e.key)) e.preventDefault();
            });
        }

        const dateInputs = document.querySelectorAll('input[name="date"]');
        dateInputs.forEach(input => formatEthiopianDateInput(input));
    });
</script>
<script src="<?php echo base_url('assets/js/script.js'); ?>"></script>

</html>