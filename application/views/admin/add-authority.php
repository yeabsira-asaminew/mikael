<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon -->
    <link rel="icon" href="<?php echo base_url('assets/images/favicon.ico'); ?>" type="image/x-icon">
    <title>አስተዳዳሪ መመዝገቢያ | ሰንበት አቴንዳንስ ሲስተም</title>

    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
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
                    <h2>ሲስተም አስተዳዳሪ መመዝገቢያ</h2>

                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url('dashboard'); ?>">ዳሽቦርድ</a></li>
                        <li><i class="bx bx-chevron-right"></i></li>
                        <li> <a>የአስተዳዳሪዎች ዝርዝር</a> </li>
                        <li><i class="bx bx-chevron-right"></i></li>
                        <li><a class="active" href="<?php echo base_url('Authority/add_auth'); ?>">አስተዳዳሪ መመዝገቢያ</a>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="unique-form-container">

                <?php if ($this->session->flashdata('message')): ?>
                <div class="message-box <?= $this->session->flashdata('message')['type']; ?>">
                    <?= $this->session->flashdata('message')['text']; ?>
                </div>
                <?php endif; ?>

                <!-- Add Admin Form -->
                <form action="<?= base_url('Authority/save_auth'); ?>" method="post">
                    <div class="unique-form-grid">

                        <div class="unique-form-group">
                            <label for="email">ኢ-ሜይል <span class="required">*</span></label>
                            <input type="email" id="email" name="email" value="<?= set_value('email'); ?>"
                                placeholder="ኢ-ሜይል...">
                            <?= form_error('email', '<div style="color: red;">', '</div>'); ?>
                        </div>

                        <div class="unique-form-group">
                            <label for="role">ሚና <span class="required">*</span></label>
                            <select name="role" id="role">
                                <option value="">--ምረጥ--</option>
                                <option value="admin">አድሚን</option>
                                <option value="superadmin">ሱፐር አድሚን</option>
                            </select>
                        </div>


                        <div class="unique-form-actions">
                            <button type="submit">መዝግብ</button>
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

</html>