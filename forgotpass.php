<?php
  require 'database.php';
  require 'functions.php';
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <link rel="icon" href="icon.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<link rel="stylesheet" href="styles.css">
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-lg box">
        <a href="index.php" class="text-success"><i class="fas fa-arrow-left"></i> Back</a>
       <br>
            <h1 class="text-center mb-4">Forgot Password</h1>
            <p class="darktext">Enter your Email Account so we can send you a message to change your Password.</p>
            <?php
if(isset($_SESSION['prompt'])) {
  showPrompt();
}

if(isset($_SESSION['errprompt'])) {
  showError();
}

unset($_SESSION['prompt']);
mysqli_close($con);
unset($_SESSION['errprompt']);
?>
<form action="emailsent.php" method="POST">
        <div class="form-floating mb-3">
                <input type="email" class="form-control" id="email" name="email" placeholder="Email" required>
                <label for="email">Email</label>
            </div>

            <div class="d-grid gap-2">
    <button type="submit" name="forgot" class="btn btn-primary login">Send To My Email</button>
</div><br>

</form>
  </div>
</body>
</html>
