<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8;");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Origin, Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, X-Requested-With");

require '../bootstrap.php';
use Src\Controller\MusicController;
use Src\Controller\CommentsController;
use Src\Controller\PersonController;
use Src\Controller\NewsController;
use Src\Controller\CarouselController;



$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);
$requestMethod = $_SERVER['REQUEST_METHOD'];
//all of the end points starts with person
//everything else results in a 404


if($uri[2] == 'music'){
    
    $input = null;
    if ($requestMethod == 'POST') {
        header("Content-Type: multipart/form-data;");
        $input = (array) $_POST;
        $input += ["song" => MusicController::reArrayFiles($_FILES['music_file'])];
    }
    $controller = new MusicController($dbConnection, $requestMethod, $input);
    $controller->processRequest();
}



elseif ($uri[2] == 'news') {
    $id = null;
    $cat = null;
    $short_url = null;
    if (isset($uri[3])) {
        if ($requestMethod == "GET") {
            $cat = (intval($uri[3]) == 0) ? (String) $uri[3] : null;
            
            $id = ($cat == null) ? (int) $uri[3] : null;
            //check if uri[3] is actually $cat or a short_url
            //short_url ends with integer > 12345
            $check = explode('-', $cat);
            if ($cat) {
                if((int) $check[count($check) - 1] >= 12345){
                    $short_url = $cat;
                    $cat = null;
                }
            }
            
            //echo $short_url;
        }
        elseif ($requestMethod == "PUT") {
            $id = (int) $uri[3];
        }
        elseif ($requestMethod == "DELETE") {
            $id = (int) $uri[3];
        }
    }

    

    // pass the request method and user ID to the NewsController and process the HTTP request:
    $controller = new NewsController($dbConnection, $requestMethod, $cat, $id, $short_url);
    $controller->processRequest();
}
elseif ($uri[2] == 'person') {
    
    //the user ID is, of course optional and must be integer
    $userId = null;
    if (isset($uri[3])) {
        $userId = (int) $uri[3];
    }
    

    // pass the request method and user ID to the PersonController and process the HTTP request:
    $controller = new PersonController($dbConnection, $requestMethod, $userId);
    $controller->processRequest();
    //pass the request Method and user ID to PersonController and process the HTTP request

}
elseif ($uri[2] == 'comment') {
    $comId = null;
    $key = null;
    if (isset($uri[3])) {
        if ($requestMethod == "GET") {
            $key = (String) $uri[3];
            
        }
        if ($requestMethod == "PUT") {
            $comId = (int) $uri[3];
        }
    }

    

    // pass the request method and user ID to the CommentController and process the HTTP request:
    $controller = new CommentsController($dbConnection, $requestMethod, $key, $comId);
    $controller->processRequest();
}

elseif($uri[2] == 'carousel'){
    $id = null;
    $short_url = null;
    if (isset($uri[3])) {
        if ($requestMethod == "GET") {
            $short_url = (intval($uri[3]) == 0) ? (String) $uri[3] : null;
            $id = ($short_url == null) ? (int) $uri[3] : null;
        }
        elseif ($requestMethod == "PUT") {
            $id = (int) $uri[3];
        }
        elseif ($requestMethod == "DELETE") {
            $id = (int) $uri[3];
        }
    }
    
    // pass the request method and user ID to the CarouselController and process the HTTP request:
    $controller = new CarouselController($dbConnection, $requestMethod, $short_url, $id);
    $controller->processRequest();
}

else{
    header("HTTP/1.1 404 Not Found");
    exit();
}