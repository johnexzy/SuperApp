<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace Src\TableGateways;

/**
 * Description of MusicGateWay
 *
 * @author ObaJohn
 */
use Src\TableGateways\CommentsGateway;
use Src\TableGateways\SongGateway;
use Src\TableGateways\ImageGateway;
class AlbumGateway extends SongGateway {
    private $db = null;
    private $imageInherited = null;
    private $comment = null;
    const LIMIT_PER_PAGE = 3;
    public function __construct($db)
        {       
                parent::__construct($db);
                $this->db = $db;
                $this->imageInherited = new ImageGateway($db);
                $this->comment = new CommentsGateway($db);
        }
        
        public function insert(Array $input)
        {
                $statement = "INSERT INTO album
                                (album_name, album_details, artist, album_key, short_url, uploaded_by, year, popular)
                        VALUES
                                (:album_name, :album_details, :artist, :album_key, :short_url, :uploaded_by, :year, :popular)";
                try {
                        $_key = md5($input['album_name'].mt_rand());
                        if($this->imageInherited->createImage($input['images'], $_key) == null) throw new \PDOException("Error Processing Request", 1);
                        
                        $query = $this->db->prepare($statement);
                        $query->execute(array(
                                'album_name' => $input['album_name'],
                                'album_details' => $input['album_details'],
                                'artist' => $input['artist'],
                                'album_key' => $_key,
                                'uploaded_by' => $input['author'],
                                'year' => $input['year'],
                                'popular' => $input['popular'],
                                'short_url' => str_replace(".", "-", str_replace(" ", "-", $input['album_name']."-".mt_rand()))

                        ));
                        
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
                                $comm = $this->comment->findAllWithKey($res["album_key"]);
                                $songs = $this->getAllWithKey($res["album_key"]);
                                $images = $this->imageInherited->getPostImages($res["album_key"]);
                                $res += ["audio" => $songs]; 
                                $res += ["images" => $images];
                                $res += ["comments" => $comm];
                                $result[] = $res;
                        }
                        return $result;
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        public function getPages($pageNo)
        {
                $limit = self::LIMIT_PER_PAGE;
                $startFrom = ($pageNo - 1) * $limit;
                $totalRecord = self::getTotalRecord($this->db);
                $totalPages = \ceil($totalRecord / $limit);
                $statement = "
                        SELECT
                                *
                        FROM
                                album
                        ORDER BY album_name 
                            DESC LIMIT $startFrom, $limit;";
                try {   
                        $data = array();
                        $statement = $this->db->query($statement);
                        while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
                                $comm = $this->comment->findAllWithKey($res["album_key"]);
                                $songs = $this->getAllWithKey($res["album_key"]);
                                $images = $this->imageInherited->getPostImages($res["album_key"]);
                                $res += ["audio" => $songs]; //pnly one file is needed. just incase
                                $res += ["images" => $images];
                                $res += ["comments" => $comm];
                                $data[] = $res;
                        }
                        $result = ["data" => $data];
                        $result += ["links" => [
                                "first" => "pages/1",
                                "last" => "pages/$totalPages",
                                "prev" =>(($pageNo - 1) > 0) ? "pages/".($pageNo - 1) : null,
                                "next" => ($pageNo == $totalPages) ? null : "pages/".($pageNo + 1)
                        ]];
                        $result += ["meta" => [
                                "current_page" => (int) $pageNo,
                                "total_pages" => $totalPages
                        ]];
                        return $result;
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        public function getPopular($popularInt)
        {
                $statement = "
                        SELECT
                                *
                        FROM
                                album
                        WHERE popular = 1
                        ORDER 
                            BY id DESC LIMIT $popularInt;
                ";
                
                try {   
                        $result = array();
                        $statement = $this->db->query($statement);
                        while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
                                $comm = $this->comment->findAllWithKey($res["album_key"]);
                                $songs = $this->getAllWithKey($res["album_key"]);
                                $images = $this->imageInherited->getPostImages($res["album_key"]);
                                $res += ["audio" => $songs]; 
                                $res += ["images" => $images];
                                $res += ["comments" => $comm];
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
                        $comm = $this->comment->findAllWithKey($res["album_key"]);
                        $songs = $this->getAllWithKey($res["album_key"]);
                        $images = $this->imageInherited->getPostImages($res["album_key"]);
                        $res += ["audio" => $songs]; 
                        $res += ["images" => $images];
                        $res += ["comments" => $comm];
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
                        $comm = $this->comment->findAllWithKey($res["album_key"]);
                        $songs = $this->getAllWithKey($res["album_key"]);
                        $images = $this->imageInherited->getPostImages($res["album_key"]);
                        $res += ["audio" => $songs]; 
                        $res += ["images" => $images];
                        $res += ["comments" => $comm];
                        $result = $res;
                        return $result;
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }

        public function findByUrl($short_url)
        {
                $statement = "
                        SELECT
                                *
                        FROM
                                album
                        WHERE short_url = ?;
                ";
                
                try {   
                        
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($short_url));
                        $res = $statement->fetch(\PDO::FETCH_ASSOC);
                        
                        return $res;
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
        private static function getTotalRecord($db)
        {
                $statement = "SELECT album.id FROM album";
                try {
                        $statement = $db->query($statement);
                        $result = $statement->fetchAll(\PDO::FETCH_COLUMN);
                        return $result = count($result);
                } catch (\PDOException $th) {
                        exit($th->getMessage());
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
