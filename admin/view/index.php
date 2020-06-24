<?php

require '../../bootstrap.php';


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = explode('/', $uri);

if(isset($uri[3])){
    $group = strip_tags($uri[3]);
    switch ($$group) {
        case 'music':
            # music request handler
            break;
        
        default:
            # album request handler
            break;
    }
}