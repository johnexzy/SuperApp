<?php



namespace Src\Layout;

/**
 * Get response detail and Generate View
 *
 * @author JohnOba
 */

use Src\Layout\NavBarClass;
use Src\Layout\FooterClass;
use Src\Layout\ArticleClass;
class Details {
    private $detailArray = null;
    private $root = null;
    private  $db;
    public function __construct(Array $detailArray, String $root, $db) {
        $this->detailArray = $detailArray;
        $this->root = $root;
        $this->db = $db;
    }
    //Gather all required Components and build up a view
    public function proccessView($group) {
        
        $nav = new NavBarClass($this->root, null);    

        $nav = $nav->returnNavLayout();
        $body = new ArticleClass($this->detailArray, $this->root, $this->db);
        $body = $body->returnLayout($group);
        $footer = new FooterClass;
        $footer = $footer->returnFooterLayout();
        // echo $nav.$body.$footer;
        return '<!DOCTYPE html>
                <html lang="en">
                '.$nav.$body.$footer.'
                    <script src="'.$this->root.'assets/js/vendor/jquery.min.js" type="text/javascript"></script>
                    <script src="'.$this->root.'assets/js/vendor/popper.min.js" type="text/javascript"></script>
                    <script src="'.$this->root.'assets/js/vendor/bootstrap.min.js" type="text/javascript"></script>
                    <script src="'.$this->root.'assets/js/functions.js" type="text/javascript"></script>
                    <script src="'.$this->root.'assets/js/commentajax.js" type="text/javascript"></script>
                    <script>
                        $(".carousel").on("slide.bs.carousel", function(event) {
                            var height = $(event.relatedTarget).height();
                            var $innerCarousel = $(event.target).find(".carousel-inner");
                            $innerCarousel.animate({
                                height: height
                            });
                        });
                    </script>
                </body>

                </html>
                
        ';
        
        
    }
}
