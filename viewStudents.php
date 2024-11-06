<?php
require 'database.php'; // Assuming this contains the connection code to the database

// Check if the user is logged in
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

// AJAX request handling for filtering, sorting, and live search
if (isset($_POST['action']) && $_POST['action'] == 'filter') {
    $sql = "SELECT * FROM users WHERE user_type != 'admin'"; // Exclude admin users

    // Handle filtering
    $department = isset($_POST['department']) ? $_POST['department'] : '';
    $year = isset($_POST['year']) ? $_POST['year'] : '';
    $section = isset($_POST['section']) ? $_POST['section'] : '';
    $search = isset($_POST['search']) ? $_POST['search'] : ''; // Live search input
    $sortBy = isset($_POST['sortBy']) ? $_POST['sortBy'] : 'student_id'; // Default sort by student_id
    $order = isset($_POST['order']) ? $_POST['order'] : 'ASC'; // Default order is ascending
    $limit = isset($_POST['limit']) ? (int)$_POST['limit'] : 10; // Default limit is 10
    $page = isset($_POST['page']) ? (int)$_POST['page'] : 1; // Default page
    $offset = ($page - 1) * $limit; // Calculate offset

    // Add filters
    if (!empty($department)) {
        $sql .= " AND department = '$department'";
    }
    if (!empty($year)) {
        $sql .= " AND year = '$year'";
    }
    if (!empty($section)) {
        $sql .= " AND section = '$section'";
    }
    // Add live search filter for name or student ID
    if (!empty($search)) {
        $sql .= " AND (name LIKE '%$search%' OR student_id LIKE '%$search%')";
    }

    // Add sorting
    $sql .= " ORDER BY $sortBy $order";

    // Add limit and offset for pagination
    $sql .= " LIMIT $limit OFFSET $offset";

    // Execute the query
    $result = mysqli_query($con, $sql);
    
    // Get total records for pagination
    $totalRecordsResult = mysqli_query($con, "SELECT COUNT(*) as total FROM users WHERE user_type != 'admin'");
    $totalRecords = mysqli_fetch_assoc($totalRecordsResult)['total'];
    $totalPages = ceil($totalRecords / $limit);
    
    if (mysqli_num_rows($result) > 0) {
        $recordNumber = $offset + 1; // Start numbering from the correct offset
        while ($row = mysqli_fetch_assoc($result)) {
            echo "<tr>
                    <td>" . $recordNumber++ . "</td>
                    <td>" . $row['student_id'] . "</td>
                    <td>" . $row['name'] . "</td>
                    <td>" . $row['department'] . "</td>
                    <td>" . $row['year'] . "</td>
                    <td>" . $row['section'] . "</td>
                  </tr>";
        }
    } else {
        echo "<tr><td colspan='6'>No records found</td></tr>"; // Adjust colspan to match table headers
    }

    // Output pagination details
    echo "<div class='pagination'>";
    if ($page > 1) {
        echo "<button class='page' data-page='" . ($page - 1) . "'>Previous</button>";
    }

    for ($i = 1; $i <= $totalPages; $i++) {
        echo "<button class='page' data-page='$i'>$i</button>";
    }

    if ($page < $totalPages) {
        echo "<button class='page' data-page='" . ($page + 1) . "'>Next</button>";
    }
    
    echo "</div>";
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <style>
        /* Hide elements when printing */
        @media print {
            .filter-container, .print-btn, .save-btn {
                display: none;
            }
            .attendance-table {
                width: 100%;
            }
        }
    </style>
</head>
<body>
<a href="adminForm.php" class="link back"><i class="fas fa-arrow-left"></i></a><br><br>

<div class="filter-container">
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
    
        <input type="text" id="section" name="section" placeholder="Section">

        <!-- Live search field -->
        <label for="search">Search:</label>
        <input type="text" id="search" name="search" placeholder="Search by Name or ID">

        <label for="limit">Show:</label>
        <select id="limit" name="limit">
            <option value="50">50</option>
            <option value="100">100</option>
        </select>
    </form>
</div>

<!-- Print and Save Buttons -->
<button class="btn btn-sm btn-primary print-btn" onclick="printData()">Print Data</button>
<button class="btn btn-sm btn-success save-btn" id="save-btn">Save as Image</button>

<div class="attendance-table" id="attendanceTable">
    <table border="1">
        <thead>
            <tr>
                <th>#</th>
                <th>ID
                    <button class="btn btn-sm btn-light sort-btn" data-sort="student_id" data-order="ASC">
                        <i class="fas fa-sort"></i>
                    </button>
                </th>
                <th>Full Name
                    <button class="btn btn-sm btn-light sort-btn" data-sort="name" data-order="ASC">
                        <i class="fas fa-sort"></i>
                    </button>
                </th>
                <th>Department</th>
                <th>Year</th>
                <th>Section</th>
            </tr>
        </thead>
        <tbody id="students">
            <!-- Filtered data will be inserted here via AJAX -->
        </tbody>
    </table>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    // Function to handle live filtering, sorting, and live search using AJAX
    function filterAttendance(sortBy = 'student_id', order = 'ASC', page = 1) {
        $.ajax({
            url: "",  // Use the current file for AJAX handling
            method: "POST",
            data: {
                action: 'filter',
                department: $('#department').val(),
                year: $('#year').val(),
                section: $('#section').val(),
                search: $('#search').val(), // Live search input
                limit: $('#limit').val(),
                sortBy: sortBy,
                order: order,
                page: page // Add page
            },
            success: function(data) {
                $('#students').html(data); // Update the table with the filtered data
            }
        });
    }

    // Print function to print data
    function printData() {
        window.print();
    }

    // Save image function
    function saveData() {
        html2canvas(document.querySelector("#attendanceTable")).then(canvas => {
            // Convert the canvas to a data URL
            let link = document.createElement('a');
            link.download = 'attendance_data.png'; // Specify the filename
            link.href = canvas.toDataURL('image/png');
            link.click();
        });
    }

    $(document).ready(function() {
        // Trigger filtering when filter inputs or search input changes
        $('#department, #year, #section, #limit, #search').on('input change', function() {
            filterAttendance();
        });

        // Trigger sorting when sort buttons are clicked
        $('.sort-btn').on('click', function() {
            var sortBy = $(this).data('sort');
            var currentOrder = $(this).data('order');
            var newOrder = currentOrder === 'ASC' ? 'DESC' : 'ASC'; // Toggle order
            $(this).data('order', newOrder); // Update the data-order attribute
            filterAttendance(sortBy, newOrder);
        });

        // Pagination button click
        $(document).on('click', '.page', function() {
            const page = $(this).data('page');
            filterAttendance(undefined, undefined, page);
        });

        // Save button click
        $('#save-btn').on('click', function() {
            saveData();
        });

        // Initially load all data
        filterAttendance();
    });
</script>
</body>
</html>
