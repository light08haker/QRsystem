<?php
require 'database.php';
require 'functions.php';

if (isset($_POST['login'])) {
    $student_id_or_email = clean($_POST['student_id']);
    $password = clean($_POST['password']);

    // Check if the input is an email or student_id
    if (filter_var($student_id_or_email, FILTER_VALIDATE_EMAIL)) {
        $query = "SELECT * FROM users WHERE email = '$student_id_or_email' AND password = '$password'";
    } else {
        $query = "SELECT * FROM users WHERE student_id = '$student_id_or_email' AND password = '$password'";
    }

    $result = mysqli_query($con, $query);

    if (mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);

        $_SESSION['userid'] = $row['id'];
        $_SESSION['student_id'] = $row['student_id'];
        $_SESSION['password'] = $row['password'];

        // Check user type and redirect accordingly
        if ($row["user_type"] == "user") {
            header("location: userForm.php");
            exit;
        } elseif ($row["user_type"] == "admin") {
            header("location: adminForm.php");
            exit;
        } elseif ($row["user_type"] == "admin2") {
            header("location: shareCam1.php");
            exit;
        }
    } else {
        $_SESSION['errprompt'] = "Wrong Student ID / Email or Password";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Attendance Monitor System</title>
    <link rel="icon" href="icon.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styless.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

    <div class="card p-4 shadow-lg box">
        <form method="post">
            <h1 class="text-center mb-4">Login to my Account</h1>

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
                <input type="text" class="form-control" id="student_id" name="student_id" placeholder="Student ID/Email" required>
                <label for="student_id">Student ID / Email</label>
            </div>

            <div class="form-floating mb-3 position-relative">
                <input type="password" class="form-control" id="password" name="password" placeholder="Password" required>
                <label for="password">Password</label>
                <i class="bi bi-eye-slash position-absolute top-50 end-0 translate-middle-y me-3 cursor-pointer" id="togglePassword" style="cursor: pointer;"></i>
            </div>

            <div class="d-grid gap-2">
                <input type="submit" value="Log In" name="login" class="btn btn-primary login">
            </div><br>


          
                <a href="forgotpass.php" class="text-danger mb-3 d-block password">
                <div class="password">Forgot Password?</div></a><hr>
            

           
            <div class="text-center mb-4">
            <a href="register.php" class="hgreen"><h5>Create an account</h5></a>
            </div>
                

        </form>
    </div> 

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
    </script>
</body>
</html>
