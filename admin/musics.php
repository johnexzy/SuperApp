<?php
  require './session.php';
  require '../bootstrap.php';
  use Src\Layout\LayoutClass;
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>LecceL::Admin</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/base/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
</head>
<body>
  <div class="container-scroller">
    <!-- partial:partials/_navbar.php -->
    <?= LayoutClass::navBar ?>
    <!-- partial -->
    <div class="container-fluid page-body-wrapper">
      <!-- partial:partials/_sidebar.php -->
      <?= LayoutClass::sideBar ?>
      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          
          <div class="row">
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex align-items-end flex-wrap">
                  <div class="mr-md-3 mr-xl-5">
                    <h2>Welcome back, Leccel</h2>
                    <p class="mb-md-0">Your admin dashboard Add and Manage Media.</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card grid-margin stretch-card">
                  <div class="card-header">
                      <div class="card-header-pills">
                        <h3 style="font-family: monospace;" class="text-center">
                            <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon">
                                <i class="mdi mdi-music text-dark"></i>
                            </button>    
                            Love the way you are
                        </h3>
                      </div>
                  </div>
                  <div class="card-body">
                        <img class="card-img" src="../uploads/images/20200614172008989640171.jpg" alt="" srcset="">
                  </div>
                  <div class="card-footer">
                      <div class="text-right">
                        <a href="">
                            <button type="button" class="btn btn-info btn-rounded btn-icon">
                                <i class="mdi mdi-pencil"></i>
                            </button>
                        </a>
                        <button type="button" class="btn btn-danger btn-rounded btn-icon">
                            <i class="mdi mdi-delete"></i>
                        </button>
                        
                      </div>
                  </div>
              </div>
            </div>
            <div class="col-md-4 grid-margin stretch-card">
            
            </div>
          </div>
          
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.php -->
        <?= LayoutClass::footer ?>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
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
  <!-- End custom js for this page-->
</body>

</html>

