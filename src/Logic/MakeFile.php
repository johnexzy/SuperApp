<?php
namespace Src\Logic;
class MakeFile {

    public static function makeimg(String $base64img){
        $path = date('YmdHis',time()).mt_rand().'.jpg';
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64img));
        if(file_put_contents("../uploads/images/$path", $data)){
            return "uploads/images/$path";
        }
    
    
    }
    public static function makesong(Array $audio, $name) {
        
            // $fileInfo = new \finfo(FILEINFO_MIME_TYPE);
            $fileExt = pathinfo($audio['name'], PATHINFO_EXTENSION);
            $extensions = array('mp3', 'wav', 'ogg', 'opus', 'flac', 'm4a', 'm4b');
            if (in_array($fileExt, $extensions)) {
                $path = str_replace(" ", "-", $name).mt_rand(0, 400).".$fileExt";
                if (!move_uploaded_file($audio['tmp_name'], "../uploads/audios/$path")) {
                    exit;
                }
                return "uploads/audios/$path";
            }
    }
}