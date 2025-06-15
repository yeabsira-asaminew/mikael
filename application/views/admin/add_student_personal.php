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

                <?php echo form_open_multipart("student/save_personal_info"); ?>
                <div class="unique-form-grid">
                    <div class="unique-form-group">
                        <label for="fname">ስም <span class="required">*</span></label>
                        <input type="text" name="fname" id="fname" placeholder="ስም ያስገቡ..." required>
                    </div>

                    <div class="unique-form-group">
                        <label for="mname">የአባት ስም <span class="required">*</span></label>
                        <input type="text" id="mname" name="mname" placeholder="የአባት ስም ያስገቡ..." required>
                    </div>

                    <div class="unique-form-group">
                        <label for="lname">የአያት ስም <span class="required">*</span></label>
                        <input type="text" id="lname" name="lname" placeholder="የአያት ስም ያስገቡ..." required>
                    </div>

                    <div class="unique-form-group">
                        <label for="mother_name">የእናት ሙሉ ስም <span class="required">*</span></label>
                        <input type="text" id="mother_name" name="mother_name" placeholder="የእናት ሙሉ ስም ያስገቡ..." required>
                    </div>

                    <div class="unique-form-group">
                        <label for="sex">ጾታ <span class="required">*</span></label>
                        <select name="sex" id="sex" required>
                            <option value="">--ምረጥ--</option>
                            <option value="Male">ወንድ</option>
                            <option value="Female">ሴት</option>
                        </select>
                    </div>

                     <div class="unique-form-group">
                        <label for="dob">የትውልድ ዘመን <span class="required">*</span></label>
                        <input type="text" id="dob" name="dob" placeholder="ቀን/ወር/አመት " required>
                    </div>

                    <div class="unique-form-group">
                        <label for="pob">የትውልድ ቦታ <span class="required">*</span></label>
                        <input type="text" id="pob" name="pob" placeholder="የትውልድ ቦታ ያስገቡ..." required>
                    </div>
                    
                    <div class="unique-form-group">
                        <label for="christian_name">የክርስትና ስም <span class="required">*</span></label>
                        <input type="text" id="christian_name" name="christian_name" placeholder="የክርስትና ስም ያስገቡ..."
                            required>
                    </div>

                    <div class="unique-form-group">
                        <label for="God_father">የክርስትና አባት ስም </label>
                        <input type="text" id="God_father" name="God_father" placeholder="የክርስትና አባት ስም ያስገቡ...">
                    </div>

                    <div class="unique-form-group">
                        <label for="repentance_father">የንሰሃ አባት ስም </label>
                        <input type="text" id="repentance_father" name="repentance_father"
                            placeholder="የንሰሃ አባት ስም ያስገቡ..." >
                    </div>

                     <div class="unique-form-group">
                        <label for="repentance_father_church">የንሰሃ አባት ደብር </label>
                        <input type="text" id="repentance_father_church" name="repentance_father_church"
                            placeholder="የንሰሃ አባት ደብር ያስገቡ..." >
                    </div>


                    <div class="unique-form-group">
                        <label for="phone1">ስልክ ቁጥር <span class="required">*</span></label>
                        <input type="tel" id="phone1" name="phone1" placeholder="ስልክ ቁጥር ያስገቡ..." required>
                    </div>

                    <div class="unique-form-group">
                        <label for="phone2">ተጨማሪ ስልክ ቁጥር </label>
                        <input type="tel" id="phone2" name="phone2" placeholder="ተጨማሪ ስልክ ቁጥር ካለ ያስገቡ...">
                    </div>

                    <div class="unique-form-group">
                        <label for="address">የመኖሪያ አድራሻ <span class="required">*</span></label>
                        <input type="text" id="address" name="address"
                            placeholder="የተማሪውን የመኖሪያ አድራሻ ያስገቡ..." required>
                    </div>

                    <div class="unique-form-group">
                        <label for="photo">የተማሪው ምስል(የተፈቀደው ከፍተኛ 5MB) <span class="required">*</span></label>
                        <input type="file" name="photo" id="photo" required>
                    </div>

                    <div class="unique-form-actions">
                        <button type="submit"
                            style="background-color: #007bff; color: #fff; border: none; padding: 10px 20px; font-size: 14px; border-radius: 4px; cursor: pointer; margin: 10px auto; display: block;">
                            ቀጣይ ክፍል
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

    // Apply the formatting to both date inputs
    const dobInput = document.getElementById('dob');
    const registrationDateInput = document.getElementById('registration_date');

    formatEthiopianDateInput(dobInput);
    formatEthiopianDateInput(registrationDateInput);
</script>

</html>