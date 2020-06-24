<?php
$base64img = "data:image/png;base64,/9j/4AAQSkZJRgABAQAAAQABAA";
$start = \strpos($base64img, "/") +1;
$end = \strpos($base64img, ";");
$ext = substr($base64img, $start, $end-$start);
// echo $ext;
$str = "ebuka36779492#";
$hash = password_hash($str, PASSWORD_DEFAULT);
if (password_verify($str, '$2y$10$b7C9va5yo4Y0/lR3hNsTc.mm6JzmeiBlGZGDgJd5HOL53Aj8JrSie')) {
    echo "TRUE\n";
}else echo "FALSE\n";
echo(password_hash($str, PASSWORD_DEFAULT));