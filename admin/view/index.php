<?php

require '../../bootstrap.php';


$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uri = explode('/', $uri);