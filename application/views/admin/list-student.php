<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon -->
    <link rel="icon" href="<?php echo base_url('assets/images/favicon.ico'); ?>" type="image/x-icon">
    <title>የተማሪዎች ዝርዝር | ሰንበት አቴንዳንስ ሲስተም </title>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/icon.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/id_card.css'); ?>">
    <link rel="stylesheet" type="text/css"
        href="<?php echo base_url('assets/icon/simple-line-icons/css/simple-line-icons.css') ?>">

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
                    <h2>የተማሪዎች ዝርዝር ገጽ</h2>

                    <ul class="breadcrumb">
                        <li> <a href="<?php echo base_url('dashboard'); ?>">ዳሽቦርድ</a> </li>
                        <li><i class='bx bx-chevron-right'></i></li>
                        <li> <a class="active" href="<?php echo base_url('student/list'); ?>">የተማሪዎች ዝርዝር</a> </li>
                    </ul>
                </div>

                <!-- error and success message-->
                <?php if ($this->session->flashdata('student_message')): ?>
                    <div id="flash-message"
                        class="message-box <?php echo $this->session->flashdata('student_message')['type']; ?>">
                        <?php echo $this->session->flashdata('student_message')['text']; ?>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('qr_message')): ?>
                    <div id="flash-message"
                        class="message-box <?php echo $this->session->flashdata('qr_message')['type']; ?>">
                        <?php echo $this->session->flashdata('qr_message')['text']; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="table-container">
                <div class="order">

                    <!-- Search Form -->
                    <!-- Pagination & Rows per Page -->
                    <form class="list-search-form" method="get">
                        <select name="limit" id="limit" onchange="this.form.submit()">
                            <?php foreach ([10, 25, 50, 100] as $l): ?>
                                <option value="<?= $l ?>" <?= $l == $per_page ? 'selected' : '' ?>><?= $l ?></option>
                            <?php endforeach; ?>
                        </select>

                        <label for="limit">ዝርዝሮች አሳይ</label>
                        <input type="text" name="search" placeholder="ቁልፍ ቃላቶችን እዚህ ይተይቡ..."
                            value="<?= htmlspecialchars($search ?? '') ?>">
                        <button type="submit">ፈልግ</button>
                    </form>

                    <!-- add new students button-->
                    <a href="<?= base_url('student/add_personal_info') ?>" class="custom-add-btn">
                        <i class="bx bx-plus"></i> አዲስ ያክሉ
                    </a>

                    <!-- can only be accessed by superadmins -->
                    <?php if ($this->session->userdata('role') == 'superadmin'): ?>
                        <a href="<?= base_url('student/export_students') ?>"
                            style="margin-left: 2px; display: inline-flex; align-items: center; background-color: #007BFF; color: #ffff; font-size: 14px; font-weight: bold; padding: 8px 15px; border: none; border-radius: 5px; cursor: pointer; text-decoration: none; transition: background 0.3s ease;"
                            onmouseover="this.style.backgroundColor='#0056b3'"
                            onmouseout="this.style.backgroundColor='#007BFF'">
                            <i class="bx bx-download" style="margin-right: 5px; font-size: 13px;"></i> የተማሪ ዝርዝር ያውርዱ
                        </a>
                    <?php endif ?>

                    <table class="table-container-table">
                        <thead>
                            <tr>
                                <th><a href="<?= site_url('student/list?sort_by=id&sort_order=' . (($sort_by == 'id' && $sort_order == 'asc') ? 'desc' : 'asc')); ?>"
                                        style="color: white;">መ. ቁ.
                                        <?= ($sort_by == 'id' && $sort_order == 'asc') ? '▲' : '▼'; ?></a></th>
                                <th><a href="<?= site_url('student/list?sort_by=fname&sort_order=' . (($sort_by == 'fname' && $sort_order == 'asc') ? 'desc' : 'asc')); ?>"
                                        style="color: white;">ሙሉ ስም
                                        <?= ($sort_by == 'fname' && $sort_order == 'asc') ? '▲' : '▼'; ?></a></th>
                                <th><a href="<?= site_url('student/list?sort_by=sex&sort_order=' . (($sort_by == 'sex' && $sort_order == 'asc') ? 'desc' : 'asc')); ?>"
                                        style="color: white;">ጾታ
                                        <?= ($sort_by == 'sex' && $sort_order == 'asc') ? '▲' : '▼'; ?></a></th>
                                <th><a style="color: white;">የሐዋሪያዊ ምድብ </a></th>
                                <th><a style="color: white;">የእድሜ ክፍል </a></th>
                                <th><a style="color: white;">የስርአተ ት/ክፍል </a></th>

                                <th><a style="color: white;">የአገልግሎት ክፍል </a></th>
                                <th><a style="color: white;">የመዝሙር ክፍል </a></th>
                            
                                <th>መፈጸሚያዎች</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php if (!empty($students)): ?>
                                <?php foreach ($students as $student): ?>
                                    <tr>
                                        <td><?= $student['student_id']; ?></td>
                                        <td><?= $student['fname'] . ' ' . $student['mname'] . ' ' . $student['lname']; ?></td>
                                        <td><?= $student['sex_amharic']; ?></td>
                                         <td><?php echo !empty($student['apostolic_name']) ? $student['apostolic_name'] : '-'; ?></td>
                                         <td><?php echo !empty($student['age_category_name']) ? $student['age_category_name'] : '-'; ?></td>
                                        <td><?php echo !empty($student['curriculum_name']) ? $student['curriculum_name'] : '-'; ?></td>
                                        <td><?php echo !empty($student['department_name']) ? $student['department_name'] : '-'; ?></td>
                                        <td><?php echo !empty($student['choir_name']) ? $student['choir_name'] : '-'; ?></td>
                                        <td>

                                            <a href="<?= base_url('student/view/' . $student['id']) ?>"
                                                class="btn btn-info btn-sm">
                                                <i class='bx bx-show'></i>
                                            </a>

                                            <a href="<?= base_url('student/edit/' . $student['id']) ?>"
                                                class="btn btn-primary btn-sm">
                                                <i class='bx bx-edit'></i>
                                            </a>

                                            <!-- can only be accessed by superadmins -->
                                            <?php if ($this->session->userdata('role') == 'superadmin'): ?>
                                                <a href="<?= base_url('student/deactivate_student/' . $student['id']) ?>"
                                                    class="btn btn-danger confirm-delete"
                                                    onclick="return confirm('እርግጠኛ ነዎት የዚህን ተማሪ ትምህርት ማቋረጥ ይፈልጋሉ?');">
                                                    <i class='bx bx-power-off'> </i>
                                                </a>

                
                                                <a href="javascript:void(0);" class="btn btn-primary btn-sm"
                                                    onclick="openModal('<?= $student['id']; ?>')">
                                                    መታወቂያ</a>
                                            <?php endif; ?>

                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="100%" style="text-align: center; ">
                                        <h2>ምንም ተማሪ አልተገኘም።</h2>
                                    </td>
                                </tr>

                            <?php endif; ?>
                        </tbody>
                    </table>

                    <!-- Pagination -->
                    <ul class="pagination">
                        <?php
                        $total_pages = ceil($total_rows / $limit);
                        for ($i = 1; $i <= $total_pages; $i++):
                            $new_offset = ($i - 1) * $limit;
                        ?>
                            <li <?= $i == $page ? 'class="active"' : '' ?>>
                                <a
                                    href="?offset=<?= $new_offset ?>&limit=<?= $limit ?>&search=<?= urlencode($search ?? '') ?>&sort_by=<?= $sort_by ?>&sort_order=<?= $sort_order ?>">
                                    <?= $i ?>
                                </a>
                            </li>
                        <?php endfor; ?>
                    </ul>

                </div>
            </div>
        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->


    <!-- Modal -->
    <div id="idCardModal" class="modal">
        <div class="modal-content">
            <span class="close-btn" onclick="closeModal()">&times;</span>
            <h2>መታወቂያ ካርድ</h2>
            <div id="idCardContent"></div>
            <button onclick="printIdCard()">አትም</button>
        </div>
    </div>

    <script>
        function openModal(student_id) {
            fetch(`<?= site_url('student/generate_id/'); ?>/${student_id}`)
                .then(response => response.text())
                .then(html => {
                    document.getElementById('idCardContent').innerHTML = html;
                    document.getElementById('idCardModal').style.display = 'flex';
                });
        }

        function closeModal() {
            document.getElementById('idCardModal').style.display = 'none';
        }

        function printIdCard() {
            const printContent = document.getElementById('idCardContent').innerHTML;
            const originalContent = document.body.innerHTML;
            document.body.innerHTML = printContent;
            window.print();
            document.body.innerHTML = originalContent;
            location.reload(); // Reload to restore the original page
        }

        // Close modal if user clicks outside the modal content
        window.onclick = function(event) {
            const modal = document.getElementById('idCardModal');
            if (event.target === modal) {
                closeModal();
            }
        }
    </script>
</body>
<script src="<?php echo base_url('assets/js/script.js'); ?>"></script>

</html>