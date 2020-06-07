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
class AlbumGateWay extends SongGateway {
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
                $statement = "INSERT INTO album
                                (album_name, album_details, artist, album_key, short_url, uploaded_by, year)
                        VALUES
                                (:album_name, :album_details, :artist, :album_key, :short_url, :uploaded_by, :year)";
                try {
                        $_key = md5($input['album_name'].mt_rand());
                        $query = $this->db->prepare($statement);
                        $query->execute(array(
                                'album_name' => $input['album_name'],
                                'album_details' => $input['album_details'],
                                'artist' => $input['artist'],
                                'album_key' => $_key,
                                'uploaded_by' => $input['author'],
                                'year' => $input['year'],
                                'short_url' => str_replace(".", "-", str_replace(" ", "-", $input['album_name']."-".mt_rand()))

                        ));
                        $this->imageInherited->createImage($input['album_images'], $_key);
                        $this->createAlbumSong($input['songs'], $input['album_name']."-".$input['artist'], $_key);
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
                                album
                        ORDER 
                            BY id DESC;
                " : "
                        SELECT
                                *
                        FROM
                                album
                        ORDER 
                            BY id DESC LIMIT $lim
                        ;
                ";
                try {   
                        $result = array();
                        $statement = $this->db->query($statement);
                        while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
                                // $comm = $this->findAllWithKey($res["post_key"]);
                                $songs = $this->getAllWithKey($res["album_key"]);
                                $images = $this->imageInherited->getPostImages($res["album_key"]);
                                $res += ["audio" => $songs]; 
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
                                album
                        WHERE short_url = ?;
                ";
                
                try {   
                        $result = null;
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($short_url));
                        $res = $statement->fetch(\PDO::FETCH_ASSOC);
                                // $comm = $this->findAllWithKey($res["post_key"]);
                        $songs = $this->getAllWithKey($res["album_key"]);
                        $images = $this->imageInherited->getPostImages($res["album_key"]);
                        $res += ["audio" => $songs]; 
                        $res += ["images" => $images];
                        // $res += ["comments" => $comm];
                        $result = $res;
                        return $result;
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        
        
        public function find($id)
        {
                
                $statement = "
                        SELECT
                                *
                        FROM
                                album
                        WHERE id = ?;
                ";
                try {   
                        $result = null;
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($id));
                        $res = $statement->fetch(\PDO::FETCH_ASSOC);
                                // $comm = $this->findAllWithKey($res["post_key"]);
                        $songs = $this->getAllWithKey($res["album_key"]);
                        $images = $this->imageInherited->getPostImages($res["album_key"]);
                        $res += ["audio" => $songs]; 
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
                $statement = "DELETE FROM `album` WHERE `album`.`id` = :id";

                try {
                        $statement=$this->db->prepare($statement);
                        $statement->execute(array('id' => $id));
                        return $statement->rowCount();
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
}
