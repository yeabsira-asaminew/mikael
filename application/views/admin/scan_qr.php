<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- favicon -->
    <link rel="icon" href="<?php echo base_url('assets/images/favicon.ico'); ?>" type="image/x-icon">
    <title>QR Code Scanner | ሰንበት አቴንዳንስ ሲስተም</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.min.js"></script>
    <link rel="stylesheet" href="<?= base_url('assets/css/scanner.css'); ?>">
    <style>
        .message-box {
            padding: 10px;
            margin: 10px 0;
            border-radius: 5px;
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

        button {
            padding: 10px 20px;
            margin: 10px 0;
            background-color: #FF5722;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        button:hover {
            background-color: #E64A19;
        }
    </style>
</head>

<body>
    <div class="scanner-container">
        <div id="response-message" class="message-box" style="display: none;"></div>

        <?php if (isset($response)): ?>
            <div id="response-message" class="message-box <?php echo ($response['status'] === 'success') ? 'success' : 'error'; ?>">
                <?php echo $response['message']; ?>
            </div>
        <?php endif; ?>

        <h1>Scan QR Code</h1>
        <video id="video" autoplay></video>
        <canvas id="canvas" style="display:none;"></canvas>
        <p id="scanned-data">Scanning...</p>
        <div id="message" class="message"></div>
    </div>

    <button id="markAbsentBtn">የዛሬውን መርሐግብር በ45 ደቂቃ ላረፈዱ ወይም ላልተገኙ ተማሪዎች በሙሉ ቀሪ መዝግብ</button>

    <a href="<?= base_url('dashboard'); ?>" class="dashboard-link">ወደ ዳሽቦርድ ይመለሱ</a>
</body>

<script>
    document.getElementById('markAbsentBtn').addEventListener('click', function(e) {
        e.preventDefault();

        if (!confirm('እርግጠኛ ነዎት?')) return;

        fetch('<?php echo site_url("attendance/record_absent_for_all"); ?>', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                const box = document.getElementById('response-message');
                box.textContent = data.message;
                box.className = 'message-box ' + (data.status === 'success' ? 'success' : (data.status === 'error' ? 'error' : 'info'));
                box.style.display = 'block';

                // Optional: hide after 5 seconds
                setTimeout(() => {
                    box.style.display = 'none';
                }, 5000);
            })
            .catch(error => {
                const box = document.getElementById('response-message');
                box.textContent = 'something went wrong!';
                box.className = 'message-box error';
                box.style.display = 'block';
                console.error('Fetch error:', error);
            });
    });
</script>

<script>
    const video = document.getElementById('video');
    const canvas = document.getElementById('canvas');
    const context = canvas.getContext('2d');
    const scannedData = document.getElementById('scanned-data');
    const messageBox = document.getElementById('message');

    let scanningPaused = false; // Flag to control scanning

    function showMessage(text, type) {
        messageBox.innerText = text;
        messageBox.className = 'message ' + type;
        messageBox.style.display = 'block';

        // Hide message after 3 seconds
        setTimeout(() => {
            messageBox.style.display = 'none';
        }, 3000);
    }

    if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
        scannedData.innerText = "Your browser does not support camera access.";
    } else {
        navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "environment"
                }
            })
            .then(function(stream) {
                video.srcObject = stream;
                video.play();
                requestAnimationFrame(scanQRCode);
            })
            .catch(function(error) {
                scannedData.innerText = "Error accessing camera: " + error.message;
            });
    }

    function scanQRCode() {
        if (scanningPaused) {
            requestAnimationFrame(scanQRCode);
            return; // Stop scanning if it's paused
        }

        if (video.readyState === video.HAVE_ENOUGH_DATA) {
            canvas.height = video.videoHeight;
            canvas.width = video.videoWidth;
            context.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height, {
                inversionAttempts: "dontInvert",
            });

            if (code) {
                scanningPaused = true; // Pause scanning

                $.ajax({
                    url: '<?php echo site_url("attendance/record"); ?>',
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        student_id: code.data
                    },
                    success: function(response) {
                        // Display the decrypted student ID if available
                        if (response.student_id) {
                            scannedData.innerText = `የተማሪው መታወቂያ ቁ.: ${response.student_id}`;
                        } else {
                            scannedData.innerText = `የተማሪው መታወቂያ ቁ.: ${code.data}`;
                        }

                        if (response.status === 'success') {
                            showMessage(response.message, "success");
                        } else if (response.status === 'absent') {
                            showMessage(response.message, "error");
                        } else if (response.status === 'error') {
                            showMessage(response.message, "error");
                        }
                    },
                    error: function(xhr, status, error) {
                        showMessage("❌ An error occurred: " + error, "error");
                    }
                });

                // Resume scanning after 3 seconds
                setTimeout(() => {
                    scanningPaused = false;
                }, 3000);
            }
        }
        requestAnimationFrame(scanQRCode);
    }
</script>

</html>