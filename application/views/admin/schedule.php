<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon -->
    <link rel="icon" href="<?php echo base_url('assets/images/favicon.ico'); ?>" type="image/x-icon">
    <title>መርሐግብሮች | ሰንበት አቴንዳንስ ሲስተም</title>

    <!-- Boxicons -->
    <link href="https://unpkg.com/boxicons@2.0.9/css/boxicons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="<?= base_url('assets/css/style.css'); ?>">
    <link rel="stylesheet" href="<?= base_url('assets/css/schedule.css'); ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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
                    <h2>መርሐግብሮች መፍጠሪያና ማስተካከያ</h2>

                    <ul class="breadcrumb">
                        <li><a href="<?php echo base_url('dashboard'); ?>">ዳሽቦርድ</a></li>
                        <li><i class="bx bx-chevron-right"></i></li>
                        <li><a class="active" href="<?php echo base_url('academic/schedule'); ?>">መርሐግብሮች</a></li>
                    </ul>
                </div>

                <?php if ($this->session->flashdata('academic_message')): ?>
                    <div id="flash-message" class="message-box <?php echo $this->session->flashdata('academic_message')['type']; ?>">
                        <?php echo $this->session->flashdata('academic_message')['text']; ?>
                    </div>
                <?php endif; ?>
            </div>

            <div class="unique-form-container">
                <div class="schedule-container">

                    <!-- Add Schedule Form -->
                    <?php if ($this->session->userdata('role') == 'superadmin'): ?>
                        <div class="form-container">
                            <form method="post" action="<?= site_url('academic/add_schedule'); ?>" class="schedule-form">
                                <label for="day">ቀን:</label>
                                <select name="day" id="day" class="schedule-select">
                                    <option value="Monday">ሰኞ</option>
                                    <option value="Tuesday">ማክሰኞ</option>
                                    <option value="Wednesday">ረቡዕ</option>
                                    <option value="Thursday">ሐሙስ</option>
                                    <option value="Friday">አርብ</option>
                                    <option value="Saturday">ቅዳሜ</option>
                                    <option value="Sunday">እሁድ</option>
                                </select>

                                <label for="time">ሰዓት:</label>
                                <input type="time" name="time" id="time" class="schedule-input" required>

                                <label for="category"> ምድቦች:</label>
                                <div class="unique-form-group">
                                    <select name="category" id="category" class="schedule-select" required>
                                        <option value="">--ምረጥ--</option>
                                        <?php foreach ($categories as $category): ?>
                                            <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>

                                <div id="sub_category_container" class="subcategory-container" style="display: none;">
                                    <!-- Subcategories will be loaded here via AJAX as dropdown with checkboxes -->
                                </div>


                                <label for="description">ገለጻ:</label>
                                <textarea name="description" id="description" class="schedule-input" rows="2"></textarea>

                                <button type="submit" class="schedule-button">መርሐግብር መዝግብ</button>
                            </form>
                        </div>
                    <?php endif; ?>

                    <!-- Schedule List -->
                    <div class="table-container">
                        <table>
                            <thead>
                                <tr>
                                    <th>ቁጥር</th>
                                    <th>ቀን</th>
                                    <th>ሰዓት</th>
                                    <th>የት/ክፍል</th>
                                    <th>ገለጻ</th>

                                    <?php if ($this->session->userdata('role') == 'superadmin'): ?>
                                        <th>መፈጸሚያዎች</th>
                                    <?php endif; ?>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($schedules)): ?>
                                    <?php
                                    // Mapping array for English to Amharic day names
                                    $dayTranslations = [
                                        "Monday" => "ሰኞ",
                                        "Tuesday" => "ማክሰኞ",
                                        "Wednesday" => "ረቡዕ",
                                        "Thursday" => "ሐሙስ",
                                        "Friday" => "አርብ",
                                        "Saturday" => "ቅዳሜ",
                                        "Sunday" => "እሁድ"
                                    ];

                                    foreach ($schedules as $schedule): ?>
                                        <tr>
                                            <td><?= $schedule['id'] ?></td>
                                            <td><?= $dayTranslations[$schedule['day']] ?? $schedule['day'] ?></td>
                                            <td><?= $schedule['time'] ?></td>
                                            <td><?= $schedule['sub_categories'] ?></td>
                                            <td><?= $schedule['description'] ?></td>

                                            <!-- only superadmins has the previlege -->
                                            <?php if ($this->session->userdata('role') == 'superadmin'): ?>
                                                <td>
                                                    <a href="javascript:void(0);" class="edit-btn"
                                                        onclick="openModal(<?= $schedule['id'] ?>, '<?= $schedule['day'] ?>', '<?= date('H:i', strtotime($schedule['time']) - 6 * 3600) ?>', '<?= $schedule['description'] ?>')">
                                                        አርትዕ
                                                    </a> |
                                                    <a href="<?= site_url('academic/delete_schedule/' . $schedule['id']) ?>"
                                                        onclick="return confirm('እርግጠኛ ነዎት ይሄን መርሐግብር መሰረዝ ይፈልጋሉ?')"
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
                                            <h2>ምንም መርሐግብር አልተገኘም</h2>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Edit Modal -->
                    <div id="editModal" class="modal">
                        <div class="modal-content">
                            <span class="close-btn" onclick="closeModal()">&times;</span>
                            <h2>መርሐግብር ማስተካከያ</h2>

                            <form id="editForm" method="post" action="<?= site_url('academic/update_schedule'); ?>/"
                                onsubmit="this.action += document.getElementById('editId').value;">
                                <input type="hidden" name="id" id="editId">

                                <label for="editDay">ቀን:</label>
                                <select name="day" id="editDay" class="schedule-select">
                                    <option value="Monday">ሰኞ</option>
                                    <option value="Tuesday">ማክሰኞ</option>
                                    <option value="Wednesday">ረቡዕ</option>
                                    <option value="Thursday">ሐሙስ</option>
                                    <option value="Friday">አርብ</option>
                                    <option value="Saturday">ቅዳሜ</option>
                                    <option value="Sunday">እሁድ</option>
                                </select>

                                <label for="editTime">ሰዓት:</label>
                                <input type="time" name="time" id="editTime" class="schedule-input" required>

                                <label for="editCategory"> ምድቦች:</label>
                                <select name="category" id="editCategory" class="schedule-select" required>
                                    <option value="">--ምረጥ--</option>
                                    <?php foreach ($categories as $category): ?>
                                        <option value="<?php echo $category->id; ?>"><?php echo $category->name; ?></option>
                                    <?php endforeach ?>
                                </select>

                                <div id="edit_sub_category_container" class="subcategory-container">
                                    <!-- Subcategories will be loaded here via AJAX as dropdown with checkboxes -->
                                </div>


                                <label for="editDescription">ገለጻ:</label>
                                <textarea name="description" id="editDescription" class="schedule-input" rows="2"></textarea>

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
        // Load subcategories when category changes (for add form)
        $(document).ready(function() {
            // Hide modal on page load
            $('#editModal').hide();

            $('#category').change(function() {
                var category_id = $(this).val();
                $('#sub_category_container').hide();

                if (category_id) {
                    $.ajax({
                        url: '<?= base_url("academic/get_sub_categories"); ?>',
                        type: 'POST',
                        data: {
                            category_id: category_id
                        },
                        dataType: 'json',
                        success: function(data) {
                            var container = $('#sub_category_container');
                            container.empty();

                            if (data.length > 0) {
                                container.append('<div class="subcategory-dropdown">');
                                container.append('<button type="button" class="dropdown-toggle"><span class="dropdown-text">ክፍላት ምረጥ</span> <span class="arrow">▼</span></button>');
                                container.append('<div class="dropdown-options">');

                                $.each(data, function(index, sub_category) {
                                    container.find('.dropdown-options').append(
                                        '<label class="schedule-checkbox">' +
                                        '<input type="checkbox" name="sub_categories[]" value="' + sub_category.id + '">' +
                                        '<span class="checkmark"></span>' + sub_category.name +
                                        '</label>'
                                    );
                                });

                                container.append('</div></div>');
                                container.show();

                                // Toggle dropdown
                                $('.dropdown-toggle').click(function() {
                                    $(this).siblings('.dropdown-options').toggle();
                                    $(this).find('.arrow').text(function(_, text) {
                                        return text === '▼' ? '▲' : '▼';
                                    });
                                    $(this).toggleClass('active');
                                });
                            } else {
                                container.hide();
                            }
                        }
                    });
                } else {
                    $('#sub_category_container').empty().hide();
                }
            });
        });

        // Function to open edit modal
        function openModal(id, day, time, description) {
            $('#editId').val(id);
            $('#editDay').val(day);
            $('#editTime').val(time);
            $('#editDescription').val(description);

            // Load subcategories for the schedule
            $.ajax({
                url: '<?= base_url("academic/get_sub_categories_for_schedule"); ?>',
                type: 'POST',
                data: {
                    schedule_id: id
                },
                dataType: 'json',
                success: function(response) {
                    // First, get all categories and subcategories
                    $.ajax({
                        url: '<?= base_url("academic/get_categories"); ?>',
                        type: 'GET',
                        dataType: 'json',
                        success: function(categories) {
                            var categorySelect = $('#editCategory');
                            categorySelect.empty().append('<option value="">--ምረጥ--</option>');

                            // Populate categories dropdown
                            $.each(categories, function(index, category) {
                                categorySelect.append(
                                    '<option value="' + category.id + '">' + category.name + '</option>'
                                );
                            });

                            // If schedule has subcategories, select the first one's category
                            if (response.length > 0) {
                                var firstCategoryId = response[0].category_id;
                                categorySelect.val(firstCategoryId).trigger('change');

                                // After category is selected, check the appropriate subcategories
                                setTimeout(function() {
                                    $.each(response, function(index, subcat) {
                                        $('#edit_sub_category_container input[value="' + subcat.id + '"]').prop('checked', true);
                                    });
                                }, 500);
                            }
                        }
                    });
                }
            });

            // Show the modal
            $('#editModal').css('display', 'flex');
        }

        // Function to close modal
        function closeModal() {
            $('#editModal').hide();
        }

        // Close modal if user clicks outside the modal content
        $(document).mouseup(function(e) {
            var modal = $('#editModal');
            if (!modal.is(e.target) && modal.has(e.target).length === 0) {
                closeModal();
            }
        });

        // Load subcategories when category changes (for edit form)
        $('#editCategory').change(function() {
            var category_id = $(this).val();
            var container = $('#edit_sub_category_container');
            container.empty();

            if (category_id) {
                $.ajax({
                    url: '<?= base_url("academic/get_sub_categories"); ?>',
                    type: 'POST',
                    data: {
                        category_id: category_id
                    },
                    dataType: 'json',
                    success: function(data) {
                        if (data.length > 0) {
                            container.append('<div class="subcategory-dropdown">');
                            container.append('<button type="button" class="dropdown-toggle"><span class="dropdown-text">ክፍላት ምረጥ</span> <span class="arrow">▼</span></button>');
                            container.append('<div class="dropdown-options">');

                            $.each(data, function(index, sub_category) {
                                container.find('.dropdown-options').append(
                                    '<label class="schedule-checkbox">' +
                                    '<input type="checkbox" name="sub_categories[]" value="' + sub_category.id + '">' +
                                    '<span class="checkmark"></span>' + sub_category.name +
                                    '</label>'
                                );
                            });

                            container.append('</div></div>');

                            // Toggle dropdown
                            $('.dropdown-toggle').click(function() {
                                $(this).siblings('.dropdown-options').toggle();
                                $(this).find('.arrow').text(function(_, text) {
                                    return text === '▼' ? '▲' : '▼';
                                });
                                $(this).toggleClass('active');
                            });
                        } else {
                            container.append('<p>No subcategories found</p>');
                        }
                    }
                });
            }
        });
    </script>
</body>

</html>