<!DOCTYPE html>
<html lang="en">

<?php
require 'bootstrap.php';
use Src\Layout\NavBarClass;
use Src\Layout\FooterClass;
use Src\Layout\CarouselClass;
use Src\Layout\NewsClass;

$navbar = new NavBarClass("./", null);
$carousel = new CarouselClass($dbConnection);
$news = new NewsClass($dbConnection, "./");
$footer = new FooterClass();


echo($navbar->returnNavLayout());

?>

    <!-- End Navbar -->


    <!--------------------------------------
HEADER
--------------------------------------->
    <div class="container">
        <?php
            echo($carousel->returnCarousel());
        ?>        
    </div>
    <div class="container">
        <div class="border p-5">
            <div class="text-center">
                <h5 class="font-weight-bold secondfont">Welcome to <?php echo(getenv("APP_NAME")) ?></h5>
                Get the latest news, movies, musics, Tv-series and entertainments right here.
            </div>       
         </div>
    </div>
    <!-- End Header -->


    <!--------------------------------------
MAIN
--------------------------------------->

    <?php
            echo($news->returnNews("news"));
    
            echo($news->returnNews("tech"));
    
            echo($news->returnNews("sports"));
            echo ($news->returnAllNews());
    ?>
    

    <!--------------------------------------
FOOTER
--------------------------------------->
    <?php
        
        echo($footer->returnFooterLayout());
    ?>
    <!-- End Footer -->

    <!--------------------------------------
JAVASCRIPTS
--------------------------------------->
    <script src="./assets/js/vendor/jquery.min.js" type="text/javascript"></script>
    <script src="./assets/js/vendor/popper.min.js" type="text/javascript"></script>
    <script src="./assets/js/vendor/bootstrap.min.js" type="text/javascript"></script>
    <script src="./assets/js/functions.js" type="text/javascript"></script>
    <script>
        $('.carousel').on('slide.bs.carousel', function(event) {
            var height = $(event.relatedTarget).height();
            var $innerCarousel = $(event.target).find('.carousel-inner');
            $innerCarousel.animate({
                height: height
            });
        });
    </script>
</body>

</html>