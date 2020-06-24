<?php

require '../../bootstrap.php';


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = explode('/', $uri);

if(isset($uri[3])){
    $group = strip_tags($uri[3]);
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
            echo "album";
            break;

        case 'comments':
            # comment request handler
            echo "comments";
            break;
        default:
            # album request handler
            break;
    }
}