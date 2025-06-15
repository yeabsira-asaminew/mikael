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
   
            <style>
    .disabled-field {
        opacity: 0.6;
        pointer-events: none;
    }

    .hidden-field {
        display: none;
    }

    .disabled-field input,
    .disabled-field select {
        background-color: #f5f5f5;
        border-color: #ddd;
        color: #777;
    }

    .btn-primary {
        background-color: #007bff;
        color: #fff;
        border: none;
        padding: 10px 20px;
        font-size: 14px;
        border-radius: 4px;
        cursor: pointer;
        margin: 10px auto;
        display: block;
    }
</style>
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



<?php echo form_open_multipart("aca/update/" . $student['id']); ?>
    <div class="unique-form-grid">
        <div class="unique-form-group">
            <label for="fname">ስም <span class="required">*</span></label>
            <input type="text" name="fname" id="fname" value="<?php echo set_value('fname', $student['fname']); ?>" required>
        </div>

        <div class="unique-form-group">
            <label for="mname">የአባት ስም <span class="required">*</span></label>
            <input type="text" id="mname" name="mname" value="<?php echo set_value('mname', $student['mname']); ?>" required>
        </div>

        <div class="unique-form-group">
            <label for="lname">የአያት ስም <span class="required">*</span></label>
            <input type="text" id="lname" name="lname" value="<?php echo set_value('lname', $student['lname']); ?>" required>
        </div>

        <div class="unique-form-group">
            <label for="mother_name">የእናት ሙሉ ስም <span class="required">*</span></label>
            <input type="text" id="mother_name" name="mother_name" value="<?php echo set_value('mother_name', $student['mother_name']); ?>" required>
        </div>

        <div class="unique-form-group">
            <label for="sex">ጾታ <span class="required">*</span></label>
            <select name="sex" id="sex">
                <option value="">--ምረጥ--</option>
                <option value="Male" <?php echo set_select('sex', 'Male', ($student['sex'] == 'Male')); ?>>ወንድ</option>
                <option value="Female" <?php echo set_select('sex', 'Female', ($student['sex'] == 'Female')); ?>>ሴት</option>
            </select>
        </div>

        <div class="unique-form-group">
            <label for="dob">የትውልድ ዘመን <span class="required">*</span></label>
            <input type="text" id="dob" name="dob" value="<?php echo set_value('dob', $student['dob_eth']); ?>" required>
        </div>

        <div class="unique-form-group">
            <label for="pob">የትውልድ ቦታ <span class="required">*</span></label>
            <input type="text" id="pob" name="pob" value="<?php echo set_value('pob', $student['pob']); ?>" required>
        </div>

        <div class="unique-form-group">
            <label for="christian_name">የክርስትና ስም <span class="required">*</span></label>
            <input type="text" id="christian_name" name="christian_name" value="<?php echo set_value('christian_name', $student['christian_name']); ?>" required>
        </div>

        <div class="unique-form-group">
            <label for="God_father">የክርስትና አባት ስም </label>
            <input type="text" id="God_father" name="God_father" value="<?php echo set_value('God_father', $student['God_father']); ?>">
        </div>

        <div class="unique-form-group">
            <label for="repentance_father">የንሰሃ አባት ስም <span class="required">*</span></label>
            <input type="text" id="repentance_father" name="repentance_father" value="<?php echo set_value('repentance_father', $student['repentance_father']); ?>" required>
        </div>

        <div class="unique-form-group">
            <label for="repentance_father_church">የንሰሃ አባት ደብር </label>
            <input type="text" id="repentance_father_church" name="repentance_father_church" value="<?php echo set_value('repentance_father_church', $student['repentance_father_church']); ?>">
        </div>

        <div class="unique-form-group">
            <label for="phone1">ስልክ ቁጥር <span class="required">*</span></label>
            <input type="tel" id="phone1" name="phone1" value="<?php echo set_value('phone1', $student['phone1']); ?>" required>
        </div>

        <div class="unique-form-group">
            <label for="phone2">ተጨማሪ ስልክ ቁጥር </label>
            <input type="tel" id="phone2" name="phone2" value="<?php echo set_value('phone2', $student['phone2']); ?>">
        </div>

        <div class="unique-form-group">
            <label for="address">የመኖሪያ አድራሻ <span class="required">*</span></label>
            <input type="text" id="address" name="address" value="<?php echo set_value('address', $student['address']); ?>" required>
        </div>

        <div class="unique-form-group">
            <label for="age_category_id">የእድሜ ክፍል <span class="required">*</span></label>
            <select name="age_category_id" id="age_category_id" required class="form-control">
                <option value="">-- ምረጥ --</option>
                <?php foreach ($age_categories as $category): ?>
                    <option value="<?php echo $category->id; ?>"
                        <?php echo set_select('age_category_id', $category->id, ($category->id == ($student['age_category_id'] ?? ''))); ?>>
                        <?php echo $category->name; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="unique-form-group" id="apostolic_group">
            <label for="apostolic_id">የሐዋርያዊ አገልግሎት ዓይነት <span class="required">*</span></label>
            <select name="apostolic_id" id="apostolic_id">
                <option value="">--ምረጥ--</option>
                <?php if ($show_apostolic && !empty($apostolic_categories)): ?>
                    <?php foreach ($apostolic_categories as $apostolic): ?>
                        <option value="<?php echo $apostolic->id; ?>"
                            <?php echo set_select('apostolic_id', $apostolic->id, ($apostolic->id == $student['apostolic_id'])); ?>>
                            <?php echo $apostolic->name; ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="unique-form-group" id="curriculum_group">
            <label for="curriculum_id">የትምህርት ስርዓት </label>
            <select name="curriculum_id" id="curriculum_id">
                <option value="">--ምረጥ--</option>
                <?php if (!empty($curriculums)): ?>
                    <?php foreach ($curriculums as $curriculum): ?>
                        <option value="<?php echo $curriculum->id; ?>"
                            <?php echo set_select('curriculum_id', $curriculum->id, ($curriculum->id == $student['curriculum_id'])); ?>>
                            <?php echo $curriculum->name; ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="unique-form-group" id="department_group">
            <label for="department_id">የአገልግሎት ክፍል <span class="required">*</span></label>
            <select name="department_id" id="department_id">
                <option value="">--ምረጥ--</option>
                <?php if ($show_service_dept && !empty($departments)): ?>
                    <?php foreach ($departments as $department): ?>
                        <option value="<?php echo $department->id; ?>"
                            <?php echo set_select('department_id', $department->id, ($department->id == $student['department_id'])); ?>>
                            <?php echo $department->name; ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="unique-form-group" id="choir_group">
            <label for="choir_id">የመዝሙር አገልግሎት</label>
            <select name="choir_id" id="choir_id">
                <?php if (!empty($choirs)): ?>
                    <?php foreach ($choirs as $choir): ?>
                        <option value="<?php echo $choir->id; ?>"
                            <?php echo set_select('choir_id', $choir->id, ($choir->id == $student['choir_id'])); ?>>
                            <?php echo $choir->name; ?>
                        </option>
                    <?php endforeach; ?>
                <?php endif; ?>
            </select>
        </div>

        <div class="unique-form-group">
            <label>ስራ/ትምህርት <span class="required">*</span></label>
            <select name="occupation" id="occupation" required>
                <option value="">--ምረጥ--</option>
                <?php if (!empty($occupations)): ?>
                    <?php foreach ($occupations as $occupation): ?>
                        <option value="<?= $occupation ?>"
                            <?= set_select('occupation', $occupation, (!empty($student['occupation']) && $student['occupation'] == $occupation)) ?>>
                            <?= ucfirst($occupation) ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option disabled>ምንም ስራ/ትምህርት አልተገኘም</option>
                <?php endif; ?>
            </select>
        </div>

        <div class="unique-form-group">
            <label>የትምህርት ደረጃ <span class="required">*</span></label>
            <select name="education_level" id="education_level" required>
                <option value="">--ምረጥ--</option>
                <?php if (!empty($education_levels)): ?>
                    <?php foreach ($education_levels as $education_level): ?>
                        <option value="<?= $education_level ?>"
                            <?= set_select('education_level', $education_level, (!empty($student['education_level']) && $student['education_level'] == $education_level)) ?>>
                            <?= ucfirst($education_level) ?>
                        </option>
                    <?php endforeach; ?>
                <?php else: ?>
                    <option disabled>ምንም የትምህርት ደረጃ አልተገኘም</option>
                <?php endif; ?>
            </select>
        </div>

        <div class="unique-form-group">
            <label for="academic_field">የትምህርት ዘርፍ </label>
            <input type="text" id="academic_field" name="academic_field" value="<?php echo set_value('academic_field', $student['academic_field']); ?>">
        </div>

        <div class="unique-form-group">
            <label for="workplace">የስራ ቦታ </label>
            <input type="text" id="workplace" name="workplace" value="<?php echo set_value('workplace', $student['workplace']); ?>">
        </div>

        <div class="unique-form-group">
            <label for="registration_date">የምዝገባ ቀን <span class="required">*</span></label>
            <input type="text" id="registration_date" name="registration_date" value="<?php echo set_value('registration_date', $student['registration_date_eth']); ?>" required>
        </div>

        <div class="unique-form-group">
            <label for="photo">የተማሪው ምስል(የተፈቀደው ከፍተኛ 5MB) (የአሁን ምስል:
                <?php echo $student['photo']; ?>): <span class="required">*</span></label>
            <input type="file" name="photo" id="photo">
        </div>

        <div class="unique-form-actions">
            <button type="submit" style="background-color: #007bff; color: #fff; border: none; padding: 10px 20px; font-size: 14px; border-radius: 4px; cursor: pointer; margin: 10px auto; display: block;">
                አርትዕ
            </button>
        </div>
    </div>
<?php echo form_close(); ?>

            </div>

        </main>
        <!-- MAIN -->
    </section>
    <!-- CONTENT -->

</body>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        // Initial state setup based on the current age category
        updateDependentFields($('#age_category_id').val(), true);

        $('#age_category_id').change(function() {
            var ageCategoryId = $(this).val();
            updateDependentFields(ageCategoryId, false); // Pass false for initial load
        });

        function updateDependentFields(ageCategoryId, isInitialLoad) {
            $.ajax({
                url: '<?php echo site_url("student/get_age_dependent_fields"); ?>',
                type: 'POST',
                data: {
                    age_category_id: ageCategoryId
                },
                dataType: 'json',
                success: function(response) {
                    // Handle Apostolic field
                    var apostolicGroup = $('#apostolic_group');
                    var apostolicSelect = $('#apostolic_id');
                    if (response.show_apostolic) {
                        apostolicGroup.removeClass('hidden-field').find('label').append(' <span class="required">*</span>');
                        apostolicSelect.prop('disabled', false).prop('required', true);
                        populateSelect(apostolicSelect, response.apostolic_categories, isInitialLoad ? '<?php echo $student['apostolic_id']; ?>' : '');
                    } else {
                        apostolicGroup.addClass('hidden-field').find('label .required').remove(); // Remove required asterisk
                        apostolicSelect.prop('disabled', true).prop('required', false).val('');
                        apostolicSelect.empty().append('<option value="">--ምረጥ--</option>'); // Clear options
                    }

                    // Handle Department field
                    var departmentGroup = $('#department_group');
                    var departmentSelect = $('#department_id');
                    if (response.show_service_dept) {
                        departmentGroup.removeClass('hidden-field').find('label').append(' <span class="required">*</span>');
                        departmentSelect.prop('disabled', false).prop('required', true);
                        populateSelect(departmentSelect, response.departments, isInitialLoad ? '<?php echo $student['department_id']; ?>' : '');
                    } else {
                        departmentGroup.addClass('hidden-field').find('label .required').remove(); // Remove required asterisk
                        departmentSelect.prop('disabled', true).prop('required', false).val('');
                        departmentSelect.empty().append('<option value="">--ምረጥ--</option>'); // Clear options
                    }

                    // Handle Curriculum field (always visible, but active/disabled)
                    var curriculumSelect = $('#curriculum_id');
                    if (response.curriculums && response.curriculums.length > 0) {
                        curriculumSelect.prop('disabled', false);
                        populateSelect(curriculumSelect, response.curriculums, isInitialLoad ? '<?php echo $student['curriculum_id']; ?>' : '');
                    } else {
                        curriculumSelect.prop('disabled', true).empty().append('<option value="" disabled>የለም</option>');
                    }

                    // Handle Choir field
                    var choirSelect = $('#choir_id');
                    if (response.choirs && response.choirs.length > 0 && response.choirs[0].id !== '') {
                        choirSelect.prop('disabled', false);
                        populateSelect(choirSelect, response.choirs, isInitialLoad ? '<?php echo $student['choir_id']; ?>' : '');
                    } else {
                        choirSelect.prop('disabled', true).empty().append('<option value="" disabled>የለም</option>');
                    }

                    // Apply disabled-field class to parent div for visual indication
                    $('#apostolic_group').toggleClass('disabled-field', apostolicSelect.prop('disabled'));
                    $('#department_group').toggleClass('disabled-field', departmentSelect.prop('disabled'));
                    $('#curriculum_group').toggleClass('disabled-field', curriculumSelect.prop('disabled'));
                    $('#choir_group').toggleClass('disabled-field', choirSelect.prop('disabled'));
                },
                error: function(xhr, status, error) {
                    console.error("AJAX error: " + status + " - " + error);
                    console.log(xhr.responseText); // Log the full error response
                }
            });
        }

        // Helper function to populate select dropdowns
        function populateSelect(selectElement, options, selectedValue) {
            selectElement.empty().append('<option value="">--ምረጥ--</option>');
            $.each(options, function(index, item) {
                var selected = (item.id == selectedValue) ? 'selected' : '';
                selectElement.append('<option value="' + item.id + '" ' + selected + '>' + item.name + '</option>');
            });
            // If the selectedValue is not in the new options and not empty, add it to the dropdown
            if (selectedValue && !selectElement.find('option[value="' + selectedValue + '"]').length) {
                // This scenario should be handled by the backend's get_categories_based_on_age,
                // but as a fallback/redundancy, you might load it here via another AJAX if needed.
                // For now, let's assume the backend provides the current selected item if it's valid.
            }
        }
    });
</script>
<script src="<?= base_url('assets/js/script.js'); ?>"></script>


</html>