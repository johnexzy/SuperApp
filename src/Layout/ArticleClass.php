<?php
namespace Src\Layout;

/**
 * Build the main UI of the article.
 * This Can be used with CarouselDetail and NewsDetail
 * if($group == carousel || $group == News)
 *
 * @author hp
 */
use Src\Layout\NewsClass;
use Src\Logic\CheckDate;
class ArticleClass {
    private $getArticle = null;
    private  $root = null;
    private $db;
    public function __construct(Array $getArticle, $root, $db) {
        $this->getArticle = $getArticle;
        $this->root = $root;
        $this->db = $db;
    }
    function returnLayout($group) {
        
        if ($group == "carousel") {
            $_body = "carousel_body";
            $_title = "carousel_title";
            $_image = "carousel_image";
            $_key = "carousel_key";
            $newsNext = '';
        }
        else{
            $_body = "post_body";
            $_title = "post_title";
            $_image = "post_images";
            $_key = "post_key";
            $newsNext = new NewsClass($this->db, $this->root, $this->getArticle["id"]);
            $newsNext = $newsNext->returnNews($this->getArticle["post_category"]);

        }
        $arr_body = explode(".", $this->getArticle[$_body]);
        $article_body = "";
        $comment = $this->getArticle['comments'];
        $commentsUI = '';
        for ($i=0; $i < count($comment); $i++) { 
            $time = new CheckDate(strtotime($comment[$i]['created_at']));
            $commentsUI .= '
                <li class="comment-li card">
                    <div>
                    
                            <figure class="comment-avatar"><img src="'.$this->root.'assets/img/avatar.png" alt=""></figure>
                            <address>
                            By <a href="#">'.$comment[$i]['name'].'</a>
                            </address>
                            <time class="comment-time"><i class="fa fa-clock"></i> '.$time->checkDay().' </time>
                        <div class="comment-content">
                            '.$comment[$i]['comment'].'
                        </div>
                    </div>
                </li>';
        }
        for ($i = 0; $i < count($arr_body); $i++) {
            $article_body .= "<p>$arr_body[$i]</p>";
        }
        if($group !== "carousel"){
            $imageIndicator = '';
            $imageCarousel = '';
            foreach ($this->getArticle[$_image] as $key =>$image){
                $imageIndicator .= ($key == 0) ? '<li data-target="#carouselExampleIndicators" data-slide-to="0" class="active"></li>'
                        : '<li data-target="#carouselExampleIndicators" data-slide-to="1"></li>';


                $imageCarousel .= ($key == 0)?'<div class="carousel-item active">
                            <img class="d-block w-100" src="'.$this->root.$image.'" alt="First slide">

                        </div>':
                        '<div class="carousel-item">
                            <img class="d-block w-100" src="'.$this->root.$image.'" alt="First slide">

                        </div>';


            }
            $imageBody = '<div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-ride="carousel">
                                <ol class="carousel-indicators">
                                    '.$imageIndicator.'
                                </ol>
                                <div class="carousel-inner shadow-sm rounded">
                                    '.$imageCarousel.'
                                </div>
                            </div>';
        }
        else{
            $imageBody = '<img src="'.$this->root.$this->getArticle[$_image].'">';
        }
        
        $rawLayout = '
            <div class="container">
                <div class="jumbotron jumbotron-fluid mb-3 pl-0 pt-0 pb-0 bg-white position-relative">
                    <div class="h-100 tofront">
                        <div class="row justify-content-between">
                            <div class="col-md-6 pt-6 pb-6 pr-6 align-self-center">
                                <p class="text-uppercase font-weight-bold">
                                    <a class="text-danger" href="'.$this->root.'category.html">Stories</a>
                                </p>
                                <h1 class="display-4 secondfont mb-3 font-weight-bold">'.$this->getArticle[$_title].'</h1>
                                
                                <div class="d-flex align-items-center">
                                    <small class="ml-2">'.$this->getArticle["author"].' <span class="text-muted d-block">A few hours ago &middot; 5 min. read</span>
                                                        </small>
                                </div>
                            </div>
                            <div class="col-md-6 pr-0">
                                '.$imageBody.'
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Header -->

            <!--------------------------------------
        MAIN
        --------------------------------------->
            <div class="container pt-4 pb-4">
                <div class="row justify-content-center">
                    <div class="col-lg-2 pr-4 mb-4 col-md-12">
                        <div class="sticky-top text-center">
                            <div class="text-muted">
                                Share this
                            </div>
                            <div class="share d-inline-block">
                                <!-- AddToAny BEGIN -->
                                <div class="a2a_kit a2a_kit_size_32 a2a_default_style">
                                    <a class="a2a_dd" href="https://www.addtoany.com/share"></a>
                                    <a class="a2a_button_facebook"></a>
                                    <a class="a2a_button_twitter"></a>
                                </div>
                                <script async src="https://static.addtoany.com/menu/page.js"></script>
                                <!-- AddToAny END -->
                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 col-lg-8">
                        <article class="article-post">
                            '.$article_body.'
                        </article>
                        
                        
                        <form class="border p-3 bg-lightblue">
                            <div class="text-dark text-center p-3">
                                ADD YOUR COMMENT
                            </div>
                            <input type="hidden" id="commentKey" value="'.$this->getArticle[$_key].'">
                            <div class="row form-group">
                                <div class="col">
                                    <input type="text" class="form-control" id="nameController" placeholder="name">
                                    
                                </div>
                                
                            </div>
                            <div class="form-group">
                                <span class="text-danger">*</span>
                                <textarea class="form-control" placeholder="Comment here" id="commentController" rows="6"></textarea>
                            </div>
                            
                            <div class="border p-3">
                                <div class="text-center">
                                    <button type="button" id="handleSubmit" class="btn btn-primary shadow"><i class="fa fa-comment"></i> Comment</button>
                                </div>       
                            </div>
                            
                        </form>
                        
                    </div>
                </div>
            </div>
            <div class="container">
                <h5 class="font-weight-bold spanborder"><span><i class="fa fa-comments"></i> Comments</span></h5>
                <div id="commentBox">    
                    <ul class="comment-ui">
                        '.$commentsUI.'
                    </ul>
                </div>
            </div>
            <div class="container pt-4 pb-4">
            
        
            '.$newsNext.'

            <!-- End Main -->';
        return $rawLayout;
    }
}
