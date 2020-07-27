<?php
  require './session.php';
  require '../bootstrap.php';
  use Src\Layout\LayoutClass;
  use Src\TableGateways\AlbumGateway;
  $pageNo = isset($_GET['pages']) ? (int) $_GET['pages'] : 1;
  $gateway = new AlbumGateway($dbConnection);
  $res = $gateway->getPages($pageNo);
  $request_url = "http://127.0.0.1:8090/admin/albums.php";
  $prev = isset($res["links"]["prev"]) ? $request_url.str_replace("/", "=", "?".$res["links"]["prev"]) : "#";
  $next = isset($res["links"]["next"]) ? $request_url.str_replace("/", "=", "?".$res["links"]["next"]) : "#";
  $prev
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
          
          <div class="row-border">
            <div class="col-md-12 grid-margin">
              <div class="d-flex justify-content-between flex-wrap">
                <div class="d-flex align-items-end flex-wrap">
                  <div class="mr-md-3 mr-xl-5">
                    <h2>Manage album Uploads</h2>
                    <p class="mb-md-0">View, Edit or Delete Media</p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <hr>
          <p class="text-center text-black-50 font-weight-bold" style="font-family: monospace; font-size:large">Page <?=$res["meta"]["current_page"] ?> out of <?=$res["meta"]["total_pages"] ?></p>
          <hr>
          <div class="row">
          <?php 
            foreach ($res["data"] as $album) {
              $image = isset($album["images"][0]) ? $album["images"][0] : "uploads/images/20200531111530182851488.jpg";
              
              echo <<<HTML
                    <div class="col-md-4 grid-margin stretch-card">
                      <div class="card card-outline-primary card-rounded card-inverse-info grid-margin stretch-card">
                          <div class="card-header">
                                <h3 style="font-family: monospace;" class="text-center">
                                    <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon">
                                        <i class="mdi mdi-album text-dark"></i>
                                    </button>    
                                    $album[album_name]
                                </h3>
                          </div>
                          <div class="card-body">
                                <div class="card-img-holder">
                                    <img class="card-img" src="../$image" alt="Love the Way You are Image Banner">
                                </div>
                          </div>
                          <div class="card-footer">
                              <div class="text-right" id="$album[id]" >
                                <a href="/admin/view/album/$album[short_url]" class="text-decoration-none">
                                    <button type="button" class="btn btn-info btn-rounded btn-icon edit">
                                        <i class="mdi mdi-pencil"></i>
                                    </button>
                                </a>
                                <button type="button" class="btn btn-danger btn-rounded btn-icon delete" name="$album[album_name]">
                                    <i class="mdi mdi-delete"></i>
                                </button>
                              </div>
                          </div>
                      </div>
                    </div>
              HTML;
            }
          ?>
          </div>
          
        </div>
        <hr>
            <nav class="nav d-flex align-items-center justify-content-center" aria-label="Album-pager">
              <ul class="pagination">
                <li class="page-item">
                  <a class="page-link" href="<?=$prev ?>">
                    <i class="mdi mdi-arrow-left-bold"></i>
                  </a>
                </li>

                <li class="page-item">
                  <a class="page-link" href="<?=$next ?>">
                    <i class="mdi mdi-arrow-right-bold"></i>
                  </a>
                </li>
              </ul>

            </nav>
            <div class="d-flex align-items-center justify-content-center pad2x">
              <p class="pager">Page <?=$res["meta"]["current_page"] ?> of <?=$res["meta"]["total_pages"] ?></p>
            </div>
        <hr>
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
  <script src="js/album/manager/albumManager.js"></script>
  <!-- endinject -->
  <!-- End custom js for this page-->
</body>
</html>