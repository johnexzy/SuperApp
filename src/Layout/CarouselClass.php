<?php
namespace Src\Layout;

use Src\TableGateways\CarouselGateway;

class CarouselClass {
    
    private $db = null;
    public function __construct($db) {
        $this->db = $db;
    }
   
    private function getJson(){
        /**
         * use this Api endpoint outside the origin, scheme, or port.
         * $res = file_get_contents("http://localhost:1234/api/carousel");
         * $res = (array) json_decode($response, true);
         */
        
        $carousel = new CarouselGateway($this->db);
        $res = $carousel->getAll();
        
        return $res;
    }
    public function returnCarousel() {
        $domain = $_SERVER['SERVER_NAME'].":".$_SERVER['SERVER_PORT'];
        $carousel_indicator = '';
        $carouselMain = '';
        $response = (array) $this->getJson();
        $colors = ['bg-orange', 'bg-light', 'bg-lightblue', 'bg-primary', 'bg-cyan', 'bg-indigo', 'bg-purple', 'bg-success', 'bg-info'];
        for ($i=0; $i < count($response); $i++) {
            $carousel_indicator .= ($i == 0) ? 
            '<li data-target="#carouselExampleIndicators" data-slide-to="'.$i.'" class="active"></li>'
            : '<li data-target="#carouselExampleIndicators" data-slide-to="'.$i.'" ></li>';
            $carouselMain .= ($i == 0)
            ? 
            '<div class="carousel-item active">
                <div class="jumbotron jumbotron-fluid mb-3 pt-0 pb-0 '.$colors[\array_rand($colors, 1)].' position-relative">
                    <div class="pl-4 pr-0 h-100 tofront">
                        <div class="row justify-content-between">
                            <div class="col-md-6 pt-6 pb-6 align-self-center">
                                <h1 class="secondfont mb-3 font-weight-bold">'.$response[$i]["carousel_title"].'</h1>
                                <p class="mb-3">
                                    '.$response[$i]["carousel_body"].'
                                </p>
                                <a href="http://'.$domain.'/view/carousel/'.$response[$i]["carousel_short_url"].'"
                                    target="_blank" class="btn btn-dark">DOWNLOAD NOW</a>
                            </div>
                            <a href="http://'.$domain.'/view/carousel/'.$response[$i]["carousel_short_url"].'"'
                    . ' target="_blank"  class="col-md-6 d-none d-md-block pr-0" style="cursor:pointer;background-size:cover;background-image:url('.$response[$i]["carousel_image"].');"> </a>
                        </div>
                    </div>
                </div>
            </div>'
            :
            '<div class="carousel-item">
                <div class="jumbotron jumbotron-fluid mb-3 pt-0 pb-0 '.$colors[\array_rand($colors, 1)].' position-relative">
                    <div class="pl-4 pr-0 h-100 tofront">
                        <div class="row justify-content-between">
                            <div class="col-md-6 pt-6 pb-6 align-self-center">
                                <h1 class="secondfont mb-3 font-weight-bold">'.$response[$i]["carousel_title"].'</h1>
                                <p class="mb-3">
                                    '.$response[$i]["carousel_body"].'
                                </p>
                                <a href="http://'.$domain.'/view/carousel/'.$response[$i]["carousel_short_url"].'" target="_blank" class="btn btn-dark">DOWNLOAD NOW</a>
                                </div>
                                <a href="http://'.$domain.'/view/carousel/'.$response[$i]["carousel_short_url"].'" target="_blank"  class="col-md-6 d-none d-md-block pr-0" style="cursor:pointer;background-size:cover;background-image:url('.$response[$i]["carousel_image"].');"> </a>
                            </div>
                    </div>
                </div>
            </div>';
        }

         $carouseData = '
        <div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-ride="carousel">
            <ol class="carousel-indicators">
                '.$carousel_indicator.'
            </ol>
            <div class="carousel-inner shadow-sm rounded">
                '.$carouselMain.'
            </div>
        </div>';
        return $carouseData;
    }
    
}
// $rrr = new CarouselClass();
// echo $rrr->returnCarosel();
