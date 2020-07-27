<?php

// namespace ViewController\;
namespace Src\Controller\ViewController\ViewSeries;

use PDO;
use Src\Layout\LayoutClass;
use Src\TableGateways\SeasonGateway;

/**
 * get album shortUrl and display Album
 */
class ViewSeason extends SeasonGateway
{
 protected $db;
 protected $_short_url;
 protected $_series_name;
 public function __construct($short_url, $_series_name, PDO $db)
 {
    parent::__construct($db);
    $this->short_url = $short_url;
    $this->db        = $db;
    $this->_series_name = $_series_name;

 }

 public function getSeries($short_url, $series_name)
 {
    $res = $this->findByUrl($short_url, $series_name);
    if (!$res) {
        return null;
    }
    $res = $this->getByUrl($short_url, $series_name);
        return $res;
    }

 public function bodyParser()
 {
  $response = $this->getSeries($this->short_url, $this->_series_name);

  if (!$response) {
   return <<<HTML
                <h1>Page not found. 404</h1>
            HTML;
  }
  
  $cardBody = "";
  foreach ($response["episodes"] as $key=>$eps) {
    $image = isset($eps["images"][0]) ? $eps["images"][0] : "uploads/images/20200531111530182851488.jpg";
    $bytes = isset($eps["videos"][0]["video_bytes"]) ? ceil($eps["videos"][0]["video_bytes"]/(1024*1024)) : "200";
    $cardBody .= <<<HTML
          <div class="col-md-4 grid-margin stretch-card">
            <div class="card card-outline-primary card-rounded card-inverse-info grid-margin stretch-card">
                <div class="card-header">
                      <h3 style="font-family: monospace;" class="text-center">
                          <button type="button" class="btn btn-outline-secondary btn-rounded btn-icon">
                              <i class="mdi mdi-video text-dark"></i>
                          </button>    
                          $eps[ep_name]
                      </h3>
                </div>
                <div class="card-body">
                      <div class="card-img-holder">
                          <img class="card-img" src="/$image" alt="Love the Way You are Image Banner">
                      </div>
                </div>
                <div class="card-footer">
                    <div class="text-left">
                          <i class="mdi mdi-information"></i>
                          $bytes(mb)
                    </div>
                    <div class="text-right" id="$eps[id]" >
                      <a href="/admin/view/movie/$eps[short_url]" class="text-decoration-none">
                          <button type="button" class="btn btn-info btn-rounded btn-icon edit">
                              <i class="mdi mdi-pencil"></i>
                          </button>
                      </a>
                      <button type="button" class="btn btn-danger btn-rounded btn-icon delete" name="$eps[ep_name]">
                          <i class="mdi mdi-delete"></i>
                      </button>
                      
                    </div>
                </div>
            </div>
          </div>
    HTML;
  }
  //Generates HTML Components
  $navBar  = LayoutClass::navBar;
  $sideBar = LayoutClass::sideBar;
  $footer  = LayoutClass::footer;
  return <<<HTML

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Leccel Admin</title>
    <!-- plugins:css -->
    <link rel="stylesheet" href="/admin/vendors/mdi/css/materialdesignicons.min.css">
    <link rel="stylesheet" href="/admin/vendors/base/vendor.bundle.base.css">
    <!-- endinject -->
    <!-- inject:css -->
    <link rel="stylesheet" href="/admin/css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="/admin/images/favicon.png" />
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.php -->
        $navBar
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.php -->
            $sideBar
            <!-- partial -->

            <!-- Useable Variables-->
            <input type="hidden" value="$response[season_key]" class="key">
            <input type="hidden" value="$response[id]" class="id">
            <input type="hidden" value="$response[series_name]" class="series-name">
            <!-- End Variables -->

            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="border border-info pt-3 mb-4">
                        <div class="col-md-12 grid-margin">
                            <h3 class="text-center text-capitalize">$response[series_name] - $response[season_name]</h3>
                            <p class="text-center mt-3">Add, Edit or Delete Episodes</p>
                        </div>
                    </div>
                    <div class="row">
                        $cardBody
                    </div>
                    <hr>
                    <div class="col-md-12 grid-margin text-center">
                        <button type="button" class="btn btn-primary btn-lg btn-block">
                            <i class="mdi mdi-plus"></i>                      
                            New Episode
                        </button>
                    </div>
                        
                </div>
                <!-- partial:partials/_footer.php -->
                $footer
                <!-- partial -->
            </div>
        </div>
    </div>
    <!-- plugins:js -->
    <script src="/admin/vendors/base/vendor.bundle.base.js"></script>
    <!-- endinject -->

    <!-- inject:js -->
    <script src="/admin/js/off-canvas.js"></script>
    <script src="/admin/js/hoverable-collapse.js"></script>
    <script src="/admin/js/template.js"></script>
    <!-- endinject -->
    <!-- Inject:js for this page -->
    <script src="/admin/js/movieUpdate.js"></script>
    <!-- endinject:js for this page -->
</body>

</html>

HTML;

 }

}
