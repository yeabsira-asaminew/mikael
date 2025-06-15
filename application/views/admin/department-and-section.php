<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon -->
    <link rel="icon" href="<?php echo base_url('assets/images/favicon.ico'); ?>" type="image/x-icon">
    <title>የትምህርትና ስራ ክፍሎች | ሰንበት አቴንዳንስ ሲስተም</title>

    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">
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
                    <h2>የትምህርትና ስራ ክፍሎች መፍጠሪያና ማስተካከያ</h2>

                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url('dashboard'); ?>">ዳሽቦርድ</a></li>
                        <li><i class="bx bx-chevron-right"></i></li>
                        <li><a class="active" href="<?php echo base_url('academic/department_and_section'); ?>">የትምህርትና
                                ስራ ክፍሎች</a></li>
                    </ul>
                </div>

                <?php if ($this->session->flashdata('academic_message')): ?>
                    <div id="flash-message"
                        class="message-box <?php echo $this->session->flashdata('academic_message')['type']; ?>">
                        <?php echo $this->session->flashdata('academic_message')['text']; ?>
                    </div>
                <?php endif; ?>


            </div>

            <div class="unique-form-container">
                <div class="schedule-container">

                    <!-- department list -->
                    <div class="table-container">

                        <!-- only superadmins has the previlege -->
                        <?php if ($this->session->userdata('role') == 'superadmin'): ?>
                            <!-- add new deartment button-->
                            <a href="javascript:void(0);" class="custom-add-btn"
                                style="margin-bottom: 15px; display: inline-block;" onclick="openAddDepartmentModal()">
                                <i class="bx bx-plus"></i> አዲስ የስራ ክፍል ያክሉ
                            </a>
                        <?php endif; ?>

                        <table>
                            <thead>
                                <tr>
                                    
                                    <th>የስራ ክፍል</th>

                                    <!-- only superadmins has the previlege -->
                                    <?php if ($this->session->userdata('role') == 'superadmin'): ?>
                                        <th>መፈጸሚያዎች</th>
                                    <?php endif; ?>

                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($departments)): ?>
                                    <?php foreach ($departments as $department): ?>
                                        <tr>
                                           
                                            <td><?= $department['name'] ?></td>


                                            <!-- only superadmins has the previlege -->
                                            <?php if ($this->session->userdata('role') == 'superadmin'): ?>
                                                <td>
                                                    <!-- Edit Department Button -->
                                                    <a href="javascript:void(0);" class="edit-btn"
                                                        onclick="openDepartmentModal(<?= $department['id'] ?>, '<?= $department['name'] ?>')">
                                                        አርትዕ
                                                    </a> |
                                                    <a href="<?= site_url('academic/delete_department/' . $department['id']) ?>"
                                                        onclick="return confirm('እርግጠኛ ነዎት ይሄን የስራ ክፍል መሰረዝ ይፈልጋሉ?')"
                                                        class="delete-btn">
                                                        ሰርዝ
                                                    </a>
                                                </td>
                                            <?php endif; ?>

                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="100%" style="text-align: center;">
                                            <h2>ምንም የስራ ክፍል አልተገኘም.</h2>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- section List -->
                    <div class="table-container">



                        <!-- add new deartment button-->
                         <!-- only superadmins has the previlege -->
                         <?php if ($this->session->userdata('role') == 'superadmin'): ?>
                        <a href="javascript:void(0);" class="custom-add-btn"
                            style="margin-bottom: 15px; display: inline-block;" onclick="openAddSectionModal()">
                            <i class="bx bx-plus"></i> አዲስ የትምህርት ክፍል ያክሉ
                        </a>
                        <?php endif; ?>
                        

                        <table>
                            <thead>
                                <tr>
                                    
                                    <th>የትምህርት ክፍል</th>

                                    <!-- only superadmins has the previlege -->
                                    <?php if ($this->session->userdata('role') == 'superadmin'): ?>
                                    <th>መፈጸሚያዎች</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($sections)): ?>
                                    <?php foreach ($sections as $section): ?>
                                        <tr>
                                           
                                            <td><?= $section['name'] ?></td>

                                            <!-- only superadmins has the previlege -->
                                    <?php if ($this->session->userdata('role') == 'superadmin'): ?>
                                            <td>
                                                <a href="javascript:void(0);" class="edit-btn"
                                                    onclick="openSectionModal(<?= $section['id'] ?>, '<?= $section['name'] ?>')">
                                                    አርትዕ
                                                </a> |
                                                <a href="<?= site_url('academic/delete_section/' . $section['id']) ?>"
                                                    onclick="return confirm('እርግጠኛ ነዎት ይሄን የትምህርት ክፍል መሰረዝ ይፈልጋሉ?')"
                                                    class="delete-btn">
                                                    ሰርዝ
                                                </a>
                                            </td>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="100%" style="text-align: center;">
                                            <h2>ምንም የትምህርት ክፍል አልተገኘም.</h2>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <br>
                      <div class="table-container">

                        <!-- only superadmins has the previlege -->
                        <?php if ($this->session->userdata('role') == 'superadmin'): ?>
                            <!-- add new deartment button-->
                            <a href="javascript:void(0);" class="custom-add-btn"
                                style="margin-bottom: 15px; display: inline-block;" onclick="openAddDepartmentModal()">
                                <i class="bx bx-plus"></i> አዲስ የስራ ክፍል ያክሉ
                            </a>
                        <?php endif; ?>

                        <table>
                            <thead>
                                <tr>
                                    
                                    <th>የስራ ክፍል</th>

                                    <!-- only superadmins has the previlege -->
                                    <?php if ($this->session->userdata('role') == 'superadmin'): ?>
                                        <th>መፈጸሚያዎች</th>
                                    <?php endif; ?>

                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($departments)): ?>
                                    <?php foreach ($departments as $department): ?>
                                        <tr>
                                           
                                            <td><?= $department['name'] ?></td>


                                            <!-- only superadmins has the previlege -->
                                            <?php if ($this->session->userdata('role') == 'superadmin'): ?>
                                                <td>
                                                    <!-- Edit Department Button -->
                                                    <a href="javascript:void(0);" class="edit-btn"
                                                        onclick="openDepartmentModal(<?= $department['id'] ?>, '<?= $department['name'] ?>')">
                                                        አርትዕ
                                                    </a> |
                                                    <a href="<?= site_url('academic/delete_department/' . $department['id']) ?>"
                                                        onclick="return confirm('እርግጠኛ ነዎት ይሄን የስራ ክፍል መሰረዝ ይፈልጋሉ?')"
                                                        class="delete-btn">
                                                        ሰርዝ
                                                    </a>
                                                </td>
                                            <?php endif; ?>

                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="100%" style="text-align: center;">
                                            <h2>ምንም የስራ ክፍል አልተገኘም.</h2>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>


                      <div class="table-container">

                        <!-- only superadmins has the previlege -->
                        <?php if ($this->session->userdata('role') == 'superadmin'): ?>
                            <!-- add new deartment button-->
                            <a href="javascript:void(0);" class="custom-add-btn"
                                style="margin-bottom: 15px; display: inline-block;" onclick="openAddDepartmentModal()">
                                <i class="bx bx-plus"></i> አዲስ የስራ ክፍል ያክሉ
                            </a>
                        <?php endif; ?>

                        <table>
                            <thead>
                                <tr>
                                 
                                    <th>የስራ ክፍል</th>

                                    <!-- only superadmins has the previlege -->
                                    <?php if ($this->session->userdata('role') == 'superadmin'): ?>
                                        <th>መፈጸሚያዎች</th>
                                    <?php endif; ?>

                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($departments)): ?>
                                    <?php foreach ($departments as $department): ?>
                                        <tr>
                                           
                                            <td><?= $department['name'] ?></td>


                                            <!-- only superadmins has the previlege -->
                                            <?php if ($this->session->userdata('role') == 'superadmin'): ?>
                                                <td>
                                                    <!-- Edit Department Button -->
                                                    <a href="javascript:void(0);" class="edit-btn"
                                                        onclick="openDepartmentModal(<?= $department['id'] ?>, '<?= $department['name'] ?>')">
                                                        አርትዕ
                                                    </a> |
                                                    <a href="<?= site_url('academic/delete_department/' . $department['id']) ?>"
                                                        onclick="return confirm('እርግጠኛ ነዎት ይሄን የስራ ክፍል መሰረዝ ይፈልጋሉ?')"
                                                        class="delete-btn">
                                                        ሰርዝ
                                                    </a>
                                                </td>
                                            <?php endif; ?>

                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="100%" style="text-align: center;">
                                            <h2>ምንም የስራ ክፍል አልተገኘም.</h2>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- add modal-->
                    <!-- Add Department Modal -->
                    <div id="addDepartmentModal" class="modal">
                        <div class="modal-content">
                            <span class="close-btn" onclick="closeModal()">&times;</span>
                            <h2>አዲስ የስራ ክፍል ያክሉ</h2>
                            <form id="addDepartmentForm" method="post"
                                action="<?= site_url('academic/save_department'); ?>">
                                <label for="name">የስራ ክፍሉ ስም:</label>
                                <input type="text" name="name" id="addDepartmentName" class="schedule-input" required>
                                <button type="submit" class="schedule-button">አክል</button>
                            </form>
                        </div>
                    </div>

                    <!-- Add Section Modal -->
                    <div id="addSectionModal" class="modal">
                        <div class="modal-content">
                            <span class="close-btn" onclick="closeModal()">&times;</span>
                            <h2>አዲስ የትምህርት ክፍል ያክሉ</h2>
                            <form id="addSectionForm" method="post" action="<?= site_url('academic/save_section'); ?>">
                                <label for="name">የትምህርት ክፍሉ ስም:</label>
                                <input type="text" name="name" id="addSectionName" class="schedule-input" required>
                                <button type="submit" class="schedule-button">አክል</button>
                            </form>
                        </div>
                    </div>

                    <!-- Edit Modal -->
                    <!-- Edit Department Modal -->
                    <div id="editDepartmentModal" class="modal">
                        <div class="modal-content">
                            <span class="close-btn" onclick="closeModal()">&times;</span>
                            <h2>የስራ ክፍል ማስተካከያ</h2>
                            <form id="editDepartmentForm" method="post"
                                action="<?= site_url('academic/update_department'); ?>/"
                                onsubmit="this.action += document.getElementById('editDepartmentId').value;">
                                <input type="hidden" name="id" id="editDepartmentId">
                                <label for="name">የስራ:</label>
                                <input type="text" name="name" id="editDepartmentName" class="schedule-input" required>
                                <button type="submit" class="schedule-button">አርትዕ</button>
                            </form>
                        </div>
                    </div>

                    <!-- Edit Section Modal -->
                    <div id="editSectionModal" class="modal">
                        <div class="modal-content">
                            <span class="close-btn" onclick="closeModal()">&times;</span>
                            <h2>የስራ ክፍል ማስተካከያ</h2>
                            <form id="editSectionForm" method="post"
                                action="<?= site_url('academic/update_section'); ?>/"
                                onsubmit="this.action += document.getElementById('editSectionId').value;">
                                <input type="hidden" name="id" id="editSectionId">
                                <label for="name">የስራ:</label>
                                <input type="text" name="name" id="editSectionName" class="schedule-input" required>
                                <button type="submit" class="schedule-button">አርትዕ</button>
                            </form>
                        </div>
                    </div>

                </div>
            </div>

        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

    <script src="<?= base_url('assets/js/script.js'); ?>"></script>
    <script>
        // Function to open the add department modal
        function openAddDepartmentModal() {
            document.getElementById('addDepartmentModal').style.display = "flex";
        }

        // Function to open the add section modal
        function openAddSectionModal() {
            document.getElementById('addSectionModal').style.display = "flex";
        }

        // Function to open the department modal
        function openDepartmentModal(id, name) {
            document.getElementById('editDepartmentId').value = id; // Set the ID
            document.getElementById('editDepartmentName').value = name; // Set the name
            document.getElementById('editDepartmentModal').style.display = "flex"; // Show the modal
        }

        // Function to open the section modal
        function openSectionModal(id, name) {
            document.getElementById('editSectionId').value = id; // Set the ID
            document.getElementById('editSectionName').value = name; // Set the name
            document.getElementById('editSectionModal').style.display = "flex"; // Show the modal
        }

        // Function to close modal
        function closeModal() {
            // Hide all modals
            document.getElementById('addDepartmentModal').style.display = "none";
            document.getElementById('addSectionModal').style.display = "none";
            document.getElementById('editDepartmentModal').style.display = "none";
            document.getElementById('editSectionModal').style.display = "none";
        }
        // Close modal if user clicks outside the modal content
        window.onclick = function(event) {
            const modals = ['addDepartmentModal', 'addSectionModal', 'editDepartmentModal', 'editSectionModal'];
            modals.forEach(modalId => {
                const modal = document.getElementById(modalId);
                if (event.target === modal) {
                    closeModal();
                }
            });
        }

        // Ensure modals are hidden on page load
        document.addEventListener("DOMContentLoaded", function() {
            closeModal();
        });
    </script>
</body>

</html>