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
use Src\TableGateWays\SongGateway;
use Src\TableGateWays\ImageGateway;
class MusicGateWay extends SongGateway {
    private $db = null;
    private $imageInherited = null;
    public function __construct($db)
        {       
                parent::__construct($db);
                $this->db = $db;
                $this->imageInherited = new ImageGateway($db);
        }
        
        public function insert(Array $input)
        {
                $statement = "INSERT INTO music
                                (music_name, music_details, artist, music_key, short_url, uploaded_by)
                        VALUES
                                (:music_name, :music_details, :artist, :music_key, :short_url, :uploaded_by)";
                try {
                        $_key = md5($input['music_name'].rand(123, 2345621));
                        $query = $this->db->prepare($statement);
                        $query->execute(array(
                                'music_name' => $input['music_name'],
                                'music_details' => $input['music_details'],
                                'artist' => $input['artist'],
                                'music_key' => $_key,
                                'uploaded_by' => $input['author'],
                                'short_url' => str_replace(".", "-", str_replace(" ", "-", $input['music_name']."-".mt_rand()))

                        ));
                        $this->imageInherited->createImage($input['music_images'], $_key);
                        $this->createSong($input['song'], $input['music_name']."-".$input['artist'], $_key);
                        return $query->rowCount();
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        public function getAll($lim = null)
        {
                $statement = ($lim == null) ? "
                        SELECT
                                *
                        FROM
                                music
                        ORDER 
                            BY id DESC;
                " : "
                        SELECT
                                *
                        FROM
                                music
                        ORDER 
                            BY id DESC LIMIT $lim
                        ;
                ";
                try {   
                        $result = array();
                        $statement = $this->db->query($statement);
                        while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
                                // $comm = $this->findAllWithKey($res["post_key"]);
                                $songs = $this->getAllWithKey($res["music_key"]);
                                $images = $this->imageInherited->getPostImages($res["music_key"]);
                                $res += ["audio" => $songs[0]]; //pnly one file is needed. just incase
                                $res += ["images" => $images];
                                // $res += ["comments" => $comm];
                                $result[] = $res;
                        }
                        return $result;
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        public function getByUrl($short_url)
        {
                $statement = "
                        SELECT
                                *
                        FROM
                                music
                        WHERE short_url = ?;
                ";
                
                try {   
                        $result = null;
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($short_url));
                        $res = $statement->fetch(\PDO::FETCH_ASSOC);
                                // $comm = $this->findAllWithKey($res["post_key"]);
                        $songs = $this->getAllWithKey($res["music_key"]);
                        $images = $this->imageInherited->getPostImages($res["music_key"]);
                        $res += ["audio" => $songs[0]]; //pnly one file is needed. just incase
                        $res += ["images" => $images];
                        // $res += ["comments" => $comm];
                        $result = $res;
                        return $result;
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
