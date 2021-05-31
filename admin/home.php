<?php
require './session.php';
require '../bootstrap.php';

use Src\Layout\LayoutClass;
use Src\TableGateways\MovieGateway;
use Src\TableGateways\MusicGateway;
use Src\TableGateways\SeriesGateway;

//music
$musicCount = MusicGateway::getTotalRecord($dbConnection);
$musicPopularCount = MusicGateway::getTotalRecord($dbConnection, ["popular"=>0]);
$music =  new MusicGateway($dbConnection);
$musicLatest = $music->getAll(1);

//movie
$moviesCount = MovieGateway::getTotalRecord($dbConnection);
$moviePopularCount = MovieGateWay::getTotalRecord($dbConnection, ["popular"=>0]);
$movie = new MovieGateway($dbConnection);
$movieLatest = $movie->getAll(1);

//series
$seriesCount = SeriesGateway::getTotalRecord($dbConnection);
$seriesPopularCount = SeriesGateway::getTotalRecord($dbConnection, ["popular"=>0]);
$series = new SeriesGateway($dbConnection);
$seriesLatest = $series->getAll(1);


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
  <!-- plugin css for this page -->
  <link rel="stylesheet" href="vendors/datatables.net-bs4/dataTables.bootstrap4.css">
  <!-- End plugin css for this page -->
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
        <div class="content-wrapper p-3">
          <div class="col-lg-12 m-0 p-0 search display-none">
            <div class="row">
              <div class="col-md-12 grid-margin stretch-card">
                <div class="card card-square">
                  <div class="card-body dashboard-tabs p-0">
                    <ul class="nav nav-tabs" role="tablist">
                      <li class="nav-item">
                        <a class="nav-link active musicLabel" id="overview-tab" data-toggle="tab" href="#music" role="tab" aria-controls="music" aria-selected="true"><span style="font-size: 16px;">🎧</span></a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link movieLabel" id="sales-tab" data-toggle="tab" href="#movies" role="tab" aria-controls="movies" aria-selected="false"><span style="font-size: 16px;">🎬</span></a>
                      </li>
                      <li class="nav-item">
                        <a class="nav-link seriesLabel" id="purchases-tab" data-toggle="tab" href="#series" role="tab" aria-controls="series" aria-selected="false"><span style="font-size: 16px;">📺</span></a>
                      </li>
                    </ul>
                    <div class="tab-content py-3 px-1">
                      <div class="tab-pane fade show active" id="music" role="tabpanel" aria-labelledby="music-tab">
                        <div class="loader">
                          <div class="d-flex justify-content-center">
                            <img src="/admin/images/loader.gif" alt="">
                          </div>
                        </div>
                        <div id="musicbody"></div>
                      </div>
                      <div class="tab-pane fade" id="movies" role="tabpanel" aria-labelledby="movies-tab">
                        <div class="loader">
                          <div class="d-flex justify-content-center">
                            <img src="/admin/images/loader.gif" alt="">
                          </div>
                        </div>
                        <div id="moviesbody"></div>
                      </div>
                      <div class="tab-pane fade" id="series" role="tabpanel" aria-labelledby="series-tab">
                        <div class="loader">
                          <div class="d-flex justify-content-center">
                            <img src="/admin/images/loader.gif" alt="">
                          </div>
                        </div>
                        <div id="seriesbody"></div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div class="col-lg-12 m-0 p-0 dashboard">
            <div class="row">
              <div class="col-md-12 grid-margin ">
                <div class="d-flex justify-content-between flex-wrap border-dark border-bottom">
                  <div class="d-flex align-items-end flex-wrap">
                    <div class="mr-md-3 mr-xl-5 ">
                      <h2>Welcome back, Leccel</h2>
                      <p class="mb-md-0">Your admin dashboard. Add, Delete and Manage Media.</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-12">
                <div class="row">
                  <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-header">
                        <p class="text-center text-dark font-weight-bold">
                          MUSIC
                        </p>
                      </div>
                      <div class="card-body">
                        <div class="d-flex justify-content-between mt-1">
                          <span class="text-monospace">Total Records: </span>
                          <span class="text-danger text-monospace" style="font-size: 19px;">
                            <?=$musicCount ?>
                          </span>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                          <span class="text-monospace">Trending: </span>
                          <span class="text-danger text-monospace" style="font-size: 19px;">
                            <?=$musicPopularCount ?>
                          </span>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                          <span class="text-monospace">Last Uploaded: </span>
                          <span class="text-danger text-monospace">
                            <?=$musicLatest[0]["music_name"] ?>
                          </span>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                          <span class="text-monospace">Date: </span>
                          <span class="text-danger text-monospace">
                            <?=date("D, d\\t\h M Y", strtotime($musicLatest[0]["created_at"])) ?>
                          </span>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-header">
                        <p class="text-center text-dark font-weight-bold">
                          MOVIES
                        </p>
                      </div>
                      <div class="card-body">
                        <div class="d-flex justify-content-between">
                          <span class="text-monospace">Total Records: </span>
                          <span class="text-danger text-monospace" style="font-size: 19px;">
                            <?=$moviesCount ?>
                          </span>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                          <span class="text-monospace">Trending: </span>
                          <span class="text-danger text-monospace" style="font-size: 19px;">
                            <?=$moviePopularCount ?>
                          </span>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                          <span class="text-monospace">Last Uploaded: </span>
                          <span class="text-danger text-monospace">
                            <?=$movieLatest[0]["video_name"] ?>
                          </span>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                          <span class="text-monospace">Date: </span>
                          <span class="text-danger text-monospace">
                            <?=date("D, d\\t\h M Y", strtotime($movieLatest[0]["created_at"])) ?>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-header">
                        <p class="text-center text-uppercase text-dark font-weight-bold">
                          Series
                        </p>
                      </div>
                      <div class="card-body">
                        <div class="d-flex justify-content-between">
                          <span class="text-monospace">Total Records: </span>
                          <span class="text-danger text-monospace" style="font-size: 19px;">
                            <?=$seriesCount ?>
                          </span>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                          <span class="text-monospace">Trending: </span>
                          <span class="text-danger text-monospace" style="font-size: 19px;">
                            <?=$seriesPopularCount ?>
                          </span>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                          <span class="text-monospace">Last Uploaded: </span>
                          <span class="text-danger text-monospace">
                            <?=$seriesLatest[0]["series_name"] ?>
                          </span>
                        </div>
                        <div class="d-flex justify-content-between mt-1">
                          <span class="text-monospace">Date: </span>
                          <span class="text-danger text-monospace">
                            <?=date("D, d\\t\h M Y", strtotime($seriesLatest[0]["created_at"])) ?>
                          </span>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-lg-6 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                        <h4 class="card-title">Upload chart in Bar</h4>
                        <canvas id="barChart"></canvas>
                      </div>
                    </div>
                  </div>
                  <div class="col-lg-6 grid-margin stretch-card">
                    <div class="card">
                      <div class="card-body">
                        <h4 class="card-title">Upload chart in Pie</h4>
                        <canvas id="pieChart"></canvas>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12">
                    <!-- <div class="d-flex justify-content-center align-content-center"> -->
                      <div class="d-flex justify-content-center align-content-center align-items-center">
                        <canvas id="canvas">

                        </canvas>
                      </div>
                      
                    <!-- </div> -->
                  </div>
                </div>
              </div>
            </div>
          </div>
          
        </div>
        <div class="data:hidden">
          <input type="hidden" id="musicCount" value="<?=$musicCount?>">
          <input type="hidden" id="seriesCount" value="<?=$seriesCount?>">
          <input type="hidden" id="moviesCount" value="<?=$moviesCount?>">
        </div>
        <?=LayoutClass::footer ?>
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
  <script src="js/Chart.min.js"></script>
  
  <!-- endinject -->
  <!-- Custom js for this page -->
  <script src="js/data.js"></script>
  <script src="js/utils.js"></script>
  <script src="js/clockscript.js"></script>
  
</body>

</html>

