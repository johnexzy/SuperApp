<?php
  require './session.php';
  require '../bootstrap.php';
  use Src\Layout\LayoutClass;
  use Src\TableGateways\MusicGateway;

  $gateway = new MusicGateway($dbConnection);
  $res = $gateway->getAll();
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
          <?php 
            foreach ($res as $music) {
              $image = isset($music["images"][0]) ? $music["images"][0] : "uploads/images/20200531111530182851488.jpg";
              // foreach ($music["images"] as $value) {
              //   $image = $value;
              // }
              echo <<<HTML
                    <div class="col-md-4 grid-margin stretch-card">
                      <div class="card card-outline-primary card-rounded card-inverse-info grid-margin stretch-card">
                          <div class="card-header">
                                <h3 style="font-family: monospace;" class="text-center">
                                    <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon">
                                        <i class="mdi mdi-music text-dark"></i>
                                    </button>    
                                    $music[music_name]
                                </h3>
                          </div>
                          <div class="card-body">
                                <div class="card-img-holder">
                                    <img class="card-img" src="../$image" alt="Love the Way You are Image Banner">
                                </div>
                          </div>
                          <div class="card-footer">
                              <div class="text-right" id="$music[id]" >
                                <a href="" class="text-decoration-none">
                                    <button type="button" class="btn btn-info btn-rounded btn-icon edit">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                </a>
                                <button type="button" class="btn btn-danger btn-rounded btn-icon delete" name="$music[music_name]">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                                
                              </div>
                          </div>
                      </div>
                    </div>
              HTML;
            }
          ?>
            <div class="col-md-4 grid-margin stretch-card">
              <div class="card card-outline-primary card-rounded card-inverse-info grid-margin stretch-card">
                  <div class="card-header">
                        <h3 style="font-family: monospace;" class="text-center">
                            <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon">
                                <i class="mdi mdi-music text-dark"></i>
                            </button>    
                            Love the way you are
                        </h3>
                  </div>
                  <div class="card-body">
                        <div class="card-img-holder">
                            <img class="card-img" src="../uploads/images/20200614172008989640171.jpg" alt="Love the Way You are Image Banner">
                        </div>
                  </div>
                  <div class="card-footer">
                      <div class="text-right">
                        <a href="" class="text-decoration-none">
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
  <script src="js/managemusic.js"></script>
  <!-- endinject -->
  <!-- End custom js for this page-->
</body>

</html>

