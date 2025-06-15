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
                    <h2>የተማሪ ትምህርት ነክ መረጃዎች መመዝገቢያ ገጽ</h2>

                    <ul class="breadcrumb">
                        <li>
                            <a href="<?php echo base_url('dashboard'); ?>">ዳሽቦርድ</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li> <a>የተማሪዎች ዝርዝር</a> </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="<?php echo base_url('student/add_academic_info'); ?>">ተማሪ መመዝገቢያ</a>
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
                <?php echo form_open_multipart("student/save_academic_info/" . $student_id); ?>
                <div class="unique-form-grid">
                    <?php if ($show_apostolic): ?>
                        <div class="unique-form-group">
                            <label>ሐዋርያዊ ምድባት <span class="required">*</span></label>
                            <select name="apostolic_id" id="apostolic_id" required>
                                <option value="">--ምረጥ--</option>
                                <?php foreach ($apostolic_categories as $category): ?>
                                    <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="unique-form-group">
                        <label>ስርአተ ትምህርት </label>
                        <select name="curriculum_id" id="curriculum_id">
                            <option value="">--ምረጥ--</option>
                            <?php foreach ($curriculums as $curriculum): ?>
                                <option value="<?php echo $curriculum->id; ?>"><?php echo $curriculum->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <?php if ($show_service_dept): ?>
                        <div class="unique-form-group">
                            <label>የአገልግሎት ምድብ </label>
                            <select name="department_id" id="department_id">
                                <option value="">--ምረጥ--</option>
                                <?php foreach ($departments as $department): ?>
                                    <option value="<?php echo $department->id; ?>"><?php echo $department->name; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    <?php endif; ?>

                    <div class="unique-form-group">
                        <label>መዝሙር ክፍል </label>
                        <select name="choir_id" class="form-control">
                            <option value="">--ምረጥ--</option>
                            <?php foreach ($choirs as $choir): ?>
                                <option value="<?php echo $choir->id; ?>"><?php echo $choir->name; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="unique-form-group">
                        <label>ስራ/ትምህርት <span class="required">*</span></label>
                        <select name="occupation" id="occupation" required>
                            <option value="">--ምረጥ--</option>
                            <?php foreach ($occupations as $occupation): ?>
                                <option value="<?= $occupation ?>"><?= ucfirst($occupation) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="unique-form-group">
                        <label>የትምህርት ደረጃ <span class="required">*</span></label>
                        <select name="education_level" id="education_level" required>
                            <option value="">--ምረጥ--</option>
                            <?php foreach ($education_levels as $education): ?>
                                <option value="<?= $education ?>"><?= ucfirst($education) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="unique-form-group">
                        <label for="academic_field">የትምህርት ዘርፍ </label>
                        <input type="text" id="academic_field" name="academic_field" placeholder="የትምህርት ዘርፍ ያስገቡ(ምሳሌ፦ ኤሌክትሪካል ምህንድስና፣ ኮምፒተር ሳይንስ)...">
                    </div>

                    <div class="unique-form-group">
                        <label for="workplace">የስራ ቦታ </label>
                        <input type="text" id="workplace" name="workplace" placeholder="የስራ ቦታ ያስገቡ...">
                    </div>

                    <div class="unique-form-group">
                        <label for="registration_date">የምዝገባ ቀን <span class="required">*</span></label>
                        <input type="text" id="registration_date" name="registration_date" placeholder="ቀን/ወር/አመት "
                            required>
                    </div>

                    <div class="unique-form-actions">
                        <button type="submit"
                            style="background-color: #007bff; color: #fff; border: none; padding: 10px 20px; font-size: 14px; border-radius: 4px; cursor: pointer; margin: 10px auto; display: block;">
                            መዝግብ
                        </button>
                    </div>


                </div>
            </div>

        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->
</body>

<script src="<?php echo base_url('assets/js/script.js'); ?>"></script>

<script>
    function formatEthiopianDateInput(input) {
        input.addEventListener('input', function(e) {
            let value = e.target.value;

            // Remove non-numeric characters
            value = value.replace(/\D/g, '');

            // Add forward slashes after day and month
            if (value.length > 2) {
                value = value.slice(0, 2) + '/' + value.slice(2);
            }
            if (value.length > 5) {
                value = value.slice(0, 5) + '/' + value.slice(5, 9); // Year is 4 digits
            }

            // Validate day, month, and year
            if (value.length >= 2) {
                const day = parseInt(value.slice(0, 2), 10);
                if (day > 30 || day === 0) {
                    // Replace invalid day with '01'
                    value = '01' + value.slice(2);
                }
            }

            if (value.length >= 5) {
                const month = parseInt(value.slice(3, 5), 10);
                if (month > 13 || month === 0) {
                    // Replace invalid month with '01'
                    value = value.slice(0, 3) + '01' + value.slice(5);
                }
            }

            if (value.length >= 10) {
                const year = parseInt(value.slice(6, 10), 10);
                if (year === 0) {
                    // Replace invalid year with '1990'
                    value = value.slice(0, 6) + '1990';
                }
            }

            // Update the input value
            e.target.value = value;
        });

        input.addEventListener('keydown', function(e) {
            // Allow backspace, delete, and arrow keys
            if (e.key === 'Backspace' || e.key === 'Delete' || e.key.startsWith('Arrow')) {
                return;
            }

            // Block non-numeric characters
            if (/\D/.test(e.key)) {
                e.preventDefault();
            }

            // Auto-jump to the next field
            const value = e.target.value;
            if (value.length === 2 || value.length === 5) {
                e.target.value += '/';
            }
        });
    }

    const registrationDateInput = document.getElementById('registration_date');

    formatEthiopianDateInput(registrationDateInput);
</script>

</html>