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
                                (music_name, music_image, music_details, artist, music_key, uploaded_by)
                        VALUES
                                (:music_name, :music_image, :music_details, :artist, :music_key, :uploaded_by)
                ";
                $statementImage = "
                        INSERT INTO images
                                (image_url, image_key)
                        VALUES
                                (:image_url, :image_key)
                ";
                try {
                        $post_key = md5($input['music_key'].rand(123, 2345621));
                        $statement = $this->db->prepare($statement);
                        $statementImage = $this->db->prepare($statementImage);
                        $statement->execute(array(
                                'music_name' => $input['music_name'],
                                'music_image' => $this->makeImg($input['music_image'][0]),
                                'music_details' => $input['music_details'],
                                'artist' => $input['artist'],
                                'music_key' => $input['post_category'],
                                'uploaded_by' => $input['author'],
                                'post_short_url' => str_replace(".", "-", str_replace(" ", "-", $input['post_title']."-".rand(12345, 23456219090)))
                                
                        ));
                        foreach ($input['post_images'] as $image) {
                                
                                $statementImage->execute(array(
                                        'image_url' => $ddd->makeImg($image),
                                        'image_key' => $post_key
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
