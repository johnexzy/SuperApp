<?php

// namespace ViewController\;
namespace Src\Controller\ViewController\ViewAlbum;

use PDO;
use Src\Layout\LayoutClass;
use Src\TableGateways\AlbumGateway;

/**
 * get album shortUrl and display Album
 */
class ViewAlbum extends AlbumGateway
{
 protected $db;
 protected $_short_url;
 public function __construct(String $short_url, PDO $db)
 {
  parent::__construct($db);
  $this->short_url = $short_url;
  $this->db        = $db;

 }

 public function getAlbum($short_url)
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
  $response = $this->getAlbum($this->short_url);

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
  $popular = (int) $response["popular"] !== 0 ? "checked" : "";
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
                                <input type="hidden" value="$response[album_key]" class="key">
                                <input type="hidden" value="$response[id]" class="id">
                                <div class="card-header">
                                    <h3 class="text-center" style="font-family:monospace">
                                        <i class="mdi mdi-pencil"></i>
                                        $response[album_name]
                                    </h3> 
                                </div>
                                <div class="card-body">
                                   <div class="alert alert-success status-msg" style="display: none;">Updated Successfully</div>
                                   <form class="forms-sample">
                                    <div class="form-group">
                                        <label for="postTitle">Title</label>
                                        <font size="0.6" id="titleCap" style="display: block; display: none; text-align: right; float: right;">
                                            Required</font>
                                        <input type="text" class="form-control" id="album_title" placeholder="Song Title" value="$response[album_name]">
                                    </div>
                                    <div class="form-group">
                                        <label for="postTitle">Artist</label>
                                        <font size="0.6" id="artistCap" style="display: block; display: none; text-align: right; float: right;">
                                            Required</font>
                                        <input type="text" class="form-control" id="artist" placeholder="Artist" value="$response[artist]">
                                    </div>


                                    <div class="form-group">
                                        <label for="postBody">About this Song</label>
                                        <font size="0.6" id="AboutCap" style="display: block; display: none; text-align: right; float: right;">
                                            Required</font>
                                        <textarea class="form-control" id="about_album" rows="16">$response[album_details]</textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="postBody">YEAR OF RELEASE</label>
                                        <font size="0.6" id="yearCap" style="display: block; display: none; text-align: right; float: right;">
                                            Required</font>
                                        <input type="number" class="form-control" id="album_year" placeholder="2020" value="$response[year]">
                                    </div>
                                    <div class="form-group">
                                        Add to Trending:
                                        <div class="d-flex align-items-center">
                                            <p class="mr-2 font-weight-medium monthly check-box-label text-active">OFF</p>
                                            <label class="toggle-switch toggle-switch">
                                            <input type="checkbox" id="toggle-switch" class="popular" $popular>
                                            <span class="toggle-slider round"></span>
                                            </label>
                                            <p class="ml-2 font-weight-medium yearly check-box-label">ON</p>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary btn-icon-text" id="handleSubmit">
                                            <i class="mdi mdi-content-save-all btn-icon-prepend"></i>
                                            Save All
                                        </button>
                                    </div>
                                    
                                    <hr>
                                    <div class="card-header">
                                        <h3 class="text-center" style="font-family:Sans serif">Alter Media</h3>
                                    </div>
                                    <hr>
                                    <div class="form-group">
                                        <ul class="el-upload-list el-upload-list--picture-card image-list">
                                            $images
                                        </ul>
                                        <div class="del-thumbnail item-delete">
                                            <i class="mdi mdi-24px mdi-delete"></i>
                                        </div>
                                        <input type="file" name="img[]" class="file-upload-default image-upload" accept="image/*" multiple>
                                        <div class="el-upload el-upload--picture-card openfile">
                                            <i class="mdi mdi-48px mdi-image-plus"></i>
                                        </div>
                                        <div class="mt-5">
                                            <div class="progress image-upload-progress" style="height: 10px; width:300px; font-weight: 800; font-size: 14px; display:none">
                                                <div class="progress-bar image-bar" style="font-size:0.8em; color:rgb(251, 253, 255)"></div> <i class="image-percent" style="font-size:9px"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <!-- <div class="form-group">
                                        <ul class="el-upload-list el-upload-list--picture-card audio-active">

                                        </ul>
                                        <div class="del-song item-delete" style="display: none;">
                                            <i class="mdi mdi-24px mdi-delete"></i>
                                        </div>

                                        <input type="file" name="audio[]" class="file-upload-default audio-upload" accept="audio/*" multiple>
                                        <div class="el-upload el-upload--picture-card openfile">
                                            <i class="mdi mdi-48px mdi-music-note-plus"></i>
                                        </div>
                                    </div>
                                    <hr> -->
                                </form>
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
    <script src="/admin/js/albumUpdate.js"></script>
    <!-- endinject:js for this page -->
</body>

</html>

HTML;

 }

}
