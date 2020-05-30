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
use Src\Logic\MakeImage;
class MusicGateWay extends MakeImage{
    private $db = null;
    public function __construct($db)
        {       
                $this->db = $db;
                
        }
        
        public function insert(Array $input)
        {
                $statement = "
                        INSERT INTO News
                                (music_name, music_details, artist, music_key, uploaded_by)
                        VALUES
                                (:music_name, :music_details, :artist, :music_key, :uploaded_by)
                ";
                $statementImage = "
                        INSERT INTO images
                                (song_url, song_key)
                        VALUES
                                (:song_url, :song_key)
                ";
                try {
                        $_key = md5($input['music_key'].rand(123, 2345621));
                        $statement = $this->db->prepare($statement);
                        $statementImage = $this->db->prepare($statementImage);
                        $statement->execute(array(
                                'music_name' => $input['music_name'],
                                'music_details' => $input['music_details'],
                                'artist' => $input['artist'],
                                'music_key' => $_key,
                                'uploaded_by' => $input['author'],
                                
                        ));
                        foreach ($input['music_image'] as $image) {
                                $statementImage->execute(array(
                                        'image_url' => $this->makeImg($image),
                                        'image_key' => $_key
                                ));
                        }
                        return $statement->rowCount();
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
