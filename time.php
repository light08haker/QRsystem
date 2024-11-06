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

// Get selected month and year from the form, or use the current month/year by default
$selected_month = isset($_GET['month']) ? $_GET['month'] : date('m');
$selected_year = isset($_GET['year']) ? $_GET['year'] : date('Y');

// Fetch Time-In Data based on student_id, month, and year
$timein_query = "SELECT * FROM timein WHERE student_id = '".$user['student_id']."' AND MONTH(timeIn) = '$selected_month' AND YEAR(timeIn) = '$selected_year'";
$timein_query_run = mysqli_query($con, $timein_query);
$timein_records = $timein_query_run ? mysqli_fetch_all($timein_query_run, MYSQLI_ASSOC) : [];

// Fetch Time-Out Data based on student_id, month, and year
$timeout_query = "SELECT * FROM timeout WHERE student_id = '".$user['student_id']."' AND MONTH(timeOut) = '$selected_month' AND YEAR(timeOut) = '$selected_year'";
$timeout_query_run = mysqli_query($con, $timeout_query);
$timeout_records = $timeout_query_run ? mysqli_fetch_all($timeout_query_run, MYSQLI_ASSOC) : [];

// Generate month options for the dropdown
$months = array(
    '01' => 'January', '02' => 'February', '03' => 'March', '04' => 'April',
    '05' => 'May', '06' => 'June', '07' => 'July', '08' => 'August',
    '09' => 'September', '10' => 'October', '11' => 'November', '12' => 'December'
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Time</title>
    <link rel="icon" href="icon.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">
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
        .table-container {
            height: 230px; 
            overflow-y: auto;
        }
        thead th {
            position: sticky;
            top: 0;
            background-color: #fff;
            z-index: 2;
        }
        .hidden {
            display: none;
        }
        .toggle-icon {
            cursor: pointer;
            font-size: 1.5rem;
            margin-left: 10px; /* Space between the button and filter */
        }
    </style>
</head>
<body>
<!-- Include Navbar -->
<?php include 'navbar.php'; ?>

<div class="container mt-4">
    <div class="card p-5 shadow-lg row d-flex">
        <h5 class="card-title text-center mb-4">Time Records</h5>

        <!-- Filter Form -->
        <form method="GET" id="filterForm" class="d-flex justify-content-center mb-4">
            <div class="me-2">
                <select name="month" class="form-select" onchange="this.form.submit()">
                    <?php foreach ($months as $num => $name): ?>
                        <option value="<?= $num ?>" <?= ($selected_month == $num) ? 'selected' : '' ?>>
                            <?= $name ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <select name="year" class="form-select" onchange="this.form.submit()">
                    <?php for ($y = 2024; $y <= date('Y'); $y++): ?>
                        <option value="<?= $y ?>" <?= ($selected_year == $y) ? 'selected' : '' ?>>
                            <?= $y ?>
                        </option>
                    <?php endfor; ?>
                </select>
            </div>
            <i id="toggleBtn" class="bi bi-clock-fill toggle-icon" onclick="toggleRecords()"></i>
        </form>

        <!-- Scrollable Time-In Table -->
        <div id="timeinRecords" class="table-container">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time-In</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($timein_records) > 0): ?>
                        <?php foreach ($timein_records as $record): ?>
                            <tr class="<?= ($record === reset($timein_records)) ? : ''; ?>">
                                <td><?= date('Y-m-d', strtotime($record['timeIn'])); ?></td>
                                <td><?= date('H:i:s', strtotime($record['timeIn'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="text-center">No Time-In Records Found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Scrollable Time-Out Table -->
        <div id="timeoutRecords" class="table-container hidden">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Time-Out</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (count($timeout_records) > 0): ?>
                        <?php foreach ($timeout_records as $record): ?>
                            <tr class="<?= ($record === reset($timeout_records)) ? 'highlight' : ''; ?>">
                                <td><?= date('Y-m-d', strtotime($record['timeout'])); ?></td>
                                <td><?= date('H:i:s', strtotime($record['timeout'])); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="2" class="text-center">No Time-Out Records Found</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- JavaScript for Toggle Functionality -->
<script>
    let isTimeInVisible = true;
    let isDebouncing = false;

    function toggleRecords() {
        if (isDebouncing) return; // Prevent multiple clicks
        isDebouncing = true;

        const timeinRecords = document.getElementById('timeinRecords');
        const timeoutRecords = document.getElementById('timeoutRecords');
        const toggleBtn = document.getElementById('toggleBtn');

        // Immediately update the icon
        if (isTimeInVisible) {
            toggleBtn.classList.remove('bi-clock-fill');
            toggleBtn.classList.add('bi-clock-history'); // Change to clock history icon
        } else {
            toggleBtn.classList.remove('bi-clock-history');
            toggleBtn.classList.add('bi-clock-fill'); // Change back to clock icon
        }

        // Toggle visibility
        isTimeInVisible = !isTimeInVisible;
        timeinRecords.classList.toggle('hidden', !isTimeInVisible);
        timeoutRecords.classList.toggle('hidden', isTimeInVisible);

        // Reset debounce after a short delay
        setTimeout(() => {
            isDebouncing = false;
        }, 300); // Adjust time as needed
    }
</script>


<!-- Bootstrap JS and dependencies (Popper and JS) -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

</body>
</html>
