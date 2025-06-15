<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon -->
    <link rel="icon" href="<?php echo base_url('assets/images/favicon.ico'); ?>" type="image/x-icon">
    <title>ንቁ ያልሆኑ ተማሪዎች ዝርዝር | ሰንበት አቴንዳንስ ሲስተም </title>
    <!-- Boxicons -->
    <link href='https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css' rel='stylesheet'>
    <!-- My CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/icon.css'); ?>">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url('assets/icon/simple-line-icons/css/simple-line-icons.css') ?>">

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
                    <h2>ንቁ ያልሆኑ ተማሪዎች ዝርዝር ገጽ</h2>

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
                                        <td><?= $student['apostolic_name']; ?></td>
                                        <td><?= $student['age_category_name']; ?></td>
                                        <td><?= $student['curriculum_name']; ?></td>
                                        <td><?= $student['department_name']; ?></td>
                                        <td><?= $student['choir_name']; ?></td>
                                        <td>

                                            <a href="<?= base_url('student/view/' . $student['id']) ?>"
                                                class="btn btn-info btn-sm">
                                                <i class='bx bx-show'></i> <!-- Updated icon for view -->
                                            </a>
                                            <a href="<?= base_url('student/activate_student/' . $student['id']) ?>"
                                                class="btn btn-primary confirm-delete"
                                                onclick="return confirm('እርግጠኛ ነዎት ይህን ተማሪ ማንቃት ይፈልጋሉ?');">
                                                <i class='bx bx-power-off'> አንቃ</i>
                                            </a>

                                            <a href="<?= base_url('student/delete/' . $student['id']) ?>"
                                                class="btn btn-primary confirm-delete"
                                                onclick="return confirm('እርግጠኛ ነዎት ይህን ተማሪ መሰረዝ ይፈልጋሉ?');">
                                                <i class='bx bx-power-off'> ሰርዝ</i>
                                            </a>

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

</body>
<script src="<?php echo base_url('assets/js/script.js'); ?>"></script>

</html>