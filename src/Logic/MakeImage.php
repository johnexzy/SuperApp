<?php
namespace Src\Logic;
class MakeImage {

    public static function makeimg(String $base64img){
        $path = time().rand(5492, 123456789).'.jpg';
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64img));
        if(file_put_contents('../uploads/'.$path, $data)){
            return "uploads"."/".$path;
        }
    
    
    }
    
}
