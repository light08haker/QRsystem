<?php
require 'database.php';
require 'functions.php';

if (isset($_POST['register'])) {
    $student_id = clean($_POST['student_id']);
    $name = clean($_POST['name']);
    $department = clean($_POST['department']);
    $year = clean($_POST['year']);
    $section = clean($_POST['section']);
    $email = clean($_POST['email']);
    $password = clean($_POST['password']);
    $generatedCode = clean($_POST['qr']); // Get the generated QR code URL

    // Check if the password is at least 8 characters long
    if (strlen($password) < 8) {
        $_SESSION['errprompt'] = "Password must be at least 8 characters.";
        header("Location: register.php");
        exit;
    }

    $query = "SELECT email FROM users WHERE student_id = '$student_id' OR email = '$email'";
    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $_SESSION['errprompt'] = "Student ID or Email already exists.";
        header("Location: register.php");
        exit;
    } else {
        $query = "INSERT INTO users (student_id, name, department, year, section, email, password, qr, last_updated) 
                  VALUES ('$student_id', '$name', '$department', '$year', '$section', '$email', '$password', '$generatedCode', '000000')";

        if (mysqli_query($con, $query)) {
            $_SESSION['prompt'] = "Account Registered, You can now <a href='index.php' class='link'>Log In</a>";
            header("Location: register.php");
            exit;
        } else {
            $_SESSION['errprompt'] = "Error in registration.";
            header("Location: register.php");
            exit;
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Account</title>
    <link rel="icon" href="icon.jpg">
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styless.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body class="d-flex justify-content-center align-items-center">
<div class="card p-4 shadow-lg box">
        <form method="post">
            <a href="index.php" class="text-success"><i class="fas fa-arrow-left"></i> Back</a>
            <h1 class="text-center mb-4">Create Your Account</h1>

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
                <input type="text" class="form-control" id="student_id" name="student_id" placeholder="ID Number" required>
                <label for="student_id">ID Number</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="name" name="name" placeholder="Full Name (Last, First, Middle)" required>
                <label for="name">Full Name (Last, First, Middle)</label>
            </div>

            <div class="form-floating mb-3">
                <select class="form-control" id="department" name="department" required>
                    <option value="" disabled selected>Select</option>
                    <option value="BSCS">Computer Science</option>
                    <option value="BSED">Education</option>
                    <option value="BSBA">Accountancy</option>
                    <option value="CRIM">Criminology</option>
                    <option value="VISITOR">VISITOR</option>
                </select>
                <label for="department">Department</label>
            </div>

            <div class="form-floating mb-3">
                <select class="form-control" id="year" name="year" required>
                    <option value="" disabled selected>Select</option>
                    <option value="1st Year">1st</option>
                    <option value="2nd Year">2nd</option>
                    <option value="3rd Year">3rd</option>
                    <option value="4th Year">4th</option>
                    <option value="N/A">N/A</option>
                </select>
                <label for="year">Year</label>
            </div>

            <div class="form-floating mb-3">
                <input type="text" class="form-control" id="section" name="section" placeholder="Section (Type N/A if none)" required>
                <label for="section">Section (Type N/A if none)</label>
            </div>

            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>

            <div class="form-floating mb-3 position-relative">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required minlength="8">
                <label for="password">Password</label>
                <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer" id="togglePassword" style="cursor: pointer;"></i>
            </div>


            <input type="hidden" id="generatedCode" name="qr" value="">
            <div class="qr-con" style="display: none; margin-top: 15px;">
                <img id="qrImg" alt="QR Code" class="img-fluid mx-auto d-block" />
            </div><br>

            <div class="d-grid gap-2">
                <button type="button" class="btn btn-primary login" onclick="generateQrCode()">Generate QR Code</button>
                <button class="btn btn-success" value="Register" name="register" style="display: none; margin-left: 10px;" onclick="closeModal()">Register</button>
            </div>
        </form>
    </div><br><br>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
      <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);
            this.classList.toggle('bi-eye');
            this.classList.toggle('bi-eye-slash');
        });


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
            generatedCodeField.value = text; // Store only the serial code, not the QR URL

            // Disable input fields after generating QR code
            document.getElementById('student_id').disabled = true;
            document.getElementById('name').disabled = true;
            document.getElementById('department').disabled = true;
            document.getElementById('year').disabled = true;
            document.getElementById('section').disabled = true;
            document.querySelector('.btn-success').style.display = '';
            document.querySelector('.qr-con').style.display = '';
            document.querySelector('.btn-primary').style.display = 'none';
        }

        function closeModal() {
            document.querySelector('.qr-con').style.display = 'none';
            document.querySelector('.btn-primary').style.display = '';
            document.querySelector('.btn-success').style.display = 'none';

            // Enable input fields
            document.getElementById('student_id').disabled = false;
            document.getElementById('name').disabled = false;
            document.getElementById('department').disabled = false;
            document.getElementById('year').disabled = false;
            document.getElementById('section').disabled = false;
            document.getElementById('email').disabled = false;
        }
    </script>
    
    <!-- Bootstrap JS (optional) -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
