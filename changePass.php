<?php
require 'database.php';
require 'functions.php';

// Get user details from the session
$query = "SELECT * FROM users WHERE id = '".$_SESSION['userid']."'";
$query_run = mysqli_query($con, $query);
$user = mysqli_fetch_assoc($query_run);

// Redirect if the user is not authorized
if ($user["user_type"] != "user") {
    header("location: Logout.php");
    exit;
}

// Handle password update
if (isset($_POST['change_password'])) {
    $current_password = clean($_POST['current_password']);
    $new_password = clean($_POST['new_password']);
    $confirm_password = clean($_POST['confirm_password']);
    $userId = $_SESSION['userid'];

    // Step 1: Verify current password
    if ($current_password != $user['password']) {
        $_SESSION['errprompt'] = "Current password is incorrect.";
        header("Location: changePass.php");
        exit;
    }

    // Step 2: Check if the new password matches the confirmation
    if ($new_password != $confirm_password) {
        $_SESSION['errprompt'] = "New password and confirm password do not match.";
        header("Location: changePass.php");
        exit;
    }

    // Step 3: Check password length or strength (e.g., at least 8 characters)
    if (strlen($new_password) < 8) {
        $_SESSION['errprompt'] = "New password must be at least 8 characters long.";
        header("Location: changePass.php");
        exit;
    }

    // Update the password in the database
    $query = "UPDATE users SET password='$new_password' WHERE id = '$userId'";
    if (mysqli_query($con, $query)) {
        $_SESSION['prompt'] = "Password updated successfully.";
        header("Location: changePass.php");
        exit;
    } else {
        $_SESSION['errprompt'] = "Error updating password.";
        header("Location: changePass.php");
        exit;
    }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Change Password</title>
    <link rel="icon" href="icon.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<style>
    .container {
        width: 900px;
        max-width: 95%;
    }
    .card {
        border-radius: 20px;
        border: none;
        padding: 20px 30px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    .form-floating {
        position: relative;
    }
    .form-floating i {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        cursor: pointer;
    }
</style>
<body>

<form method="post">  
    <?php include 'navbar.php'; ?>
    <div class="container mt-4">
        <div class="card p-5 shadow-lg">

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

            <div class="form-floating mb-3 position-relative">
                <input type="password" class="form-control" id="current_password" name="current_password" placeholder="Current Password" required>
                <label for="current_password">Current Password</label>
                <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer" id="toggleCurrentPassword"></i>
            </div>

            <div class="form-floating mb-3 position-relative">
                <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New Password" required>
                <label for="new_password">New Password</label>
                <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer" id="toggleNewPassword"></i>
            </div>

            <div class="form-floating mb-3 position-relative">
                <input type="password" class="form-control" id="confirm_password" name="confirm_password" placeholder="Confirm Password" required>
                <label for="confirm_password">Confirm New Password</label>
                <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer" id="toggleConfirmPassword"></i>
            </div>

            <div class="d-grid gap-2">
                <button class="btn btn-success" value="Change Password" name="change_password">Change Password</button>
            </div>

        </div>
    </div>
</form>

<script>
    // Toggle visibility for Current Password
    const toggleCurrentPassword = document.querySelector('#toggleCurrentPassword');
    const currentPassword = document.querySelector('#current_password');
    toggleCurrentPassword.addEventListener('click', function () {
        const type = currentPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        currentPassword.setAttribute('type', type);
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });

    // Toggle visibility for New Password
    const toggleNewPassword = document.querySelector('#toggleNewPassword');
    const newPassword = document.querySelector('#new_password');
    toggleNewPassword.addEventListener('click', function () {
        const type = newPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        newPassword.setAttribute('type', type);
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });

    // Toggle visibility for Confirm Password
    const toggleConfirmPassword = document.querySelector('#toggleConfirmPassword');
    const confirmPassword = document.querySelector('#confirm_password');
    toggleConfirmPassword.addEventListener('click', function () {
        const type = confirmPassword.getAttribute('type') === 'password' ? 'text' : 'password';
        confirmPassword.setAttribute('type', type);
        this.classList.toggle('bi-eye');
        this.classList.toggle('bi-eye-slash');
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
</body>
</html>
