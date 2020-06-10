<?php
$base64img = "data:image/png;base64,/9j/4AAQSkZJRgABAQAAAQABAA";
$start = \strpos($base64img, "/") +1;
$end = \strpos($base64img, ";");
$ext = substr($base64img, $start, $end-$start);
echo $ext;