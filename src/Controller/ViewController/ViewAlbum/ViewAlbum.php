<?php

// namespace ViewController\;
namespace Src\Controller\ViewController\ViewAlbum;


use PDO;
use Src\Layout\NavBarClass;
use Src\TableGateways\AlbumGateway;
/**
 * get album shortUrl and display Album
 */
class ViewAlbum extends AlbumGateway{
    protected PDO $db;
    protected $short_url;
    public function __construct(String $short_url, PDO $db) {
        parent::__construct($db);
        $this->short_url = $short_url;
        $this->db = $db;
        
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
        $navBar = NavBarClass::navBar;
        return<<<HTML
            
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
            <nav class="sidebar sidebar-offcanvas" id="sidebar">
                <ul class="nav">
                <li class="nav-item">
                    <a class="nav-link" href="home.php">
                    <i class="mdi mdi-home menu-icon"></i>
                    <span class="menu-title">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#ui-basic" aria-expanded="false" aria-controls="ui-basic">
                    <i class="mdi mdi-upload-multiple menu-icon"></i>
                    <span class="menu-title">ADD NEW</span>
                    <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="ui-basic">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="newsong.php"><i class="mdi mdi-music menu-icon"></i> Music </a></li>
                        <li class="nav-item"> <a class="nav-link" href="newalbum.php"> <i class="mdi mdi-album menu-icon"></i> Album</a></li>
                        <li class="nav-item"> <a class="nav-link" href="newvideo.php"> <i class="mdi mdi-video menu-icon"></i> Video</a></li>
                    </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pages/forms/basic_elements.php">
                    <i class="mdi mdi-view-headline menu-icon"></i>
                    <span class="menu-title">Form elements</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pages/charts/chartjs.php">
                    <i class="mdi mdi-chart-pie menu-icon"></i>
                    <span class="menu-title">Charts</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pages/tables/basic-table.php">
                    <i class="mdi mdi-grid-large menu-icon"></i>
                    <span class="menu-title">Tables</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="pages/icons/mdi.php">
                    <i class="mdi mdi-emoticon menu-icon"></i>
                    <span class="menu-title">Icons</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="collapse" href="#auth" aria-expanded="false" aria-controls="auth">
                    <i class="mdi mdi-account menu-icon"></i>
                    <span class="menu-title">User Pages</span>
                    <i class="menu-arrow"></i>
                    </a>
                    <div class="collapse" id="auth">
                    <ul class="nav flex-column sub-menu">
                        <li class="nav-item"> <a class="nav-link" href="pages/samples/login.php"> Login </a></li>
                        <li class="nav-item"> <a class="nav-link" href="pages/samples/login-2.php"> Login 2 </a></li>
                        <li class="nav-item"> <a class="nav-link" href="pages/samples/register.php"> Register </a></li>
                        <li class="nav-item"> <a class="nav-link" href="pages/samples/register-2.php"> Register 2 </a></li>
                        <li class="nav-item"> <a class="nav-link" href="pages/samples/lock-screen.php"> Lockscreen </a></li>
                    </ul>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="documentation/documentation.php">
                    <i class="mdi mdi-file-document-box-outline menu-icon"></i>
                    <span class="menu-title">Documentation</span>
                    </a>
                </li>
                </ul>
            </nav>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-header">New album</div>
                                <div class="card-body">
                                   <div class="alert alert-success status-msg" style="display: none;">Post Created Successfully</div>
                                   <form class="forms-sample">
                                    <div class="form-group">
                                        <label for="postTitle">Title</label>
                                        <font size="0.6" id="titleCap" style="display: block; display: none; text-align: right; float: right;">
                                            Required</font>
                                        <input type="text" class="form-control" id="album_title" placeholder="Song Title">
                                    </div>
                                    <div class="form-group">
                                        <label for="postTitle">Artist</label>
                                        <font size="0.6" id="artistCap" style="display: block; display: none; text-align: right; float: right;">
                                            Required</font>
                                        <input type="text" class="form-control" id="artist" placeholder="Artist">
                                    </div>


                                    <div class="form-group">
                                        <label for="postBody">About this Song</label>
                                        <font size="0.6" id="AboutCap" style="display: block; display: none; text-align: right; float: right;">
                                            Required</font>
                                        <textarea class="form-control" id="about_album" rows="16"></textarea>
                                    </div>
                                    <div class="form-group">
                                        <label for="postBody">YEAR OF RELEASE</label>
                                        <font size="0.6" id="yearCap" style="display: block; display: none; text-align: right; float: right;">
                                            Required</font>
                                        <input type="number" class="form-control" id="album_year" placeholder="2020">
                                    </div>
                                    <div class="form-group">
                                        <label for="postTitle">Uploaded by</label>
                                        
                                        <input type="text" class="form-control" id="author" value="Leccel" disabled>
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
                <footer class="footer">
                    <div class="d-sm-flex justify-content-center justify-content-sm-between">
                        <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2018 <a href="https://www.urbanui.com/" target="_blank">Urbanui</a>. All rights reserved.</span>
                        <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="mdi mdi-heart text-danger"></i></span>
                    </div>
                </footer>
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