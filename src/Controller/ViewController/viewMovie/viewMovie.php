<?php

// namespace ViewController\;
namespace Src\Controller\ViewController\ViewMovie;

use PDO;
use Src\Layout\LayoutClass;
use Src\TableGateways\MovieGateway;

/**
 * get album shortUrl and display Album
 */
class ViewMovie extends MovieGateway
{
 protected $db;
 protected $_short_url;
 public function __construct(String $short_url, PDO $db)
 {
  parent::__construct($db);
  $this->short_url = $short_url;
  $this->db        = $db;

 }

 public function getMovie($short_url)
 {
  $res = $this->findByUrl($short_url);
  if (!$res) {
   return null;
  }
  $res = $this->getByUrl($short_url);
  return $res;
 }

 public function bodyParser()
 {
  $response = $this->getMovie($this->short_url);

  if (!$response) {
   return <<<HTML
                <h1>Page not found. 404</h1>
            HTML;
  }
  $images = "";
  foreach ($response["images"] as $image) {
      $images .= <<<HTML
            <li tabindex="0" class="el-upload-list__item is-ready">
                <img src="/$image" alt="" class="el-upload-list__item-thumbnail">
            </li>
 HTML;
  }
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
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
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
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="text-center" style="font-family:monospace">
                                        <i class="mdi mdi-pen"></i>
                                        $response[video_name]
                                    </h3>
                                </div>
                                <div class="card-body">
                                   <div class="alert alert-success status-msg" style="display: none;">Video Added Successfully</div>
                                   <form class="forms-sample">
                                    <div class="form-group">
                                        <label for="postTitle">Title</label>
                                        <font size="0.6" id="titleCap" style="display: block; display: none; text-align: right; float: right;">
                                            Required</font>
                                        <input type="text" value="$response[video_name]" class="form-control" id="video_title" placeholder="Video Title">
                                    </div>
                                    <div class="form-group">
                                        <label for="postTitle">Category</label>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="Category" id="optionsRadios1" value="HollyWood" >
                                                HollyWood
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="Category" id="optionsRadios1" value="NollyWood">
                                                NollyWood
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="Category" id="optionsRadios1" value="BollyWood">
                                                BollyWood
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <label class="form-check-label">
                                                <input type="radio" class="form-check-input" name="Category" id="optionsRadios1" value="Others" checked>
                                                Others?
                                            </label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label for="postBody">About Video</label>
                                        <font size="0.6" id="AboutCap" style="display: block; display: none; text-align: right; float: right;">
                                            Required</font>
                                        <textarea class="form-control" id="about_video" rows="16">$response[video_details]</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="postTitle">Uploaded by</label>
                                        
                                        <input type="text" class="form-control" id="author" value="$response[uploaded_by]" disabled>
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
                                    
                                    <div class="form-group">
                                        <input type="checkbox" name="popular" class="form-check-success" id="popular"> Add to popular?
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
    <script src="/admin/js/albumController.js"></script>
    <!-- endinject:js for this page -->
</body>

</html>

HTML;

 }

}
