<?php

use Src\Controller\ViewController\ViewAlbum\ViewAlbum;
use Src\Controller\ViewController\ViewMovie\ViewMovie;
use Src\Controller\ViewController\ViewMusic\ViewMusic;
use Src\Controller\ViewController\ViewSeries\AddEpisode;
use Src\Controller\ViewController\ViewSeries\ViewSeason;
use Src\Controller\ViewController\ViewSeries\ViewEpisode;
use Src\Controller\ViewController\ViewSeries\ViewSeries;

require '../session.php';
require '../../bootstrap.php';


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = explode('/', $uri);
foreach ($uri as $key => $link) {
    $uri[$key] = str_replace("%20", " ", $link);
}


if(count($uri) == 5){
    $group = strip_tags($uri[3]);
    $short_url = strip_tags($uri[4]);
    switch ($group) {
        case 'music':
            # music request handler

            $music = new ViewMusic($short_url, $dbConnection);
            echo $music->bodyParser();
            break;
        
        case 'movie':
            # movie request handler
            $movie = new ViewMovie($short_url, $dbConnection);
            echo $movie->bodyParser();
            break;
        case 'series':
            # series request handler
            $movie = new ViewSeries($short_url, $dbConnection);
            echo $movie->bodyParser();
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
elseif(count($uri) == 6){
    $group = $uri[3];
    $series_name = $uri[4];
    $short_url = $uri[5];

    switch ($group) {
        case 'season':
            
            $season = new ViewSeason($short_url, $series_name, $dbConnection);
            echo $season->bodyParser();
            break;
        case 'episode':
            $episode = new ViewEpisode($series_name, $short_url, $dbConnection);
            echo $episode->bodyParser();
            break;
        default:
            header("HTTP/1.1 404 Not Found");
            exit();
            break;
    }
}
elseif(count($uri) == 7){
    $group = $uri[3];
    $series_name = $uri[4];
    $short_url = $uri[5];

    switch ($group) {
        case 'season':
            
            $season = new ViewSeason($short_url, $series_name, $dbConnection);
            echo $season->bodyParser();
            break;
        case 'episode':
            $episode = new AddEpisode($series_name, $short_url);
            echo $episode->bodyParser();
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