<?php
require 'database.php';
require 'functions.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

function sendMail($email, $token){
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host =  'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'systemcreation08@gmail.com'; // Your Gmail account
        $mail->Password = 'ptbpshrwejgivouj'; // Your App-specific password
        $mail->SMTPSecure = 'ssl';
        $mail->Port = 465;

        $mail->setFrom('QR_System@gmail.com', 'QR System'); // Set sender email and name
        $mail->addAddress($email); // Recipient email address

        $mail->isHTML(true);
        $mail->Subject = 'QR Account - Password Reset';

        // Construct email body
        $mail->Body = "
            <p>You requested to change the password for your QR System account registered with email: <strong>$email</strong>.</p>
            <p>Click the link below to reset your password:</p>
            <p><a href='http://localhost/Attendance%20Monitor%20System/newpass.php?email=$email&token=$token'>RESET PASSWORD</a></p>
            <p>This link will expire immediately.</p>
        ";

        $mail->send();

        $_SESSION['prompt'] = "A password reset has been successfully sent to your email address.";
        header("location:forgotpass.php");
        exit;

    } catch (Exception $e) {
        $_SESSION['errprompt'] = "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        header("location:forgotpass.php");
        exit;
    }
}

if (isset($_POST['forgot'])) {
    $query = "SELECT * FROM users WHERE email = '$_POST[email]'";
    $result = mysqli_query($con, $query);

    if ($result) {
        if (mysqli_num_rows($result) == 1) {
            $token = bin2hex(random_bytes(16)); // Generate token
            $date = date("Y-m-d");

            // Update user record with token and expiration date
            $query = "UPDATE users SET token = '$token', tokenexpire = '$date' WHERE email = '$_POST[email]'";
            if (mysqli_query($con, $query) && sendMail($_POST['email'], $token)) {
                $_SESSION['prompt'] = "Please check your email to reset your password.";
                header("location:forgotpass.php");
                exit;
            } else {
                $_SESSION['errprompt'] = "Server down! Please try again later.";
                header("location:forgotpass.php");
                exit;
            }
        } else {
            $_SESSION['errprompt'] = "Email not found.";
            header("location:forgotpass.php");
            exit;
        }
    } else {
        $_SESSION['errprompt'] = "Unable to run query.";
        header("location:forgotpass.php");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-lg">
        <a href="index.php" class="text-success"><i class="fas fa-arrow-left"></i> Back</a>
        <h1 class="text-center mb-4">Forgot Password</h1>
        <p class="darktext">Enter your email address, and we'll send you a link to reset your password.</p>

        <?php
        if (isset($_SESSION['prompt'])) {
            showPrompt();
            unset($_SESSION['prompt']);
        }

        if (isset($_SESSION['errprompt'])) {
            showError();
            unset($_SESSION['errprompt']);
        }
        ?>

        <form action="forgotpass.php" method="POST">
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" name="forgot" class="btn btn-primary">Send To My Email</button>
            </div>
        </form>
    </div>
</body>
</html>
