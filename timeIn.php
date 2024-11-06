<?php
require 'database.php'; // Assuming this contains the connection code to the database

if (!isset($_SESSION['userid'])) {
    header("location: Logout.php");
    exit;
}

// Fetch user information
$query = "SELECT * FROM users WHERE id = '".$_SESSION['userid']."'";
$query_run = mysqli_query($con, $query);
$user = mysqli_fetch_assoc($query_run);

// Check if the user is an admin
if ($user["user_type"] != "admin") {
    header("location: Logout.php");
    exit;
}

// AJAX request handling
if (isset($_POST['action']) && $_POST['action'] == 'filter') {
    $sql = "SELECT * FROM timeIn WHERE 1=1"; // Use your 'timeIn' table name

    // Handle filtering
    $department = isset($_POST['department']) ? $_POST['department'] : '';
    $year = isset($_POST['year']) ? $_POST['year'] : '';
    $section = isset($_POST['section']) ? $_POST['section'] : '';
    $timeIn = isset($_POST['timeIn']) ? $_POST['timeIn'] : '';
    $date = isset($_POST['date']) ? $_POST['date'] : ''; // New date filter
    $searchTerm = isset($_POST['searchTerm']) ? $_POST['searchTerm'] : ''; // Live search term
    $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10; // Default limit
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1; // Current page
    $offset = ($page - 1) * $limit; // Calculate offset for pagination
    $sortBy = isset($_POST['sortBy']) ? $_POST['sortBy'] : 'student_id';
    $order = isset($_POST['order']) ? $_POST['order'] : 'ASC';

    if (!empty($department)) {
        $sql .= " AND department = '$department'";
    }
    if (!empty($year)) {
        $sql .= " AND year = '$year'";
    }
    if (!empty($section)) {
        $sql .= " AND section = '$section'";
    }
    if (!empty($timeIn)) {
        $sql .= " AND timeIn = '$timeIn'";
    }
    if (!empty($date)) {
        $sql .= " AND DATE(timeIn) = '$date'"; // Filtering by date
    }
    if (!empty($searchTerm)) {
        $sql .= " AND (student_id LIKE '%$searchTerm%' OR name LIKE '%$searchTerm%')"; // Search filter
    }

    // Add sorting to the query
    $sql .= " ORDER BY $sortBy $order LIMIT $limit OFFSET $offset"; // Limit and offset

    // Execute the query
    $result = mysqli_query($con, $sql);
    
    // Count total records for pagination
    $countQuery = "SELECT COUNT(*) as total FROM timeIn WHERE 1=1";
    if (!empty($department)) {
        $countQuery .= " AND department = '$department'";
    }
    if (!empty($year)) {
        $countQuery .= " AND year = '$year'";
    }
    if (!empty($section)) {
        $countQuery .= " AND section = '$section'";
    }
    if (!empty($timeIn)) {
        $countQuery .= " AND timeIn = '$timeIn'";
    }
    if (!empty($date)) {
        $countQuery .= " AND DATE(timeIn) = '$date'"; // Filtering by date
    }
    if (!empty($searchTerm)) {
        $countQuery .= " AND (student_id LIKE '%$searchTerm%' OR name LIKE '%$searchTerm%')"; // Search filter
    }

    $countResult = mysqli_query($con, $countQuery);
    $totalRecords = mysqli_fetch_assoc($countResult)['total'];
    $totalPages = ceil($totalRecords / $limit); // Calculate total pages

    if (mysqli_num_rows($result) > 0) {
        $recordNumber = $offset + 1; // Start numbering from current offset + 1
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . $recordNumber++ . "</td> <!-- Added record number -->
                    <td>" . $row['student_id'] . "</td>
                    <td>" . $row['name'] . "</td>
                    <td>" . $row['department'] . "</td>
                    <td>" . $row['year'] . "</td>
                    <td>" . $row['section'] . "</td>
                    <td>" . $row['timeIn'] . "</td>
                  </tr>";
        }
        // Pagination controls
        echo "<tr><td colspan='7'>"; // Adjust colspan to match table headers
        for ($i = 1; $i <= $totalPages; $i++) {
            echo "<button onclick='changePage($i)'>$i</button> ";
        }
        echo "</td></tr>";
    } else {
        echo "<tr><td colspan='7'>No records found</td></tr>"; // Adjust colspan to match table headers
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Attendance</title>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        @media print {
            .no-print { 
                display: none; 
            }
        }
    </style>
</head>
<body>
<a href="adminForm.php" class="link back no-print"><i class="fas fa-arrow-left"></i></a><br><br>

<div class="filter-container no-print">
  <form id="filterForm" method="POST" action="javascript:void(0);">
    <label for="department">Department:</label>
    <select id="department" name="department">
      <option value="">All</option>
      <option value="IT">IT</option>
      <option value="Business">Business</option>
      <option value="Engineering">Engineering</option>
    </select>

    <label for="year">Year:</label>
    <select id="year" name="year">
      <option value="">All</option>
      <option value="1st">1st Year</option>
      <option value="2nd">2nd Year</option>
      <option value="3rd">3rd Year</option>
      <option value="4th">4th Year</option>
    </select>

    <label for="section">Section:</label>
    <input type="text" id="section" name="section">

    <label for="timeIn">Time In:</label>
    <input type="time" id="timeIn" name="timeIn">

    <!-- Date filter (calendar) -->
    <label for="date">Date:</label>
    <input type="date" id="date" name="date">

    <!-- Live Search -->
    <label for="search">Search:</label>
    <input type="text" id="search" name="searchTerm" placeholder="Search by ID or Name">

    <!-- Records limit selection -->
    <label for="limit">Limit:</label>
    <select id="limit" name="limit">
      <option value="50">50</option>
      <option value="100">100</option>
    </select>
  </form>
</div>

<!-- Print and Save buttons are hidden in print view -->
<button class="btn btn-sm btn-primary no-print" onclick="printData()">Print Data</button>
<button class="btn btn-sm btn-primary no-print" onclick="saveAsImage()">Save as Image</button>

<div class="attendance-table">
  <table border="1">
    <thead>
      <tr>
        <th>#</th> <!-- Record number column -->
        <th>ID <button onclick="sortTable('student_id')"><i class='fas fa-sort'></i></button></th>
        <th>Full Name <button onclick="sortTable('name')"><i class='fas fa-sort'></i></button></th>
        <th>Department</th>
        <th>Year</th>
        <th>Section</th>
        <th>Time In <button onclick="sortTable('timeIn')"><i class='fas fa-sort'></i></button></th>
      </tr>
    </thead>
    <tbody id="attendanceTable">
      <!-- Filtered data will be inserted here via AJAX -->
    </tbody>
  </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
<script>
    // Function to automatically set today's date in the date input
    function setTodaysDate() {
        const today = new Date().toISOString().substr(0, 10); // Get today's date in 'YYYY-MM-DD' format
        document.getElementById('date').value = today;
    }

    // Variables for tracking the current sort column and order
    let sortBy = 'student_id'; // Default sort column
    let order = 'ASC'; // Default sort order
    let currentPage = 1; // Default current page

    // Function to handle live filtering using AJAX
    function filterAttendance() {
        $.ajax({
            url: "",  // Use the current file for AJAX handling
            method: "POST",
            data: {
                action: 'filter',
                department: $('#department').val(),
                year: $('#year').val(),
                section: $('#section').val(),
                timeIn: $('#timeIn').val(),
                date: $('#date').val(), // Pass date filter
                searchTerm: $('#search').val(), // Pass search term
                limit: $('#limit').val(), // Pass records limit
                page: currentPage, // Pass current page
                sortBy: sortBy, // Send sort by field
                order: order // Send sort order
            },
            success: function(data) {
                $('#attendanceTable').html(data); // Update the table with the filtered data
            }
        });
    }

    // Function to change the current page
    function changePage(page) {
        currentPage = page; // Update the current page
        filterAttendance(); // Refetch the data for the new page
    }

    // Function to sort the table based on a specific column
    function sortTable(column) {
        if (sortBy === column) {
            order = (order === 'ASC') ? 'DESC' : 'ASC'; // Toggle order if the same column is clicked
        } else {
            sortBy = column;
            order = 'ASC'; // Set order to ASC if a new column is clicked
        }
        filterAttendance();
    }

    // Function to print the table data
    function printData() {
        window.print();
    }

    // Function to save the table data as an image
    function saveAsImage() {
        html2canvas(document.querySelector('.attendance-table')).then(canvas => {
            let link = document.createElement('a');
            link.download = 'attendance_data.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    }

    $(document).ready(function() {
        // Set today's date automatically
        setTodaysDate();

        // Trigger the filterAttendance function when any filter input changes
        $('#department, #year, #section, #timeIn, #date, #search, #limit').on('input change', function() {
            filterAttendance();
        });

        // Initially load all data (with today's date by default)
        filterAttendance();
    });
</script>
</body>
</html>
