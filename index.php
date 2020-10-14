<?php
require "vendor/autoload.php";
use diversen\sendfile;
if (isset($_REQUEST['f'])) {
    # code...
    $file_uri = $_REQUEST['f'];
    $uri = explode("/", $file_uri);
    if ($uri[0] == "uploads" && count($uri) == 3) {
        if (file_exists($file_uri)) {
                
            $s = new sendfile();
                    
            // if you don't set type - we will try to guess it
                    
            // if you don't set disposition (file name user agent will see)
            // we will make a file name from file
            $s->contentDisposition($uri[2]);
                    
            // chunks of 40960 bytes per 0.1 secs
            // if you don't set this then the values below are the defaults
            // approx 409600 bytes per sec
            $s->throttle(0.1, 40960);
    
            // file
            $file = $file_uri;
    
            // send the file
            try {
                $s->send($file);
            } catch (\Exception $e) {
                echo $e->getMessage();
            }
        }
        else {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
    }
    else {
        header("HTTP/1.1 404 Not Found");
        exit();
    }
}else {
    header("HTTP/1.1 404 Not Found");
    exit();
}

//required URI format: https://app.leccel.net[0]/uploads[1]/videos[2]/videoName[3]

