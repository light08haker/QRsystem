<?php
require 'database.php'; // Include database connection
require 'functions.php'; // Include additional functions

// Set the default timezone to Manila, Philippines
date_default_timezone_set('Asia/Manila');

// Check if QR code is posted
if (isset($_POST['qr_code'])) {
    $qrCode = $con->real_escape_string($_POST['qr_code']); // Escape input for security

    // Prepare and execute statement to get user data from QR code
    $query = "SELECT student_id, name, department, year, section, email, qr FROM users WHERE qr = '$qrCode'";
    $result = $con->query($query);

    if ($result && $row = $result->fetch_assoc()) {
        // Extract student data
        $studentID = $row['student_id'];
        $name = $row['name'];
        $department = $row['department'];
        $year = $row['year'];
        $section = $row['section'];
        $email = $row['email'];
        $qr = $row['qr'];
        $currentTime = date("Y-m-d H:i:s");
        $currentDate = date("Y-m-d");

        // Step 1: Check if the student has already scanned in today for timein
        $checkTimeInQuery = "SELECT * FROM timein WHERE (qr = '$qr' OR student_id = '$studentID') AND DATE(timeIn) = '$currentDate'";
        $checkTimeInResult = $con->query($checkTimeInQuery);

        // Step 2: Check if the student has already scanned out today for timeout
        $checkTimeOutQuery = "SELECT * FROM timeout WHERE (qr = '$qr' OR student_id = '$studentID') AND DATE(timeout) = '$currentDate'";
        $checkTimeOutResult = $con->query($checkTimeOutQuery);

        // Step 3: Time-in logic (First scan)
        if ($checkTimeInResult->num_rows == 0) {
            // If no time-in record for today, insert timein into the `timein` table
            $insertTimeInQuery = "INSERT INTO timein (student_id, name, department, year, section, email, qr, timeIn) 
                                  VALUES ('$studentID', '$name', '$department', '$year', '$section', '$email', '$qr', '$currentTime')";
            if ($con->query($insertTimeInQuery)) {
                $_SESSION['prompt'] = "Time-in successfully recorded!";
            } else {
                $_SESSION['errprompt'] = "Failed to record time-in.";
            }
        } 
        // Step 4: Time-out logic (Second scan, only after a valid time-in)
        else if ($checkTimeOutResult->num_rows == 0) {
            // Ensure timeout isn't already recorded for today
            $timeInRow = $checkTimeInResult->fetch_assoc();
            $timeInRecorded = strtotime($timeInRow['timeIn']);
            $timeNow = strtotime($currentTime);
            $timeDifference = ($timeNow - $timeInRecorded) / 3600; // Difference in hours

            if ($timeDifference >= 1) {
                // Insert timeout into the `timeout` table if more than 1 hour has passed
                $insertTimeoutQuery = "INSERT INTO timeout (student_id, name, department, year, section, email, qr, timeout) 
                                       VALUES ('$studentID', '$name', '$department', '$year', '$section', '$email', '$qr', '$currentTime')";
                if ($con->query($insertTimeoutQuery)) {
                    $_SESSION['prompt'] = "Time-out successfully recorded!";
                } else {
                    $_SESSION['errprompt'] = "Failed to record time-out.";
                }
            } else {
                // Prevent time-out scan within 1 hour of time-in
                $_SESSION['errprompt'] = "You must wait at least 1 hour between time-in and time-out scans.";
            }
        } else {
            // If both time-in and time-out are already recorded for today
            $_SESSION['errprompt'] = "You have already completed both scans for today.";
        }
    } else {
        $_SESSION['errprompt'] = "No student found with this QR code.";
    }

    // Redirect back to the time-in user page
    header("Location: scanQR.php");
    exit;
}

// Check if user is logged in and is an admin
if (!isset($_SESSION['userid'])) {
    header("Location: Logout.php");
    exit;
}

$query = "SELECT user_type FROM users WHERE id = '" . $con->real_escape_string($_SESSION['userid']) . "'";
$result = $con->query($query);
$user = $result->fetch_assoc();

if (!$user || $user['user_type'] != "admin") {
    header("Location: Logout.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Attendance</title>
    <link rel="stylesheet" href="styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
    background-color: #f3faf1; /* Light background color */
}
        .scanner-con {
            text-align: center;
        }
        #interactive {
            border: 2px solid #1e7e34;
            border-radius: 10px;
            width: 550px;
            max-width: 100%;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center" onload="startScanner()">
<div class="card p-4 shadow-lg" style="width: 700px;">
<a href="adminForm.php" class="text-success"><i class="fas fa-arrow-left"></i> Back</a>
    

    <?php
            if (isset($_SESSION['prompt'])) {
                showPrompt();
            }

            if (isset($_SESSION['errprompt'])) {
                showError();
            }

            unset($_SESSION['prompt']);
            mysqli_close($con);
            unset($_SESSION['errprompt']);
            ?>
    
    <div class="scanner-con" id="scanner-container">
    <h5 class="text-center mb-4 text-center">Scan your QR Code here</h5>
        <video id="interactive" class="viewport"></video>
    </div>

    <!-- This will show after automatic scan -->
    <div class="qr-detected-container" id="detected-container" style="display: none;">
        <form id="auto-submit-form" method="POST" action="emailsentQR2.php">
            <input type="hidden" id="detected-qr-code" name="qr_code">
        </form>
    </div>

    <!-- Manual input form -->
    <div class="manual-input-container" id="manual-container" style="display: none;">
        <h5>Enter your QR Code manually</h5>
        <form method="POST" action="emailsentQR2.php">
            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="qr_code" name="qr_code" placeholder="Enter QR Code" required>
                <label for="qr_code">Enter QR Code</label>
            </div>
            <button type="submit" class="btn btn-primary">Submit Attendance</button>
        </form>
    </div><br>

    <!-- Toggle Button to Switch Between Auto and Manual Modes -->
    <div class="text-right">
        <button id="toggle-button" class="btn btn-secondary" onclick="toggleMode()">Switch to Manual Input</button>
    </div>
</div>

<script src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<script>
    let scanner;
    let isScannerActive = true;

    function startScanner() {
        scanner = new Instascan.Scanner({ video: document.getElementById('interactive') });

        // Automatically submit form when QR code is detected
        scanner.addListener('scan', function (content) {
            console.log("QR Code Content: ", content); // Debugging
            document.getElementById("detected-qr-code").value = content;

            // Automatically submit the form without user intervention
            document.getElementById("auto-submit-form").submit();
        });

        Instascan.Camera.getCameras()
            .then(function (cameras) {
                if (cameras.length > 0) {
                    scanner.start(cameras[0]); // Use the first available camera
                } else {
                    console.error('No cameras found.');
                    alert('No cameras found.');
                }
            })
            .catch(function (err) {
                console.error('Camera access error:', err);
                alert('Camera access error: ' + err);
            });
    }

    function toggleMode() {
        if (isScannerActive) {
            // Switch to Manual Input
            scanner.stop();
            document.getElementById("scanner-container").style.display = 'none';
            document.getElementById("manual-container").style.display = '';
            document.getElementById("toggle-button").textContent = "Switch to Auto Scan";
        } else {
            // Switch back to Auto Scan
            document.getElementById("manual-container").style.display = 'none';
            document.getElementById("scanner-container").style.display = '';
            document.getElementById("toggle-button").textContent = "Switch to Manual Input";
            startScanner(); // Restart the scanner
        }
        isScannerActive = !isScannerActive; // Toggle the mode state
    }

    // Start the scanner automatically on page load
    window.onload = function() {
        startScanner();
    }
</script>

</body>
</html>
