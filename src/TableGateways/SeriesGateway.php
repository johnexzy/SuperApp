<?php
namespace Src\TableGateways;

use RuntimeException;
use Src\TableGateways\ImageGateway;
use Src\TableGateways\CommentsGateway;

class SeriesGateway extends SeasonGateway
{
        private $db = null, $comment, $imageInherited;
        const LIMIT_PER_PAGE = 5;
        public function __construct($db)
        {
                parent::__construct($db);
                $this->db = $db;
                $this->imageInherited = new ImageGateway($db);
                $this->comment = new CommentsGateway($db);
        }
        
        
        public function insert(Array $input)
        {
                $statement = "
                        INSERT INTO series
                                (series_name, series_key, short_url)
                        VALUES
                                (:series_name, :series_key, :short_url)
                ";
                try {
                        $_key = md5($input['series_name'].mt_rand());
                        if($this->imageInherited->createImage($input['images'], $_key) == null){
                                // throw new \PDOException("Error Processing Request", 1);
                                throw new RuntimeException("Error Processing Request", 1);
                        } 
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array(
                                'series_name' => $input['series_name'],
                                'series_key' => $_key,
                                'short_url' => str_replace(".", "-", str_replace(" ", "-", $input['series_name']."-".mt_rand()))
                        ));
                        return $statement->rowCount();
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        // public function update($uid, Array $input)
        // {
        //         $statement = "
        //                 UPDATE person
        //                 SET
        //                     firstname = :firstname,
        //                     lastname  = :lastname,
        //                     firstparent_id = :firstparent_id,
        //                     secondparent_id = :secondparent_id
        //                 WHERE id = :id
        //         ";
        //         try {
        //                 $statement = $this->db->prepare($statement);
        //                 $statement->execute(array(
        //                         'id' => (int) $uid,
        //                         'firstname' => $input['firstname'],
        //                         'lastname' => $input['lastname'],
        //                         'firstparent_id' => $input['firstparent_id'] ?? null,
        //                         'secondparent_id' => $input['secondparent_id'] ?? null,
        //                 ));
        //                 return $statement->rowCount();
        //         } catch (\PDOException $e) {
        //                 exit($e->getMessage());
        //         }
        // }

        /**
         * get all records
         */
        public function getAll($lim = null)
        {
                $statement = ($lim == null) ? "
                        SELECT
                                *
                        FROM
                                series
                        ORDER 
                            BY id DESC;
                " : "
                        SELECT
                                *
                        FROM
                                series
                        ORDER 
                            BY id DESC LIMIT $lim
                        ;
                ";
                try {   
                        $result = array();
                        $statement = $this->db->query($statement);
                        while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
                                $comm = $this->comment->findAllWithKey($res["series_key"]);
                                $images = $this->imageInherited->getPostImages($res["series_key"]);
                                $series = $this->findAllWithKey($res["series_key"]);
                                $res += ["series" => $series];
                                $res += ["images" => $images];
                                $res += ["comments" => $comm];
                                $result[] = $res;
                        }
                        return $result;
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        /**
         * get Series by pages
         * 
         */
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
                                series
                        ORDER BY series_name 
                            DESC LIMIT $startFrom, $limit;";
                try {   
                        $data = array();
                        $statement = $this->db->query($statement);
                        while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
                                $comm = $this->comment->findAllWithKey($res["series_key"]);
                                
                                $images = $this->imageInherited->getPostImages($res["series_key"]);
                                $series = $this->findAllWithKey($res["series_key"]);
                                $res += ["series" => $series];
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

        /**
         * get popular Seriess
         */
        // public function getPopular($popularInt)
        // {
        //         $statement = "
        //                 SELECT
        //                         *
        //                 FROM
        //                         series
        //                 WHERE popular > 0
        //                 ORDER 
        //                     BY id DESC LIMIT $popularInt;
        //         ";
        //         try {   
        //                 $result = array();
        //                 $statement = $this->db->query($statement);
        //                 while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
        //                         $comm = $this->comment->findAllWithKey($res["series_key"]);
                                
        //                         $images = $this->imageInherited->getPostImages($res["series_key"]);
                                
        //                         $res += ["images" => $images];
        //                         $res += ["comments" => $comm];
        //                         $result[] = $res;
        //                 }
        //                 return $result;
        //         } catch (\PDOException $e) {
        //                 exit($e->getMessage());
        //         }
        // }
        /**
         * get Series by short url
         */
        public function getByUrl($short_url)
        {
                $statement = "
                        SELECT
                                *
                        FROM
                                series
                        WHERE short_url = ?;
                ";
                
                try {   
                        $result = null;
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($short_url));
                        $res = $statement->fetch(\PDO::FETCH_ASSOC);
                        $comm = $this->comment->findAllWithKey($res["series_key"]);
                        
                        $images = $this->imageInherited->getPostImages($res["series_key"]);
                        $series = $this->findAllWithKey($res["series_key"]);
                        $res += ["series" => $series];
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
                                series
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
        public function find($id)
        {
                
                $statement = "
                        SELECT
                                *
                        FROM
                                series
                        WHERE id = ?;
                ";
                try {   
                        $result = null;
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($id));
                        $res = $statement->fetch(\PDO::FETCH_ASSOC);
                        $comm = $this->comment->findAllWithKey($res["series_key"]);
                        
                        $images = $this->imageInherited->getPostImages($res["series_key"]);
                        
                        $res += ["images" => $images];
                        $res += ["comments" => $comm];
                        $result = $res;
                        return $result;
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        public function update($id, Array $input)
        {       $statement = "
                        UPDATE `series` 
                        SET 
                                `series_name` = :series_name, 
                                `series_details` = :series_details,
                                `artist` = :artist,
                                `popular` = :popular,
                                `updated_at` = CURRENT_TIMESTAMP
                        WHERE `series`.`id` = :id;
                ";
                
                try {
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array(
                                'id' => (int) $id,
                                'series_name' => $input['series_name'],
                                'series_details' => $input['series_details'],
                                'artist' => $input['artist'],
                                'popular' => $input['popular'],
                        ));
                        return $statement->rowCount();
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        /**
         * return total records as integer
         * @return int
         */
        private static function getTotalRecord($db)
        {
                $statement = "SELECT series.id FROM series";
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
                $res = $this->find($id);
                $key = $res["series_key"];
                $statement = <<<EOS
                        DELETE FROM `series` WHERE `series`.`id` = $id;
                        DELETE FROM `images` WHERE `images`.`image_key` = $key;
                        DELETE FROM `comment` WHERE `comment`.`comment_key` = $key;
                EOS;

                try {
                        $statement=$this->db->prepare($statement);
                        if($statement->execute()){
                                foreach ($res["images"] as $images) {
                                        unlink("../$images");
                                }
                        }
                        return $statement->rowCount();
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
}
