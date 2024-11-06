<?php
require 'database.php'; // Include database connection
require 'functions.php'; // Include additional functions

// Check if the request is an AJAX call to update credentials
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $query_admin2 = "SELECT email, password FROM users WHERE user_type = 'admin2'";
    $result_admin2 = mysqli_query($con, $query_admin2);
    $admin2_user = mysqli_fetch_assoc($result_admin2);

    if ($admin2_user) {
        // Return updated credentials as a JSON response
        echo json_encode([
            'email' => $admin2_user['email'],
            'password' => $admin2_user['password']
        ]);
    } else {
        echo json_encode([
            'error' => 'No admin2 user found.'
        ]);
    }
    exit; // Ensure the script stops here for AJAX calls
}

// Fetch admin user type
$query = "SELECT user_type FROM users WHERE id = '" . $con->real_escape_string($_SESSION['userid']) . "'";
$result = $con->query($query);
$user = $result->fetch_assoc();

if (!$user || $user['user_type'] != "admin") {
    header("Location: Logout.php");
    exit;
}

// Fetch admin2 user's email and password (for initial display)
$query_admin2 = "SELECT email, password FROM users WHERE user_type = 'admin2'";
$query_run_admin2 = mysqli_query($con, $query_admin2);
$admin2_user = mysqli_fetch_assoc($query_run_admin2); // Fetch the first admin2 user
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QR Code Attendance</title>
    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
<a href="adminForm.php" class="text-success"><i class="fas fa-arrow-left"></i> Back</a>

<!-- Sharable Link Section -->
<div style="margin-top: 20px;">
    <h3>Shareable Attendance Link</h3>
</div>
<p id="copyMessage" style="color: green; display: none;">Copied to clipboard!</p>

<div style="display: flex; align-items: center; gap: 10px;">
    <!-- Text box to display the link -->
    <input type="text" value="http://localhost/Attendance%20Monitor%20System/shareCam1.php" id="shareLink" style="width: 400px; padding: 5px;" readonly>
    <!-- Copy Button -->
    <button onclick="copyLink()" style="padding: 6px 12px; cursor: pointer;">Copy Link</button>
</div>

<!-- Display admin2 email and password -->
<?php if ($admin2_user): ?>
    <div style="margin-top: 20px;">
        <input type="text" id="email" value="<?=$admin2_user['email'];?>" readonly style="width: 200px; padding: 5px;">
        <input type="text" id="password" value="<?=$admin2_user['password'];?>" readonly style="width: 200px; padding: 5px;">
        <button onclick="copyEmailAndPassword()" style="padding: 6px 12px; cursor: pointer;">Copy Email & Password</button>
    </div>
<?php else: ?>
    <p>No admin2 users found.</p>
<?php endif; ?>

<script>
    // Function to copy the link to clipboard
    function copyLink() {
        var copyText = document.getElementById("shareLink");
        copyText.select(); // Select the text
        copyText.setSelectionRange(0, 99999); // For mobile devices

        // Copy the text inside the text field
        document.execCommand("copy");

        // Show a confirmation message
        document.getElementById("copyMessage").style.display = "block";
        setTimeout(function() {
            document.getElementById("copyMessage").style.display = "none";
        }, 2000); // Hide the message after 2 seconds
    }

    // Function to copy email and password to clipboard
    function copyEmailAndPassword() {
        var email = document.getElementById("email").value;
        var password = document.getElementById("password").value;
        var combinedText = "Email: " + email + "\nPassword: " + password;

        // Create a temporary textarea to hold the combined text
        var tempTextArea = document.createElement("textarea");
        tempTextArea.value = combinedText;
        document.body.appendChild(tempTextArea);
        tempTextArea.select();
        document.execCommand("copy");
        document.body.removeChild(tempTextArea); // Remove the textarea

        // Show a confirmation message
        document.getElementById("copyMessage").style.display = "block";
        setTimeout(function() {
            document.getElementById("copyMessage").style.display = "none";
        }, 2000); // Hide the message after 2 seconds
    }

    // Function to update email and password every 5 minutes via AJAX
    function updateCredentials() {
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "", true); // Send request to the same file
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onload = function() {
            if (xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.email && response.password) {
                    document.getElementById('email').value = response.email;
                    document.getElementById('password').value = response.password;
                } else {
                    console.error('Failed to update credentials:', response.error);
                }
            }
        };
        xhr.send(); // Send the request to update credentials
    }

    // Call the update function every 5 minutes (300000 ms)
    setInterval(updateCredentials, 300000); // 5 minutes interval
</script>

</body>
</html>
