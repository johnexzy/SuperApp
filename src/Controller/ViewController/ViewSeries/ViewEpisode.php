<?php

namespace Src\Controller\ViewController\ViewSeries;

use PDO;
use Src\Layout\LayoutClass;
use Src\TableGateways\EpisodeGateway;

/**
 * get episode url and display details about an episode
 */
class ViewEpisode extends EpisodeGateway
{
	protected $db;
	protected $_short_url;
	protected $_series_name;
	public function __construct($short_url, $_series_name, PDO $db)
	{
		parent::__construct($db);
	    $this->short_url = $short_url;
	    $this->db        = $db;
	    $this->series_name = $_series_name;
	}

	public function getEpisode($short_url, $series_name)
	{
		// $res = $this->findByUrl($short_url, $series_name);
	 //    if (!$res) {
	 //        return null;
	 //    }
	    $res = $this->getByUrl($short_url, $series_name);
        return $res;
    }
	
	public function bodyParser()
	{
		$response = $this->getEpisode($this->short_url, $this->series_name);
		if (!$response) {
		   return 
		   <<<HTML
                <h1>Page not found. 404</h1>
            HTML;
  		}
// 		if (!$response) {
//    return <<<HTML
//                 <h1>Page not found. 404</h1>
//             HTML;
//   }
  $images = "";
  foreach ($response["images"] as $image) {
   $images .= <<<HTML
            <li tabindex="0" class="el-upload-list__item is-ready">
                <img src="/$image" alt="" class="el-upload-list__item-thumbnail">
            </li>
 HTML;
  }
  $videos = "";
  foreach ($response["videos"] as $video) {
   $bytes = ceil($video["video_bytes"] / (1024 * 1024));
   $videos .= <<<HTML
                <li class='el-upload-list__item is-ready'>
                    <div class='el-upload-list__item-thumbnail'>
                        name: $response[ep_name]
                        <br>
                        size: <b>$bytes mb</b>
                    </div>
                </li>
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
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                    <input type="hidden" value="$response[ep_key]" class="key">
                                    <input type="hidden" value="$response[id]" class="id">
                                <div class="card-header">
                                    <h3 class="text-center" style="font-family:monospace">
                                        <i class="mdi mdi-pen"></i>
                                        $response[ep_name]
                                    </h3>
                                </div>
                                <div class="card-body">
                                   <div class="alert alert-success status-msg" style="display: none;">Video Added Successfully</div>
                                   <form class="forms-sample">
                                    <div class="form-group">
                                        <label for="postTitle">Title</label>
                                        <font size="0.6" id="titleCap" style="display: block; display: none; text-align: right; float: right;">
                                            Required</font>
                                        <input type="text" value="$response[ep_name]" class="form-control" id="video_title" placeholder="Video Title">
                                    </div>
                                    <div class="form-group">
                                        <label for="postBody">About Video</label>
                                        <font size="0.6" id="AboutCap" style="display: block; display: none; text-align: right; float: right;">
                                            Required</font>
                                        <textarea class="form-control" id="about_video" rows="16">$response[ep_details]</textarea>
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
                                            <div class="progress image-upload-progress" style="height: 10px; width:100% font-weight: 800; font-size: 14px; display:none">
                                                <div class="progress-bar image-bar" style="font-size:0.8em; color:rgb(251, 253, 255)"></div> <i class="image-percent" style="font-size:9px"></i>
                                            </div>
                                        </div>
                                    </div>
                                    <hr>
                                    <!-- <div class="form-group">
                                        <ul class="el-upload-list el-upload-list--picture-card video-active">
                                            $videos
                                        </ul>


                                        <input type="file" name="video" class="file-upload-default video-upload" accept="video/*">
                                        <div class="el-upload el-upload--picture-card openfile">
                                            <i class="mdi mdi-48px mdi-video"></i>
                                        </div>
                                    </div> -->
                                </form>
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

    <!-- endinject -->
    <!-- Inject:js for this page -->
    <script src="/admin/js/movie/updater/movieUpdate.js"></script>
    <!-- endinject:js for this page -->
</body>

</html>

HTML;

 }

}
