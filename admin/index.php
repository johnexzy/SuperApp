<?php
require '../bootstrap.php';
use Src\TableGateways\UserGateway;

session_start();

  // unset($_SESSION['user']);
  if (isset($_SESSION['user'])) {
    header("Location: home.php");
  }
  if (isset($_POST['login'])) {
    $username = stripslashes(strip_tags($_POST['username']));
    $pass = stripslashes(strip_tags($_POST['password']));
    
    $pass = (strlen($pass) < 3) ? '' : $pass;
    if ($pass == '' || $username == '') {
      $msg = "Incorrect user details";
      exit;
    }
    
    $req = new UserGateway($dbConnection);
    $res = $req->Login($username, $pass);
    if ($res["status"] == 1) {
        //starts the session and log in user.
        $_SESSION["user"] = 1;
        header("Location: home.php");

    }
    else{
      $msg = "Wrong Username or Password";
    }
  }

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Majestic Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/base/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- plugin css for this page -->
  <!-- End plugin css for this page -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth px-0">
        <div class="row w-100 mx-0">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left py-5 px-4 px-sm-5">
              <div class="brand-logo mb-5">
                <h3>Leccel.net</h3> 
              </div>
              <?php if (isset($msg)) {
                echo $msg;
              }?>
              <h6 class="font-weight-light">Sign in to continue.</h6>
              <form class="pt-3" method="POST" action="">
                <div class="form-group">
                  <input type="text" class="form-control form-control-lg" name="username" placeholder="Username" required>
                </div>
                <div class="form-group">
                  <input type="password" class="form-control form-control-lg" name="password" placeholder="Password" required>
                </div>
                <div class="mt-3">
                  <button type="submit" class="btn btn-block btn-primary btn-lg font-weight-medium auth-form-btn" name="login">SIGN IN</button>
                </div>
                
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src="vendors/base/vendor.bundle.base.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/hoverable-collapse.js"></script>
  <script src="js/template.js"></script>
  <!-- endinject -->
</body>

</html>
