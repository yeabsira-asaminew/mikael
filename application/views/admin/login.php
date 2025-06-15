<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <!-- favicon -->
   <link rel="icon" href="<?php echo base_url('assets/images/favicon.ico'); ?>" type="image/x-icon">
    <title>መግቢያ | ሰንበት አቴንዳንስ ሲስተም</title>
    <link rel="stylesheet" href="<?= base_url('assets/css/login.css'); ?>">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .message-box {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
            display: none;
            /* Hide the message box initially */
        }

        .message-box.success {
            background-color: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }

        .message-box.error {
            background-color: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>


</head>

<body style="background: url('<?= base_url('assets/images/logo.jpg'); ?>') center/cover fixed no-repeat;">
    <div class="container right-panel-active">



        <!-- Sign In -->
        <div class="container__form container--signin">


            <form action="<?php echo base_url('login/validate_credentials'); ?>" class="form" id="form2" method="post">

                <!-- Error and success message -->
                <?php if ($this->session->flashdata('login_message')): ?>
                    <div id="flash-message"
                        class="message-box <?php echo $this->session->flashdata('login_message')['type']; ?>">
                        <?php echo $this->session->flashdata('login_message')['text']; ?>
                    </div>
                <?php endif; ?>


                <h2 class="form__title">ወደ አካውንቶ ይግቡ</h2>
                <div class="input-group">
                    <input type="email" name="email" placeholder="ኢ-ሜይል" class="input" />
                </div>
                <div class="input-group">
                    <input type="password" name="password" id="password-field" placeholder="የይለፍ ቃል" class="input">
                    <span class="toggle-password" onclick="togglePassword()">
                        <i class="bi bi-eye-slash"></i>
                    </span>
                </div>
                <!-- <a href="#" class="link">Forgot your password?</a> -->
                <button class="btn">አስገባ</button>
            </form>

        </div>

        <!-- Overlay -->
        <div class="container__overlay">
            <div class="overlay"
                style="background: url('<?= base_url('assets/images/logo.jpg'); ?>') center/cover fixed no-repeat;">
            </div>
        </div>
    </div>
</body>

<script src="assets/js/script.js"></script>
<script>
    const messageBox = document.getElementById('flash-message');

    function showMessage(text, type) {
        messageBox.innerText = text;
        messageBox.className = 'message-box ' + type;
        messageBox.style.display = 'block'; // Show the message box

        // Hide message after 3 seconds
        setTimeout(() => {
            messageBox.style.display = 'none';
        }, 3000);
    }

    // Show the message on page load if it exists
    document.addEventListener('DOMContentLoaded', function() {
        if (messageBox && messageBox.innerText.trim() !== '') {
            const type = messageBox.classList.contains('success') ? 'success' : 'error';
            showMessage(messageBox.innerText, type);
        }
    });
</script>

</html>