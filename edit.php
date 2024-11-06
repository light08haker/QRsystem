<?php
require 'database.php';
require 'functions.php';

$query = "SELECT * FROM users WHERE id = '".$_SESSION['userid']."'";
$query_run = mysqli_query($con, $query);
$user = mysqli_fetch_assoc($query_run);

if ($user["user_type"] != "user") {
    header("location: Logout.php");
    exit;
}

if (isset($_POST['edit'])) {
    $student_id = clean($_POST['student_id']);
    $name = clean($_POST['name']);
    $department = clean($_POST['department']);
    $year = clean($_POST['year']);
    $section = clean($_POST['section']);
    $email = clean($_POST['email']);
    $password = clean($_POST['password']);
    $generatedCode = clean($_POST['qr']);

    $userId = $_SESSION['userid'];

    // Check if the student exists (by student_id or email)
    $query = "SELECT student_id FROM users WHERE student_id = '$student_id' AND id != '$userId'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['errprompt'] = "Student Info already exists.";
        header("Location: edit.php");
        exit;
    } else {
        // Check last updated timestamp
        $lastUpdated = $user['last_updated'];
        $now = new DateTime();
        $lastUpdateDateTime = new DateTime($lastUpdated);
        $interval = $now->diff($lastUpdateDateTime);
        
        // Calculate months since last update
        $monthsSinceLastUpdate = $interval->m + ($interval->y * 12);
        
        // Check update limits
        if ($monthsSinceLastUpdate < 3) {
            $_SESSION['errprompt'] = "You can only update your information every 3 months.";
            header("Location: edit.php");
            exit;
        }

        // Check if ID and name can be updated (every 1.5 years)
        if ($user['student_id'] !== $student_id || $user['name'] !== $name) {
            if ($monthsSinceLastUpdate < 12) {
                $_SESSION['errprompt'] = "You can only update your Name every a year.";
                header("Location: edit.php");
                exit;
            }
        }

        // Update the user's data
        $query = "UPDATE users SET 
            student_id='$student_id',
            name='$name', 
            department='$department', 
            year='$year', 
            section='$section', 
            email='$email', 
            password='$password', 
            qr='$generatedCode',
            last_updated=NOW() 
            WHERE id = '$userId'";

        if (mysqli_query($con, $query)) {
            $_SESSION['prompt'] = "Account information updated successfully.";
            header("Location: edit.php"); // Redirect to a different page after update
            exit;
        } else {
            $_SESSION['errprompt'] = "Error updating account.";
            header("Location: edit.php");
            exit;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Edit Account</title>
    <link rel="icon" href="icon.jpg">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .container {
            width: 900px;
            max-width: 95%;
        }
        .card {
            border-radius: 20px;
            border: none;
            padding: 20px 30px; /* Increased padding */
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        .card-title {
            font-weight: 600;
            font-size: 1.5rem;
            color: #333;
        }
        .row {
            margin-bottom: 15px;
        }
        label {
            font-size: 1rem;
            font-weight: bold;
        }
        .icon {
            margin-right: 10px;
        }
        span {
            font-size: 1.1rem;
            color: #555;
        }
        .row.d-flex {
            flex-wrap: nowrap;
            align-items: center;
        }
        .col-md-4 {
            flex: 0 0 150px;
        }
        .col-md-8 {
            flex: 1;
        }
    </style>
<body>
    <?php include 'navbar.php'; ?>
    <div class="container mt-4">
        <div class="card p-5 shadow-lg">
            <form method="post">

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

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="student_id" name="student_id" value="<?=$user['student_id'];?>" placeholder="ID Number" required readonly>
                    <label for="student_id">ID Number</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="name" name="name" value="<?=$user['name'];?>" placeholder="Full Name (Last, First, Middle)" required>
                    <label for="name">Full Name (Last, First, Middle)</label>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-control" id="department" name="department" required>
                        <option value="" disabled>Select</option>
                        <option value="BSCS" <?= ($user['department'] == 'BSCS') ? 'selected' : ''; ?>>Computer Science</option>
                        <option value="BSED" <?= ($user['department'] == 'BSED') ? 'selected' : ''; ?>>Education</option>
                        <option value="BSBA" <?= ($user['department'] == 'BSBA') ? 'selected' : ''; ?>>Accountancy</option>
                        <option value="CRIM" <?= ($user['department'] == 'CRIM') ? 'selected' : ''; ?>>Criminology</option>
                        <option value="VISITOR" <?= ($user['department'] == 'VISITOR') ? 'selected' : ''; ?>>VISITOR</option>
                    </select>
                    <label for="department">Department</label>
                </div>

                <div class="form-floating mb-3">
                    <select class="form-control" id="year" name="year" required>
                        <option value="" disabled>Select</option>
                        <option value="1st Year" <?= ($user['year'] == '1st Year') ? 'selected' : ''; ?>>1st Year</option>
                        <option value="2nd Year" <?= ($user['year'] == '2nd Year') ? 'selected' : ''; ?>>2nd Year</option>
                        <option value="3rd Year" <?= ($user['year'] == '3rd Year') ? 'selected' : ''; ?>>3rd Year</option>
                        <option value="4th Year" <?= ($user['year'] == '4th Year') ? 'selected' : ''; ?>>4th Year</option>
                        <option value="N/A" <?= ($user['year'] == 'N/A') ? 'selected' : ''; ?>>N/A</option>
                    </select>
                    <label for="year">Year</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="section" name="section"  value="<?=$user['section'];?>" placeholder="Section (Type N/A if none)" required>
                    <label for="section">Section (Type N/A if none)</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" class="form-control" id="email" name="email"  value="<?=$user['email'];?>" placeholder="Email" required>
                    <label for="email">Email</label>
                </div>

                <input type="hidden" id="password" name="password" value="<?=$user['password'];?>" placeholder="Password" required>
                <input type="hidden" id="generatedCode" name="qr" value="">

                <div class="d-grid gap-2">
                    <button class="btn btn-success" value="Edit" name="edit" onclick="updateAndGenerateQR()">Update</button>
                </div>

            </form>

        </div>
        <div class="qr-con" style="visibility: hidden;">
                    <img id="qrImg" alt="QR Code" />
                </div>
    </div>

    <script>
        function generateRandomCode(length) {
            const characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            let randomString = '';

            for (let i = 0; i < length; i++) {
                const randomIndex = Math.floor(Math.random() * characters.length);
                randomString += characters.charAt(randomIndex);
            }

            return randomString;
        }

        function generateQrCode() {
            const qrImg = document.getElementById('qrImg');
            const generatedCodeField = document.getElementById('generatedCode');

            let text = generateRandomCode(10);
            const apiUrl = `https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=${encodeURIComponent(text)}`;
            qrImg.src = apiUrl;
            generatedCodeField.value = text;

            document.querySelector('.qr-con').style.display = '';
        }

        function updateAndGenerateQR() {
            document.getElementById('student_id').style.pointerEvents = 'none';
            document.getElementById('name').style.pointerEvents = 'none';
            document.getElementById('department').style.pointerEvents = 'none';
            document.getElementById('year').style.pointerEvents = 'none';
            document.getElementById('section').style.pointerEvents = 'none';
            document.getElementById('email').style.pointerEvents = 'none';

            generateQrCode();
        }
    </script>
    
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
