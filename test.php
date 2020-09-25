<?php
// $base64img = "data:image/png;base64,/9j/4AAQSkZJRgABAQAAAQABAA";
// $start = \strpos($base64img, "/") +1;
// $end = \strpos($base64img, ";");
// $ext = substr($base64img, $start, $end-$start);
// // echo $ext;
// $str = "ebuka36779492#";
// $sst = "kpskgkbkghsdfc  gisfbgsirygs iixfsgu fxsfsdfhsdfhvd";
// // $hash = password_hash($str, PASSWORD_DEFAULT);
// // if (password_verify($str, '$2y$10$b7C9va5yo4Y0/lR3hNsTc.mm6JzmeiBlGZGDgJd5HOL53Aj8JrSie')) {
// //     echo "TRUE\n";
// // }else echo "FALSE\n";
// // echo(password_hash($str, PASSWORD_DEFAULT));
// echo(md5($sst));

// // require 'bootstrap.php';

// // use Src\TableGateways\MovieGateway;

// // $array = [
// //     "video_name"=> "johnwrt wrtwertwert wretwetwe",
// //     "video_details"=> "ihectosuicbrhosuc wetwerter twertwertwertwr twetertwetwetw",
// //     "category"=> "HollyWood",
// //     "popular" => 0
// // ];


// // $gateway = new MovieGateway($dbConnection);
// // $res = $gateway->update(1, $array);
// // echo json_encode($res);

// echo date(DATE_W3C, strtotime("2020-06-03 00:17:34"));
// echo metaphone("Johnexzy");
// echo "\n";
// echo metaphone("ojo");
$uri = explode("/", "john/sfdsfsdf/jojhn/sdfsd/sdf/sdff");
$name = implode("-", array_slice($uri, 2));
echo $name;