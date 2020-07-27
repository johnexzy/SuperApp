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
                                <div class="card-header">New Series</div>
                                <div class="card-body">
                                   <div class="alert alert-success status-msg" style="display: none;">Series Created Successfully</div>
                                   <form class="forms-sample">
                                    <div class="form-group">
                                        <label for="postTitle">Title</label>
                                        <font size="0.6" id="titleCap" style="display: block; display: none; text-align: right; float: right;">
                                            Required</font>
                                        <input type="text" class="form-control" id="series_title" placeholder="series Title">
                                    </div>
                                    <div class="form-group">
                                        <label for="postBody">About This Series</label>
                                        <font size="0.6" id="AboutCap" style="display: block; display: none; text-align: right; float: right;">
                                            Required</font>
                                        <textarea class="form-control" id="about_series" rows="16"></textarea>
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
                                        Add to Trending:
                                        <div class="d-flex align-items-center">
                                            <p class="mr-2 font-weight-medium monthly check-box-label text-active">OFF</p>
                                            <label class="toggle-switch toggle-switch">
                                            <input type="checkbox" id="toggle-switch" class="popular">
                                            <span class="toggle-slider round"></span>
                                            </label>
                                            <p class="ml-2 font-weight-medium yearly check-box-label">ON</p>
                                        </div>
                                    </div>
                                </form>
                                </div>
                                <div class="card-footer">
                                    <button type="button" class="btn btn-primary btn-icon-text" id="handleSubmit"><i
                                                class="mdi mdi-creation btn-icon-prepend"></i>
                                            Create</button>
                                </div>
                                <div class="progress" style="height: 20px; font-weight: 800; font-size: 18px;">
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
    <script src="js/off-canvas.js"></script>
    <script src="js/hoverable-collapse.js"></script>
    <script src="js/template.js"></script>
    <!-- endinject -->
    <!-- Inject:js for this page -->
    <script src="js/series/controller/seriesController.js"></script>
    <!-- endinject:js for this page -->
</body>

</html>