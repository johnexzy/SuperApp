<?php
$base64img = "data:image/png;base64,/9j/4AAQSkZJRgABAQAAAQABAA";
$start = \strpos($base64img, "/") +1;
$end = \strpos($base64img, ";");
$ext = substr($base64img, $start, $end-$start);
// echo $ext;
$str = "lecceljohn";
$hash = password_hash($str, PASSWORD_DEFAULT);
if (password_verify($str, '$2y$10$gND9LLz2Y5oVdzhjXFtnE.aVpVdK/iE0ROVOAQ/prs1KA/RSv2CYK')) {
    echo "TRUE\n";
}else echo "FALSE\n";
echo(password_hash($str, PASSWORD_DEFAULT));