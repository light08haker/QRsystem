<?php
require 'database.php';
require 'functions.php';

if (!isset($_SESSION['userid'])) {
    header("location: Logout.php");
    exit;
}

$query = "SELECT * FROM users WHERE id = '".$_SESSION['userid']."'";
$query_run = mysqli_query($con, $query);
$user = mysqli_fetch_assoc($query_run);

if ($user["user_type"] != "admin") {
    header("location: Logout.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>
    <link rel="icon" href="icon.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  
    <style>
        .btn-custom {
            width: 100%;
            margin-bottom: 15px;
            font-size: 18px;
        }
        .btn-custom i {
            margin-right: 8px;
        }
    </style>
</head>
<body class="d-flex justify-content-center align-items-center vh-100">

    <div class="card p-4 shadow-lg">
        <form method="post">
            <div class="card-body text-center">
                <h1 class="text-center mb-4">Admin Dashboard</h1>
                <a href="viewStudents.php" class="btn btn-success login  btn-custom"><i class="fas fa-users"></i>Students</a>
                <a href="timeIn.php" class="btn btn-success login btn-custom"><i class="fas fa-sign-in-alt"></i>Time In</a>
                <a href="timeOut.php" class="btn btn-success login  btn-custom"><i class="fas fa-sign-out-alt"></i>Time Out</a>
                <a href="scanQR.php" class="btn btn-success login  btn-custom"><i class="fas fa-qrcode"></i>QR Scanner</a>
                <a href="shareCam.php" class="btn btn-success login  btn-custom"><i class="fas fa-share-alt"></i>Share QR Scanner</a>
                
                <hr>
                <a href="logout.php" class="btn btn-danger btn-custom"><i class="fas fa-sign-out-alt"></i>Log Out</a>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
