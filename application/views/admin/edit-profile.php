<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon -->
    <link rel="icon" href="<?php echo base_url('assets/images/favicon.ico'); ?>" type="image/x-icon">
    <title>መገለጫ አርታዒ | ሰንበት አቴንዳንስ ሲስተም </title>
    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">


    <style>
        .password-wrapper {
            position: relative;
        }

        .password-wrapper input {
            width: 100%;
            padding-right: 40px;
            /* space for the icon */
        }

        .password-wrapper .toggle-eye {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #555;
        }
    </style>

<body>

    <?php include APPPATH . 'views/admin/includes/sidebar.php'; ?>

    <!-- CONTENT -->
    <section id="content">
        <?php include APPPATH . 'views/admin/includes/topbar.php'; ?>

        <!-- MAIN -->
        <main>
            <div class="head-title">
                <div class="left">
                    <h2>የአስተዳዳሪ የግል መረጃዎች አርታዒ </h2>
                    <ul class="breadcrumb">
                        <li>
                            <a href="<?php echo base_url('dashboard'); ?>">ዳሽቦርድ</a>
                        </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li>
                            <a class="active" href="<?php echo base_url('student/edit'); ?>">መገለጫ አርታዒ</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="unique-form-container">

                <?php if ($this->session->flashdata('auth_message')): ?>
                    <?php $message = $this->session->flashdata('auth_message'); ?>
                    <div class="alert alert-<?= $message['type'] == 'success' ? 'success' : 'danger'; ?>">
                        <?= $message['text']; ?>
                    </div>
                <?php endif; ?>


                <h2>የይለፍ ቃልዎ ትክክል መሆኑን ያረጋግጡ!</h2>

                <!-- Add Admin Form -->
                <form action="<?= base_url('Authority/update_profile/'); ?>" method="post">
                    <div class="unique-form-grid">

                        <div class="unique-form-group">
                            <label for="email">ኢ-ሜይል <span class="required">*</span></label>
                            <input type="email" id="email" name="email"
                                value="<?php echo isset($profile['email']) ? $profile['email'] : ''; ?>" required>
                            <?= form_error('email', '<div style="color: red;">', '</div>'); ?>
                        </div>

                        <div class="unique-form-group">
                            <label for="password">አዲስ የይለፍ ቃል</label>
                            <div class="password-wrapper">
                                <input type="password" id="password" name="password">
                                <i class="bx bx-hide toggle-eye" id="togglePassword" onclick="togglePassword()"></i>
                            </div>
                            <?= form_error('password', '<div style="color: red;">', '</div>'); ?>
                        </div>

                        <div class="unique-form-group">
                            <label for="confirm_password">የይለፍ ቃል ያረጋግጡ</label>
                            <input type="password" id="confirm_password" name="confirm_password">
                            <?= form_error('confirm_password', '<div style="color: red;">', '</div>'); ?>
                        </div>

                        <!--
                        <div class="unique-form-group">
                            <label for="password">አዲስ የይለፍ ቃል <i class="bx bx-hide" id="togglePassword" onclick="togglePassword()"></i></label>
                            <input type="password" id="password" name="password">
                            <?= form_error('password', '<div style="color: red;">', '</div>'); ?>
                        </div>

                        <div class="unique-form-group">
                            <label for="confirm_password">የይለፍ ቃል ያረጋግጡ</label>
                            <input type="password" id="confirm_password" name="confirm_password">
                            <?= form_error('confirm_password', '<div style="color: red;">', '</div>'); ?>
                        </div>
                -->
                        <div class="unique-form-actions">
                            <button type="submit">አርትዕ</button>
                        </div>
                    </div>
                </form>

            </div>

        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

</body>

<script src="<?= base_url('assets/js/script.js'); ?>"></script>
<script>
    function togglePassword() {
        const passwordField = document.getElementById("password");
        const toggleIcon = document.getElementById("togglePassword");
        const isPassword = passwordField.type === "password";

        passwordField.type = isPassword ? "text" : "password";
        toggleIcon.classList.toggle("bx-hide", !isPassword);
        toggleIcon.classList.toggle("bx-show", isPassword);
    }
</script>

</html>