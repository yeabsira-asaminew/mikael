<div class="id-card">
  <!-- Watermark Logo -->
  <img src="<?= base_url('assets/images/logo.png'); ?>" alt="Watermark Logo" class="watermark-logo">

  <!-- Header -->
  <div class="id-card-header">
    <img src="<?= base_url('assets/images/logo.png'); ?>" alt="Company Logo" class="logo">
    <h1 class="company-name"> ደብረ ምህረት ቅዱስ ሚካኤል ዐምደ ሀይማኖት ሰ/ት/ቤት</h1>
  </div>

  <!-- Content -->
  <div class="id-card-content">
    <!-- QR Code with Date -->
    <div class="qr-code">
      <img src="<?php echo base_url('uploads/qr_codes/') . $student['qr_code']; ?>"
        alt="<?php echo "የተማሪ " .  $student['fname'] . "QR Code"; ?>">
      <div class="generated-date">የታተመበት ቀን <?= $ethiopian_current_date; ?></div>
    </div>

    <!-- Student Details -->
    <div class="id-card-details">
      <p><strong>መታወቂያ ቁ.፡</strong> <span id="student-id"><?= $student['student_id']; ?></span></p>
      <p><strong>ሙሉ ስም:</strong> <span id="full-name"><?= $student['fname'] . ' ' . $student['mname'] . ' ' . $student['lname']; ?></span></p>
      <p><strong>ጾታ:</strong> <span id="department"><?= $student['sex_amharic']; ?></span></p>
      <p><strong>ስልክ ቁ.:</strong> <span id="phone1"><?= $student['phone1']; ?></span></p>
      <?php if (!empty($student['phone2'])) : ?>
        <p><strong>ስልክ ቁ.:</strong> <span id="phone2"><?= $student['phone2']; ?></span></p>
      <?php else : ?>
      <?php endif; ?>
      <p><strong>አድራሻ:</strong> <span id="address"><?= $student['address']; ?></span></p>
      <p><strong>የእድሜ ክፍል:</strong> <span id='age_category_name'><?= $student['age_category_name']; ?></span></p>

      <?php if (!empty($student['apostolic_name'])) : ?>
        <p><strong>የሐዋርያዊ ክፍል:</strong> <span id="apostolic_name"><?= $student['apostolic_name']; ?></span></p>
      <?php else : ?>
      <?php endif; ?>

      <?php if (!empty($student['department_name'])) : ?>
        <p><strong>የአገልግሎት ክፍል:</strong> <span id='department_name'><?= $student['department_name']; ?></span></p>
      <?php else : ?>
      <?php endif; ?>

      <p><strong>የምዝገባ ቀን:</strong> <span id="registration_date"><?= $registration_date_in_ethiopian_calendar; ?></span></p>
    </div>
  </div>
</div>