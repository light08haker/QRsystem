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

if ($user["user_type"] != "user") {
    header("location: Logout.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Profile</title>
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
        .modal-body img {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .btn-primary {
            background-color: #0b6e22;
            border: none;
            padding: 10px 20px;
            border-radius: 50px;
            transition: background-color 0.3s ease;
        }
        .btn-primary:hover {
            background-color: #075c19;
        }
        .modal-content {
            border-radius: 15px;
        }
        .row.d-flex {
            flex-wrap: nowrap;
            align-items: center;
            text-align: center;
            
        }
        .col-md-4 {
            flex: 0 0 50%;
        }
        .col-md-8 {
            flex: 1;
        }
        @media (min-width: 768px) {
        .row label, .row span {
            font-size: 1rem;
        }
    }
    </style>
<body>
<!-- Include Navbar -->
<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <div class="card p-5 shadow-lg">
        <h5 class="card-title text-center mb-4">Profile Information</h5><hr>

        <div class="row d-flex">
            <div class="col-md-4">
                <label><i class="bi bi-person-fill icon"></i>Student ID:</label>
            </div>
            <div class="col-md-8">
                <span><?= $user['student_id']; ?></span>
            </div>
        </div>

        <div class="row d-flex">
            <div class="col-md-4">
                <label><i class="bi bi-person-circle icon"></i>Name:</label>
            </div>
            <div class="col-md-8">
                <span><?= $user['name']; ?></span>
            </div>
        </div>

        <div class="row d-flex">
            <div class="col-md-4">
                <label><i class="bi bi-building icon"></i>Department:</label>
            </div>
            <div class="col-md-8">
                <span><?= $user['department']; ?></span>
            </div>
        </div>

        <div class="row d-flex">
            <div class="col-md-4">
                <label><i class="bi bi-calendar3 icon"></i>Year:</label>
            </div>
            <div class="col-md-8">
                <span><?= $user['year']; ?></span>
            </div>
        </div>

        <div class="row d-flex">
            <div class="col-md-4">
                <label><i class="bi bi-diagram-3 icon"></i>Section:</label>
            </div>
            <div class="col-md-8">
                <span><?= $user['section']; ?></span>
            </div>
        </div>

        <!-- Button trigger modal -->
        <div class="text-center mt-4">
            <button type="button" class="btn btn-primary login" data-bs-toggle="modal" data-bs-target="#qrModal">
              View QR Code
            </button>
        </div>
    </div>

    <!-- QR Code Modal -->
    <div class="modal fade" id="qrModal" tabindex="-1" aria-labelledby="qrModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="qrModalLabel">Your QR Code</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body text-center">
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data=<?= $user['qr'];?>" alt="QR Code" width="150">
            <p class="mt-3"><strong>QR Data:</strong> <?= $user['qr']; ?></p>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>
</div>

<!-- Bootstrap JS and dependencies (Popper and JS) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
