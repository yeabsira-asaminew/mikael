<!-- Include Boxicons CSS -->
<link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">

<!-- SIDEBAR -->
<section id="sidebar">
    <a href="<?php echo base_url('dashboard'); ?>" class="brand">
        <img src="<?= base_url('assets/images/logo.png'); ?>" alt="ሰንበት ት/ቤት ሎጎ" style="height: 40px; width: auto;">
    </a>


    <?php
    // Get the current controller and method from the URL
    $current_controller = $this->uri->segment(1);
    $current_method = $this->uri->segment(2);
    ?>

    <ul class="side-menu top">
        <!-- Dashboard -->
        <li class="<?= ($current_controller == 'dashboard') ? 'active' : ''; ?>">
            <a href="<?php echo base_url('dashboard'); ?>">
                <i class='bx bx-grid-alt'></i>
                <span class="text">ዳሽቦርድ</span>
            </a>
        </li>

        <!-- Students List (Active for list, add, edit) -->
        <li
            class="<?= ($current_controller == 'student' && in_array($current_method, ['list', 'add_student', 'add', 'add_personal_info', 'save_personal_info', 'add_academic_info', 'save_academic_info', 'view', 'edit', 'update'])) ? 'active' : ''; ?>">
            <a href="<?php echo base_url('student/list'); ?>">
                <i class='bx bxs-user-detail'></i>
                <span class="text">ተማሪዎች</span>
            </a>
        </li>
        <!-- Manage Schedules -->
        <li
            class="<?= ($current_controller == 'academic' && in_array($current_method, ['schedule', 'add_schedule'])) ? 'active' : ''; ?>">
            <a href="<?php echo base_url('academic/schedule'); ?>">
                <i class='bx bx-time'></i>
                <span class="text">መርሐግብሮች</span>
            </a>
        </li>

         <!-- only superadmins has the previlege -->
        <?php if ($this->session->userdata('role') == 'superadmin'): ?>
        <!-- Attendances  -->
        <li
            class="<?= ($current_controller == 'attendance' && in_array($current_method, ['list', 'list_and_record', 'mark_attendance'])) ? 'active' : ''; ?>">
            <a href="<?php echo base_url('attendance/list'); ?>">
                <i class='bx bx-list-ul '></i>
                <span class="text">አቴንዳንስ</span>
            </a>
        </li>
        <!-- Authorities (Active for list and add) -->
        <li
            class="<?= ($current_controller == 'authority' && in_array($current_method, ['list', 'add_auth', 'save_auth'])) ? 'active' : ''; ?>">
            <a href="<?php echo base_url('authority/list'); ?>">
                <i class='bx bxs-user-plus'></i>
                <span class="text">አስተዳዳሪዎች</span>
            </a>
        </li>
        <!-- Inactive Students -->
        <li class="<?= ($current_controller == 'student' && $current_method == 'list_inactive') ? 'active' : ''; ?>">
            <a href="<?php echo base_url('student/list_inactive'); ?>">
                <i class='bx bxs-user-x'></i>
                <span class="text">ንቁ ያልሆኑ ተማሪዎች</span>
            </a>
        </li>

        <li class="<?= ($current_controller == 'report' ) ? 'active' : ''; ?>">
            <a href="<?php echo base_url('report'); ?>">
                <i class='bx bxs-report'></i>
                <span class="text">ሪፖርት</span>
            </a>
        </li>
        <?php endif; ?>
    </ul>

    <ul class="side-menu">

       

        <!-- Scanner -->
        <li class="<?= ($current_controller == 'attendance' && $current_method == 'scanner') ? 'active' : ''; ?>">
            <a href="<?php echo base_url('attendance/scanner'); ?>">
                <i class='bx bx-scan'></i>
                <span class="text">Scanner</span>
            </a>
        </li>


    </ul>


</section>