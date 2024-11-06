<?php

  require 'database.php';
  require 'functions.php';

  if(isset($_POST['change'])) {

    $email = clean($_POST['email']);

    $newpass = clean($_POST['newpass']);
    $token = clean($_POST['token_verify']);

    if(!empty($token)){

      $token_verify="SELECT token from users WHERE token='$token' limit 1 ";
      $token_verify_run=mysqli_query($con,$token_verify);

      if(mysqli_num_rows( $token_verify_run) >0){


          $new_password="UPDATE users set password = '$newpass'  WHERE token='$token' limit 1 ";
          $new_password_run=mysqli_query($con,$new_password);

          if($new_password_run){
            $new_token_verify = bin2hex(random_bytes(16));
            $new_token="UPDATE users set token = '$new_token_verify'  WHERE token='$token' limit 1 ";
          $new_token_run=mysqli_query($con,$new_token);

            $_SESSION['prompt']="Your Password Successfully Changed, You can Now Log In.";
          header("location:index.php?email=$email & token=$token");
          exit;

          }
          else{
            $_SESSION['errprompt']="Your Password Didn't Changed, Something Went Wrong.";
            header("location:index.php?email=$email & token=$token");
            exit;
          }
      }
      else{
        $_SESSION['errprompt']="You can't change Password, Please resend your Email again.";
        header("location:forgotpass.php");
        exit;
      }
    }
   else{
    $_SESSION['errprompt']="You can't change Password.";
    header("location:newpass.php");
    exit;
   }

  }
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Change Password</title>
  <link rel="icon" href="icon.jpg">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css" rel="stylesheet">
</head>
<link rel="stylesheet" href="styles.css">
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
<body class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4 shadow-lg box">
<form method="post">
<input type="hidden" name="token_verify" value="<?php if(isset($_GET['token']))
          {echo$_GET['token'];}?>" >
   
   <a href="forgotpass.php" class="text-success"><i class="fas fa-arrow-left"></i> Back</a>

<h1 class="text-center mb-4">Change Password</h1>
<p class="darktext">Always remember your Account information specially your Password.</p>
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
<h5><div class='font text-center'><?php if(isset($_GET['email']))
          {echo$_GET['email'];}?></h5>


        <div class="form-floating mb-3">
                <input type="text" class="form-control" id="email" name="newpass" placeholder="New Password" required>
                <label for="email">New Password</label>
            </div>

            <div class="d-grid gap-2">
    <button type="submit" name="change" class="btn btn-primary login">Save</button>
</form>
  </div>
</body>
</html>