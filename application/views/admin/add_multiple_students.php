<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon -->
    <link rel="icon" href="<?php echo base_url('assets/images/favicon.ico'); ?>" type="image/x-icon">
    <title>ተማሪ መመዝገቢያ | ሰንበት አቴንዳንስ ሲስተም</title>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">

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
                    <h2>የተማሪ ግላዊ መረጃዎች መመዝገቢያ ገጽ</h2>

                    <ul class="breadcrumb">
                        <li>
                            <a href="<?php echo base_url('dashboard'); ?>">ዳሽቦርድ</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li> <a>የተማሪዎች ዝርዝር</a> </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="<?php echo base_url('student/add_personal_info'); ?>">ተማሪ መመዝገቢያ</a>
                        </li>
                    </ul>
                </div>

                <!-- error and success message-->
                <?php if ($this->session->flashdata('student_message')): ?>
                    <div id="flash-message"
                        class="message-box <?php echo $this->session->flashdata('student_message')['type']; ?>">
                        <?php echo $this->session->flashdata('student_message')['text']; ?>
                    </div>
                <?php endif; ?>

            </div>





            <div class="unique-form-container">

                <div style="width: 700px; height: 200px; margin: 20px auto; background-color: #f8f9fa; border: 2px dashed #007bff; border-radius: 10px; padding: 10px; box-shadow: 0 3px 8px rgba(0,0,0,0.08); text-align: center;">
                    <form id="importForm" method="post" action="<?php echo base_url('student/import_excel'); ?>" enctype="multipart/form-data" style="margin: 0;">
                        <label for="import_file" style="display: inline-block; cursor: pointer; padding: 4px 10px; border: 1px solid #007bff; background-color: #ffffff; color: #007bff; border-radius: 5px; font-size: 13px;">
                            <i class="icon-cloud-upload"></i> ፋይል ይምረጡ
                        </label>
                        <input id="import_file" type="file" name="import_file" accept=".xls, .xlsx, .csv" style="opacity: 0; position: absolute; z-index: -1;">

                        <!-- File name display -->
                        <div id="fileNameDisplay" style="margin-top: 6px; font-size: 12px; color: #333;"></div>

                        <div style="margin-top: 4px; font-size: 11px; color: #6c757d;">.xls, .xlsx, .csv only</div>

                        <div style="margin-top: 6px; display: flex; justify-content: center; gap: 8px;">
                            <button id="importBtn" type="submit" disabled style="padding: 5px 10px; font-size: 12px; border-radius: 4px; border: none; background-color: #6c757d; color: white; cursor: not-allowed;">
                                <i class="icon-docs"></i> Import
                            </button>

                            <a href="<?= base_url('student/excel_format') ?>"
                                style="display: inline-flex; align-items: center; background-color: #007BFF; color: #fff; font-size: 12px; font-weight: bold; padding: 5px 10px; border-radius: 4px; text-decoration: none; transition: background 0.3s ease;"
                                onmouseover="this.style.backgroundColor='#0056b3'"
                                onmouseout="this.style.backgroundColor='#007BFF'">
                                <i class="bx bx-download" style="margin-right: 4px;"></i> EXCEL format ያውርዱ
                            </a>
                        </div>

                        <div id="fileError" style="margin-top: 6px; font-size: 11px; color: red; display: none;">
                            እባክዎ ፋይል ይምረጡ!
                        </div>
                    </form>
                </div>
            </div>

        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
</body>

<script src="<?php echo base_url('assets/js/script.js'); ?>"></script>

<script>
    const fileInput = document.getElementById('import_file');
    const importBtn = document.getElementById('importBtn');
    const fileError = document.getElementById('fileError');
    const fileNameDisplay = document.getElementById('fileNameDisplay');
    const form = document.getElementById('importForm');

    fileInput.addEventListener('change', function() {
        if (fileInput.files.length > 0) {
            const fileName = fileInput.files[0].name;
            fileNameDisplay.textContent = `የተመረጠ ፋይል፡ ${fileName}`;
            importBtn.disabled = false;
            importBtn.style.backgroundColor = '#007bff';
            importBtn.style.cursor = 'pointer';
            fileError.style.display = 'none';
        } else {
            fileNameDisplay.textContent = '';
            importBtn.disabled = true;
            importBtn.style.backgroundColor = '#6c757d';
            importBtn.style.cursor = 'not-allowed';
        }
    });

    form.addEventListener('submit', function(e) {
        if (fileInput.files.length === 0) {
            e.preventDefault();
            fileError.style.display = 'block';
        }
    });
</script>

</html>