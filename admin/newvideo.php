<?php
require '../bootstrap.php';
use Src\Layout\LayoutClass;

require './session.php';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Leccel Admin</title>
    <!-- plugins:css -->

    <link rel="stylesheet" href="vendors/mdi/css/materialdesignicons.min.css">

    <link rel="stylesheet" href="vendors/base/vendor.bundle.base.css">

    <!-- endinject -->
    <!-- plugin css for this page -->
    <!-- End plugin css for this page -->
    <!-- inject:css -->
    <link rel="stylesheet" href="css/style.css">
    <!-- endinject -->
    <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body>
    <div class="container-scroller">
        <!-- partial:partials/_navbar.php -->
        <?=LayoutClass::navBar ?>
        <!-- partial -->
        <div class="container-fluid page-body-wrapper">
            <!-- partial:partials/_sidebar.php -->
            <?=LayoutClass::sideBar ?>
            <!-- partial -->
            <div class="main-panel">
                <div class="content-wrapper">
                    <div class="row">
                        
                        <div class="col-12 grid-margin stretch-card">
                            <div class="card">
                                <div class="card-header">New Video</div>
                                <div class="card-body">
                                   <div class="alert alert-success status-msg" style="display: none;">Video Added Successfully</div>
                                   <form class="forms-sample">
                                    <div class="form-group">
                                        <label for="postTitle">Title</label>
                                        <font size="0.6" id="titleCap" style="display: block; display: none; text-align: right; float: right;">
                                            Required</font>
                                        <input type="text" class="form-control" id="video_title" placeholder="Video Title">
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
                                        <textarea class="form-control" id="about_video" rows="16"></textarea>
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
                                        <ul class="el-upload-list el-upload-list--picture-card video-active">
                                            
                                        </ul>
                                        
                                        <a href="javascript:;" class="text-decoration-none" id="pickfiles">
                                            <div class="el-upload el-upload--picture-card">
                                                <i class="mdi mdi-48px mdi-video"></i>
                                            </div>
                                        </a>
                                        
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
                                <div class="progress" style="height: 20px; font-weight: 800; font-size: 10px;">
                                    <div class="progress-bar" ></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- partial:partials/_footer.php -->
                <?=LayoutClass::footer ?>
                <!-- partial -->
            </div>
        </div>
    </div>
    <!-- plugins:js -->
    <script src="vendors/base/vendor.bundle.base.js"></script>
    <!-- endinject -->
    

    <!-- inject:js -->

    <!-- endinject -->
    <!-- Inject:js for this page -->
    <script type="text/javascript" src="js/js/plupload.full.min.js"></script>
    <script src="js/movie/controller/movieController.js"></script>
    <!-- endinject:js for this page -->
</body>

</html>