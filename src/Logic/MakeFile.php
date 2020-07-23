<?php
namespace Src\Logic;

use Error;
use Exception;

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
            throw new Error("Error Processing Image", 1);
            
            
            
    
    
    }

    /**
     * Saves Uploaded Music File and return file name
     * @return String
     */
    public static function makesong(Array $audio, $name) {
        
            // $fileInfo = new \finfo(FILEINFO_MIME_TYPE);
            $fileExt = strtolower(pathinfo($audio['name'], PATHINFO_EXTENSION));
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
        $fileExt = strtolower(pathinfo($video['name'], PATHINFO_EXTENSION));
        $ext = array('mp4', 'webm', 'mkv', 'mov');
        if (\in_array($fileExt, $ext)) {
            $path = str_replace(" ", "-", $name).mt_rand(0, 400).".$fileExt";
            if (!move_uploaded_file($video['tmp_name'], "../uploads/videos/$path")) {
                exit;
            }
            return "uploads/videos/$path";
        }
    }
    /**
     * Rearranges the file Array to ease working.
     * @return Array
     */
    public static function reArrayFiles($file_post) {

        $file_arr = array();
        if(is_countable($file_post['name'])){
            $file_count = count($file_post['name']);
            $file_keys = array_keys($file_post);
            
            for ($i=0; $i<$file_count; $i++) {
                foreach ($file_keys as $key) {
                    $file_arr[$i][$key] = $file_post[$key][$i];
                    // $file_arr[$key] = $file_post[$key];
                }
            }
            return $file_arr;
        }
        else{
            $file_keys = array_keys($file_post);
            foreach ($file_keys as $key) {
                $file_arr[$key] = $file_post[$key];
            }
            return $file_arr;
        }
        
        /**
         * @credit to : https://www.php.net/manual/en/features.file-upload.multiple.php#53240
         */
    }
}