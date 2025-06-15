<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon -->
    <link rel="icon" href="<?php echo base_url('assets/images/favicon.ico'); ?>" type="image/x-icon">
    <title>የአስተዳዳሪዎች ዝርዝር እና መመዝገቢያ | ሰንበት አቴንዳንስ ሲስተም</title>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/icon.css'); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo base_url('assets/icon/simple-line-icons/css/simple-line-icons.css') ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/schedule.css'); ?>">

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
                    <h2>የሲስተም አስተዳዳሪዎች ዝርዝር እና መመዝገቢያ ገጽ</h2>

                    <ul class="breadcrumb">
                        <li> <a href="<?php echo base_url('admin/dashboard'); ?>">ዳሽቦርድ</a> </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li> <a class="active" href="<?php echo base_url('authority/list'); ?>">የአስተዳዳሪዎች ዝርዝር</a> </li>
                    </ul>
                </div>

                <!-- error and success message-->
                <?php if ($this->session->flashdata('auth_message')): ?>
                    <div id="flash-message"
                        class="message-box <?php echo $this->session->flashdata('auth_message')['type']; ?>">
                        <?php echo $this->session->flashdata('auth_message')['text']; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="table-container">
                <div class="order">


                    <!-- add new deartment button-->
                    <a href="javascript:void(0);" class="custom-add-btn" style="margin-bottom: 15px; display: inline-block;" onclick="openModal()">
                        <i class="bx bx-plus"></i> አዲስ አስተዳዳሪ ያክሉ
                    </a>



                    <table class="table-container-table">
                        <thead>
                            <tr>
                                <th>መታወቂያ ቁ.</th>
                                <th>ኢ-ሜይል</th>
                                <th>ሚና</th>
                                <th>መጨረሻ የገቡበት</th>
                                <th>የታከሉበት</th>
                                <th>መተግበሪያዎች</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($authorities)): ?>
                                <?php foreach ($authorities as $authority): ?>
                                    <tr>
                                        <td><?= $authority['id']; ?></td>
                                        <td><?= $authority['email']; ?></td>
                                        <td><?= $authority['role']; ?></td>
                                        <td><?= $authority['last_login']; ?></td>
                                        <td><?= $authority['created_at']; ?></td>
                                        <td>

                                            <a href="<?= base_url('authority/delete/' . $authority['id']) ?>"
                                                class="btn btn-danger confirm-delete"
                                                onclick="return confirm('እርግጠኛ ነዎት ይህን አስተዳዳሪ መሰረዝ ይፈልጋሉ?');">
                                                <i class='bx bx-trash'> ሰርዝ</i> <!-- Updated icon for delete -->
                                            </a>
                                            <a href="<?= base_url('authority/reset_password/' . $authority['id']) ?>"
                                                class="btn btn-danger confirm-delete"
                                                onclick="return confirm('እርግጠኛ ነዎት በአስተዳዳሪው ጥያቄ መሰረት ነው የይለፍ ቃሉን መቀየር የፈለጉት?');">
                                                <i class='bx bx-reset'> የይለፍ ቃል ቀይር </i> <!-- Updated icon for delete -->
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="100%" style="text-align: center; ">
                                        <h2>ምንም አስተዳዳሪ አልተገኘም</h2>
                                    </td>
                                </tr>

                            <?php endif; ?>
                        </tbody>
                    </table>

                </div>



                <!-- add authority modal -->
                <div id="editModal" class="modal">
                    <div class="modal-content">
                        <span class="close-btn" onclick="closeModal()">&times;</span>
                        <h2 style="margin-bottom: 30px;">አስተዳዳሪ መመዝገቢያ</h2>


                        <form id="editForm" method="post" action="<?= site_url('authority/save_auth'); ?>/"
                            onsubmit="this.action += document.getElementById('editId').value;">

                            <label for="email">ኢ-ሜይል</span></label>
                            <input type="email" id="email" name="email" class="schedule-input" value="<?= set_value('email'); ?>"
                                placeholder="ኢ-ሜይል...">
                            <?= form_error('email', '<div style="color: red;">', '</div>'); ?>

                            <label for="role">ሚና</span></label>
                            <select name="role" id="role" class="schedule-select">
                                <option value="">--ምረጥ--</option>
                                <option value="admin">አድሚን</option>
                                <option value="superadmin">ሱፐር አድሚን</option>
                            </select>

                            <button type="submit" class="schedule-button">አክል </button>
                        </form>


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
    // Select the modal
    const modal = document.getElementById('editModal');

    // Function to open the modal without parameters
    function openModal() {
        modal.style.display = "flex";
    }

    // Function to close modal
    function closeModal() {
        modal.style.display = "none";
    }

    // Close modal if user clicks outside the modal content
    window.onclick = function(event) {
        if (event.target === modal) {
            closeModal();
        }
    }

    // Ensure modal is hidden on page load
    document.addEventListener("DOMContentLoaded", function() {
        modal.style.display = "none";
    });
</script>

</html>