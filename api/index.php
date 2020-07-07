<?php

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8;");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: OPTIONS, GET, POST, PUT, DELETE");
header("Access-Control-Allow-Headers: Origin, Access-Control-Allow-Origin, Content-Type, Access-Control-Allow-Methods, X-Requested-With");

require '../bootstrap.php';

use Src\Controller\MusicController;
use Src\Controller\CommentsController;
use Src\Logic\MakeFile;
use Src\Controller\MovieController;
// use Src\Controller\NewsController;
// use Src\Controller\CarouselController;
use Src\Controller\AlbumController;
use Src\Controller\ImageController;
use Src\Controller\UserController;

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);
$requestMethod = $_SERVER['REQUEST_METHOD'];
//all of the end points starts with person
//everything else results in a 404

// echo json_encode($uri);
if ($uri[2] == 'v1' && isset($uri[3])) {
    
    if ($uri[3] == 'user' && count($uri) == 6) {
        $username = strip_tags($uri[4]);
        $password = strip_tags($uri[5]);

        $controller = new UserController($username, $password, $dbConnection);
        $controller->proccessRequest();
    }
    elseif($uri[3] == 'music'){
        
        $input = null;
        $limit = null;
        $short_url = null;
        $id = null;
        $popular = null;
        $pn = null;
        switch ($requestMethod) {
            case 'POST':
                header("Content-Type: multipart/form-data;");
                $input = (array) $_POST;
                $input += ["images" => MakeFile::reArrayFiles($_FILES['music_images'])];
                $input += ["song" => MakeFile::reArrayFiles($_FILES['music_file'])];

                break;
            case 'GET':
                if (isset($uri[4])) {
                    if ($uri[4] == "limit" && isset($uri[5])) {
                        $limit = (int) $uri[5];
                    }
                    elseif($uri[4] == "popular" && isset($uri[5])){
                        $popular = (int) $uri[5];
                    }
                    elseif ($uri[4] == "url" && isset($uri[5])) {
                        $short_url = (strlen($uri[5]) > 6) ? strip_tags($uri[5]) : null;
                        
                    }
                    elseif($uri[4] == "pages" && isset($uri[5])){
                        $pn = (int) $uri[5];
                    }
                    else{

                    }
                }
                break;
            case 'PUT':
                $input = json_decode(file_get_contents("php://input"), TRUE);

                if (isset($uri[4])) {
                    $id = (int) $uri[4];
                }
                break;
            case 'DELETE' :
                if (isset($uri[4])) {
                    if ($uri[4] == "delete" && isset($uri[5])) {
                        $id = (int) $uri[5];
                    }
                }
                break;
            default:
                # code...
                break;
        }
        $controller = new MusicController($dbConnection, $requestMethod, $input, $id, $limit, $popular, $pn, $short_url);
        $controller->processRequest();
    }

    elseif($uri[3] == 'album'){
        
        $input = null;
        $limit = null;
        $short_url = null;
        $id = null;
        $popular = null;
        $pn = null;
        switch ($requestMethod) {
            case 'POST':
                header("Content-Type: multipart/form-data;");
                $input = (array) $_POST;
                /**
                 * An album consist of two or more audio files by from one artist
                 * We're going to process these audio files and reArray each individual files.
                 */
                $input += ["songs" => MakeFile::reArrayFiles($_FILES['album_files'])];
                $input += ["images" => MakeFile::reArrayFiles($_FILES['album_images'])];
                break;
            case 'GET':
                if (isset($uri[4])) {
                    if ($uri[4] == "limit" && isset($uri[5])) {
                        $limit = (int) $uri[5];
                    }
                    elseif($uri[4] == "popular" && isset($uri[5])){
                        $popular = (int) $uri[5];
                    }
                    elseif ($uri[4] == "url" && isset($uri[5])) {
                        $short_url = (strlen($uri[5]) > 6) ? strip_tags($uri[5]) : null;   
                    }
                    elseif($uri[4] == "pages" && isset($uri[5])){
                        $pn = (int) $uri[5];
                    }
                    else{

                    }
                }
                break;
            case 'PUT':
                $input = json_decode(file_get_contents("php://input"), TRUE);
                
                if (isset($uri[4])) {
                    $id = (int) $uri[4];
                }
                break;
            case 'DELETE' :
                if (isset($uri[4])) {
                    if ($uri[4] == "delete" && isset($uri[5])) {
                        $id = (int) $uri[5];
                    }
                }
                break;
            default:
                # code...
                break;
        }
        $controller = new AlbumController($dbConnection, $requestMethod, $input, $id, $limit, $popular, $pn, $short_url);
        $controller->processRequest();
    }

    elseif($uri[3] == 'videos'){
        
        $input = null;
        $limit = null;
        $short_url = null;
        $id = null;
        $popular = null;
        $pn = null;
        switch ($requestMethod) {
            case 'POST':
                header("Content-Type: multipart/form-data;");
                $input = (array) $_POST;
                $input += ["images" => MakeFile::reArrayFiles($_FILES['video_images'])];
                $input += ["video" => MakeFile::reArrayFiles($_FILES['video_file'])];

                break;
            case 'GET':
                if (isset($uri[4])) {
                    if ($uri[4] == "limit" && isset($uri[5])) {
                        $limit = (int) $uri[5];
                    }
                    elseif($uri[4] == "popular" && isset($uri[5])){
                        $popular = (int) $uri[5];
                    }
                    elseif ($uri[4] == "url" && isset($uri[5])) {
                        $short_url = (strlen($uri[5]) > 6) ? strip_tags($uri[5]) : null;
                        
                    }
                    elseif($uri[4] == "pages" && isset($uri[5])){
                        $pn = (int) $uri[5];
                    }
                    else{

                    }
                }
                break;
            case 'PUT':
                $input = json_decode(file_get_contents("php://input"), TRUE);

                if (isset($uri[4])) {
                    $id = (int) $uri[4];
                }
                break;
            case 'DELETE' :
                if (isset($uri[4])) {
                    if ($uri[4] == "delete" && isset($uri[5])) {
                        $id = (int) $uri[5];
                    }
                }
                break;
            default:
                # code...
                break;
        }
        $controller = new MovieController($dbConnection, $requestMethod, $input, $id, $limit, $popular, $pn, $short_url);
        $controller->processRequest();
    }

    elseif ($uri[3] == 'comment') {
        $comId = null;
        $key = null;
        if (isset($uri[4]) && $uri[4] == "key") {
            if ($requestMethod == "GET") {
                $key = (String) $uri[5];
                
            }
            if ($requestMethod == "PUT") {
                $comId = (int) $uri[4];
            }
        }
        else{
            //inother to avoid cors, post comments will use get methods instead
            $input = array(
                "name" => !isset($_GET["name"]) ? "Anonymous" : strip_tags($_GET["name"]),
                "comment_key" => strip_tags($_GET["comment_key"]),
                "comment" => strip_tags($_GET["comment"])
            );
            $input = json_encode($input);
        }

        

        // pass the request method and user ID to the CommentController and process the HTTP request:
        $controller = new CommentsController($dbConnection, $requestMethod, $key, $comId, $input);
        $controller->processRequest();
    }
    elseif ($uri[3] == 'images') {
        $input = null;
        $key = null;
        switch ($requestMethod) {
            case 'POST':
                if (isset($uri[4])) {
                    header("Content-Type: multipart/form-data");
                    $input = (array) $_FILES["images"];
                
                    $key = (String) $uri[4];
                }
                else{
                    return false;
                }
                break;
            case 'GET':
                if (isset($uri[4])) {
                    $key = (String) $uri[4];
                }
                break;
            case 'DELETE':
                if (isset($uri[4]) && $uri[4] == "delete") {
                    $key = isset($uri[5]) ? (String) $uri[5] : null;
                }
                break;
            default:
                # code...
                break;
        }
        $controller = new ImageController($dbConnection, $key, $input, $requestMethod);
        $controller->processRequest();
    }

}
else{
    header("HTTP/1.1 404 Not Found");
    exit();
}