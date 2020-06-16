<?php
namespace Src\Logic;
class MakeFile {

    /**
     * Decode base64img string, saves the img and return url
     * @return String
     */
    public static function makeimgfrombase64(String $base64img){
        $start = \strpos($base64img, "/") +1;
        $end = \strpos($base64img, ";");
        $ext = \substr($base64img, $start, $end-$start);
        $path = date('YmdHis',time()).mt_rand().".$ext";
        $data = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $base64img));
        if(file_put_contents("../uploads/images/$path", $data)){
            return "uploads/images/$path";
        }
    
    }
    /**
     * Saves uploaded img and return url|filename
     * @return String
     */
    public static function makeimg(Array $image){
            $fileExt = pathinfo($image['name'], PATHINFO_EXTENSION);
            $extensions = array('png', 'jpg', 'jpeg', 'webm', 'ico', 'gif');
            if (in_array($fileExt, $extensions)) {
                
                $path = date('YmdHis',time()).mt_rand().".$fileExt";
                if (!move_uploaded_file($image['tmp_name'], "../uploads/images/$path")) {
                    exit;
                }
                return "uploads/images/$path";
            }
    
    
    }

    /**
     * Saves Uploaded Music File and return file name
     * @return String
     */
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
    /**
     * Saves Uploaded Video File and return url | filename
     * @return String
     */
    public static function makevideo(Array $video, $name)
    {
        $fileExt = \pathinfo($video['name'], PATHINFO_EXTENSION);
        $ext = array('mp4', 'webm', 'mkv', 'mov');
        if (\in_array($fileExt, $ext)) {
            $path = str_replace(" ", "-", $name).mt_rand(0, 400).".$fileExt";
            if (!move_uploaded_file($video['tmp_name'], "../uploads/videos/$path")) {
                exit;
            }
            return "uploads/videos/$path";
        }
    }
}