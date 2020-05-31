<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Src\TableGateWays;

/**
 * Description of MusicGateWay
 *
 * @author ObaJohn
 */
use Src\Logic\MakeFile;
class MusicGateWay{
    private $db = null;
    public function __construct($db)
        {       
                $this->db = $db;
                
        }
        
        public function insert(Array $input)
        {
                $statement = "INSERT INTO music
                                (music_name, music_details, artist, music_key, uploaded_by)
                        VALUES
                                (:music_name, :music_details, :artist, :music_key, :uploaded_by)";
                try {
                        $_key = md5($input['music_name'].rand(123, 2345621));
                        $query = $this->db->prepare($statement);
                        $query->execute(array(
                                'music_name' => $input['music_name'],
                                'music_details' => $input['music_details'],
                                'artist' => $input['artist'],
                                'music_key' => $_key,
                                'uploaded_by' => $input['author'],
                        ));
                        $this->createImage($input['music_images'], $_key);
                        $this->createSong($input['song'], $input['music_name'], $_key);
                        return $query->rowCount();
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        public function createImage(Array $images, string $key) {
                $statement = "
                        INSERT INTO images
                                (image_url, image_key)
                        VALUES
                                (:image_url, :image_key)     
                ";
                try {
                        
                        $query = $this->db->prepare($statement);
                        foreach ($images as $image) {
                                $query->execute(array(
                                        'image_url' => MakeFile::makeImg($image),
                                        'image_key' => $key
                                ));
                        }
                        
                        return $query->rowCount();
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
            
        }
        public function createSong(Array $song, $name, string $key) {
                $statement = "
                        INSERT INTO songs
                                (song_url, song_bytes, song_key )
                        VALUES
                                (:song_url, :song_bytes, :song_key )
                ";
                try {
                        
                        $query = $this->db->prepare($statement);
                        $query->execute(array(
                                'song_url' => MakeFile::makesong($song, $name),
                                'song_bytes' => $song['size'],
                                'song_key' => $key
                        ));
                        
                        return $query->rowCount();
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
            
        }
        public function update($uid, Array $input)
        {       $statement = "
                        UPDATE `News` 
                        SET 
                                `post_title` = :post_title, 
                                `post_body` = :post_body,
                                `updated_at` = CURRENT_TIMESTAMP
                        WHERE `News`.`id` = :id;
                ";
                
                try {
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array(
                                'id' => (int) $uid,
                                'post_title' => $input['post_title'],
                                'post_body' => $input['post_body']
                        ));
                        return $statement->rowCount();
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        public function delete($id)
        {
                $statement = "DELETE FROM `news` WHERE `news`.`id` = :id";

                try {
                        $statement=$this->db->prepare($statement);
                        $statement->execute(array('id' => $id));
                        return $statement->rowCount();
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
}
