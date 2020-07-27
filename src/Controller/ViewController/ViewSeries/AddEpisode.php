<?php

// namespace ViewController\;
namespace Src\Controller\ViewController\ViewSeries;

use Src\Layout\LayoutClass;

/**
 * get album shortUrl and display Album
 */
class AddEpisode
{
 protected $season_key;
 protected $series_name;
 public function __construct($season_key, $series_name)
 {
    $this->season_key = $season_key;
    $this->series_name = $series_name;

 }

 public function bodyParser()
 {
 
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
            <input type="hidden" value="$this->season_key" class="key">
            <input type="hidden" value="$this->series_name" class="series-name">
            <!-- End Variables -->

            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-header">Add New Episode to $this->series_name</div>
                                <div class="card-body">
                                   <div class="alert alert-success status-msg" style="display: none;">Episode Added Successfully</div>
                                   <form class="forms-sample">
                                    <div class="form-group">
                                        <label for="postTitle">Episode Number</label>
                                        <input type="number" class="form-control" id="ep_number" placeholder="2">
                                    </div>

                                    <div class="form-group">
                                        <label for="postBody">About Episode(Short Story)</label>
                                        <textarea class="form-control" id="about_video" rows="16"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <ul class="el-upload-list el-upload-list--picture-card image-list">

                                        </ul>
                                        <div class="del-thumbnail item-delete" style="display: none;">
                                            <i class="mdi mdi-24px mdi-delete"></i>
                                        </div>
                                        <input type="file" name="img[]" class="file-upload-default image-upload" accept="image/*" multiple>
                                        <div class="el-upload el-upload--picture-card openfile">
                                            <i class="mdi mdi-48px mdi-image-plus"></i>
                                        </div>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <ul class="el-upload-list el-upload-list--picture-card video-active">
                                            
                                        </ul>
                                        
                                        
                                        <input type="file" name="video" class="file-upload-default video-upload" accept="video/*">
                                        <div class="el-upload el-upload--picture-card openfile">
                                            <i class="mdi mdi-48px mdi-video"></i>
                                        </div>
                                    </div>
                                </form>
                                </div>
                                <div class="card-footer">
                                    <button type="button" class="btn btn-primary btn-icon-text" id="handleSubmit"><i
                                                class="mdi mdi-upload btn-icon-prepend"></i>
                                            Upload</button>
                                    <button class="btn btn-light">Cancel</button>
                                </div>
                                <div class="progress" style="height: 40px; font-weight: 800; font-size: 18px;">
                                    <div class="progress-bar" ></div>
                                </div>
                            </div>
                        </div>
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
    <script src="/admin/js/episode/controller/episodeController.js"></script>
    <!-- endinject:js for this page -->
</body>

</html>

HTML;

 }

}
