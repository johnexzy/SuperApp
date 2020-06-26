<?php

use Src\Controller\ViewController\ViewAlbum\ViewAlbum;


require '../../bootstrap.php';


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = explode('/', $uri);

if(isset($uri[3]) && isset($uri[4])){
    $group = strip_tags($uri[3]);
    $short_url = strip_tags($uri[4]);
    switch ($group) {
        case 'music':
            # music request handler

            echo "music";
            break;
        
        case 'movie':
            # movie request handler
            echo "movie";
            break;

        case 'album':
            # album request handler
            $album = new ViewAlbum($short_url, $dbConnection);
            echo $album->bodyParser();
            break;

        case 'comments':
            # comment request handler
            echo "comments";
            break;
        default:
            header("HTTP/1.1 404 Not Found");
            exit();
            break;
    }
}
else{
    header("HTTP/1.1 404 Not Found");
}