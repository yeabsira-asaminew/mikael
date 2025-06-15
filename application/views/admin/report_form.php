<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon -->
    <link rel="icon" href="<?php echo base_url('assets/images/favicon.ico'); ?>" type="image/x-icon">
    <title>ሪፖርት እና ዳታቤዝ | ሰንበት አቴንዳንስ ሲስተም</title>

    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/schedule.css'); ?>">
    <style>
        .title {
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
            color: #004080;
            font-weight: bold;
            text-align: left;
        }

        .form-label {
            font-size: 1rem;
            margin: 0.5rem 0;
            color: #333;
        }

        .report-form {
            display: flex;
            flex-direction: column;
            align-items: left;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .schedule-select {
            width: 250px;
            padding: 5px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #fff;
        }

        .checkbox-group {
            margin: 10px 0;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
        }

        .checkbox-group label {
            margin: 3px 0;
            font-size: 0.9rem;
            color: #444;
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
                    <h2>ሪፖርት እና ዳታቤዝ መፍጠሪያና </h2>

                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url('dashboard'); ?>">ዳሽቦርድ</a></li>
                        <li><i class="bx bx-chevron-right"></i></li>
                        <li><a class="active" href="<?php echo base_url('report'); ?>">ሪፖርት እና ዳታቤዝ</a></li>
                    </ul>
                </div>

                <?php if ($this->session->flashdata('report_message')): ?>
                    <div id="flash-message"
                        class="message-box <?php echo $this->session->flashdata('report_message')['type']; ?>">
                        <?php echo $this->session->flashdata('report_message')['text']; ?>
                    </div>
                <?php endif; ?>


            </div>

            <div class="unique-form-container">
                <div class="schedule-container">

                    <div class="table-container">
                        <h3 class="title">ሪፖርት መፍጠሪያ</h3>



                        <form action="<?= base_url('report/generate_report') ?>" method="post" class="report-form">
                            <label class="form-label">በአመቱ የተመዘገቡ ተማሪዎች ቁጥር (ከአንድ በላይ መምረጥ ይቻላል)</label>
                            <select name="years[]" multiple size="10" class="schedule-select">
                                <?php for ($year = 2000; $year <= 2100; $year++) { ?>
                                    <option value="<?= $year ?>"><?= $year ?> ዓ.ም</option>
                                <?php } ?>
                            </select>


                            <div class="checkbox-group">
                                <label><input type="checkbox" name="data[]" value="student_status_report"> የተማሪዎች ቁጥር በትምህርት ሁኔታ(active | inactive)</label>
                                <label><input type="checkbox" name="data[]" value="age_group_report"> የተማሪዎች ቁጥር በእድሜ ክፍል</label>
                                <label><input type="checkbox" name="data[]" value="apostolic_category_report"> የተማሪዎች ቁጥር በሐዋርያዊ ክፍል</label>
                                <label><input type="checkbox" name="data[]" value="curriculum_category_report"> የተማሪዎች ቁጥር በስርአተ ትምሀርት ክፍል</label>
                                <label><input type="checkbox" name="data[]" value="department_category_report"> የተማሪዎች ቁጥር በአገልግሎት ክፍል</label>
                                <label><input type="checkbox" name="data[]" value="choir_category_report"> የተማሪዎች ቁጥር በመዝሙር ክፍል</label>
                                <br>
                                <label><input type="checkbox" id="select_all"> ሁሉንም ምረጥ</label>
                            </div>

                            <button type="submit" class="schedule-button">📄 ሪፖርት ፍጠር</button>

                        </form>
                    </div>





                    <div class="schedule-container">

                        <h3 class="title">ዳታቤዝ ምትክ አስቀምጥ </h3>
                        <a href="<?= site_url('report/backup_db') ?>" class="schedule-button">ዳታቤዝ አውርድ</a>

                    </div>





                </div>
            </div>

        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <script src="<?= base_url('assets/js/script.js'); ?>"></script>
    <script>
        document.getElementById('select_all').addEventListener('click', function() {
            var checkboxes = document.querySelectorAll('input[type="checkbox"][name="data[]"]');
            var selectAllChecked = this.checked;

            checkboxes.forEach(function(checkbox) {
                checkbox.checked = selectAllChecked;
            });
        });
    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const singleYearRadio = document.getElementById('single_year');
            const multipleYearsRadio = document.getElementById('multiple_years');
            const singleYearSelect = document.getElementById('single_year_select');
            const multipleYearSelect = document.getElementById('multiple_year_select');

            singleYearRadio.addEventListener('change', function() {
                if (this.checked) {
                    singleYearSelect.style.display = 'block';
                    multipleYearSelect.style.display = 'none';
                }
            });

            multipleYearsRadio.addEventListener('change', function() {
                if (this.checked) {
                    singleYearSelect.style.display = 'none';
                    multipleYearSelect.style.display = 'block';
                }
            });
        });
    </script>

</body>

</html>