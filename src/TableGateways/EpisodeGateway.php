<?php

namespace Src\TableGateways;

/**
 * Description of MusicGateWay
 * @author ObaJohn
 */

use PDOException;
use Src\TableGateways\VideoGateway;
use Src\TableGateways\CommentsGateway;
use Src\TableGateways\ImageGateway;
class EpisodeGateway extends VideoGateway {
    private $db = null;
    private $imageInherited = null;
    private $comment = null;
    const LIMIT_PER_PAGE = 8;
    public function __construct($db)
        {       
                parent::__construct($db);
                $this->db = $db;
                $this->imageInherited = new ImageGateway($db);
                $this->comment = new CommentsGateway($db);
        }
        
        public function insert(Array $input)
        {
                $statement = "INSERT INTO episodes
                                (ep_name, ep_details, ep_key,  short_url, season_key)
                        VALUES
                                (:ep_name, :ep_details, :ep_key,  :short_url, :season_key)";
                try {
                        $_key = $input["season_key"].md5($input['ep_name'].mt_rand(1, 10));
                        if($this->imageInherited->createImage($input['images'], $_key) == null ){
                                throw new PDOException("Error Creating Image");
                        } 
                        if ($this->createvideo($input['video'], $input['video_name']."-". mt_rand(0, 200), $_key)) {
                                throw new PDOException("Error Creating Video");
                                
                        }
                        $query = $this->db->prepare($statement);
                        $query->execute(array(
                                'ep_name' => $input['ep_name'],
                                'ep_details' => $input['ep_details'],
                                'ep_key' => $_key,
                                'season_key' => $input['season_key'],
                                'short_url' => str_replace(".", "-", str_replace(" ", "-", $input['ep_name']."-".mt_rand()))
                        ));
                        
                        return $query->rowCount();
                } catch (PDOException $e) {
                        exit($e->getMessage());
                }
        }
        public function getAll($lim = null)
        {
                $statement = ($lim == null) ? "
                        SELECT
                                *
                        FROM
                                episodes
                        ORDER 
                            BY id DESC;
                " : "
                        SELECT
                                *
                        FROM
                                episodes
                        ORDER 
                            BY id DESC LIMIT $lim
                        ;
                ";
                try {   
                        $result = array();
                        $statement = $this->db->query($statement);
                        while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
                                $comm = $this->comment->findAllWithKey($res["ep_key"]);
                                $videos = $this->getAllWithKey($res["ep_key"]);
                                $images = $this->imageInherited->getPostImages($res["ep_key"]);
                                $res += ["videos" => $videos]; //pnly one file is needed. just incase
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
         * get song by pages
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
                                episodes
                        ORDER BY video_name 
                            ASC LIMIT $startFrom, $limit;";
                try {   
                        $data = array();
                        $statement = $this->db->query($statement);
                        while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
                                $comm = $this->comment->findAllWithKey($res["video_key"]);
                                $videos = $this->getAllWithKey($res["video_key"]);
                                $images = $this->imageInherited->getPostImages($res["video_key"]);
                                $res += ["videos" => $videos]; //pnly one file is needed. just incase
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
         * get popular songs
         */
        public function getPopular($popularInt)
        {
                $statement = "
                        SELECT
                                *
                        FROM
                                episodes
                        WHERE popular > 0
                        ORDER 
                            BY id DESC LIMIT $popularInt;
                ";
                try {   
                        $result = array();
                        $statement = $this->db->query($statement);
                        while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
                                $comm = $this->comment->findAllWithKey($res["video_key"]);
                                $videos = $this->getAllWithKey($res["video_key"]);
                                $images = $this->imageInherited->getPostImages($res["video_key"]);
                                $res += ["videos" => $videos]; //pnly one file is needed. just incase
                                $res += ["images" => $images];
                                $res += ["comments" => $comm];
                                $result[] = $res;
                        }
                        return $result;
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        public function findAllWithKey($key)
        {
                $statement = "
                        SELECT
                                *
                        FROM
                                episodes
                        WHERE season_key = ?;
                ";
                try {
                        $result = [];
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($key));
                        while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
                                $comm = $this->comment->findAllWithKey($res["ep_key"]);
                                $videos = $this->getAllWithKey($res["ep_key"]);
                                $images = $this->imageInherited->getPostImages($res["ep_key"]);
                                $res += ["videos" => $videos]; //pnly one file is needed. just incase
                                $res += ["images" => $images];
                                $res += ["comments" => $comm];
                                $result[] = $res;
                        }
                        return $result;
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        public function getByUrl($short_url, $series_name)
        {
                $statement = "  SELECT
                                *
                                FROM
                                        `episodes`
                                WHERE `episodes`.`short_url` = :short_url 
                                AND `episodes`.`series_name` = :series_name;
                ";
                
                try {   
                        $result = null;
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array(
                                'short_url' => $short_url,
                                'series_name' => $series_name
                        ));
                        $res = $statement->fetch(\PDO::FETCH_ASSOC);
                        if (!$res) {
                                return $res;
                        }
                        $comm = $this->comment->findAllWithKey($res["ep_key"]);
                        $videos = $this->getAllWithKey($res["ep_key"]);
                        $images = $this->imageInherited->getPostImages($res["ep_key"]);
                        $res += ["videos" => $videos]; //pnly one file is needed. just incase
                        $res += ["images" => $images];
                        $res += ["comments" => $comm];
                        $result = $res;
                        return $result;
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        
        /**
         * this method checks availability of data by id
         */
        public function find($id)
        {
                
                $statement = "
                        SELECT
                                *
                        FROM
                                episodes
                        WHERE id = ?;
                ";
                try {   
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($id));
                        $res = $statement->fetch(\PDO::FETCH_ASSOC);
                        $comm = $this->comment->findAllWithKey($res["video_key"]);
                        $videos = $this->getAllWithKey($res["video_key"]);
                        $images = $this->imageInherited->getPostImages($res["video_key"]);
                        $res += ["videos" => $videos]; //pnly one file is needed. just incase
                        $res += ["images" => $images];
                        $res += ["comments" => $comm];
                        $result = $res;
                        return $result;
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }

         
        /**
         * this method checks availability of data by short_url
         */
        public function findByUrl($short_url, $series_name)
        {
                
                $statement = "
                        SELECT
                        *
                        FROM
                                `episodes`
                        WHERE `episodes`.`short_url` = :short_url 
                        AND `episodes`.`series_name` = :series_name;
                ";
                try {   
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($short_url));
                        $res = $statement->fetch(\PDO::FETCH_ASSOC);
                        $result = $res;
                        return $result;
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        public function update($id, Array $input)
        {       
                $statement = "UPDATE `episodes` 
                                SET 
                                        `video_name` = :video_name, 
                                        `video_details` = :video_details,
                                        `category` = :category,
                                        `popular` = :popular,
                                        `updated_at` = CURRENT_TIMESTAMP
                                WHERE `episodes`.`id` = :id;
                        ";
                
                try {
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array(
                                'id' => (int) $id,
                                'video_name' => $input['video_name'],
                                'video_details' => $input['video_details'],
                                'category' => $input['category'],
                                'popular' => $input['popular']
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
                $statement = "SELECT episodes.id FROM episodes";
                try {
                        $statement = $db->query($statement);
                        $result = $statement->fetchAll(\PDO::FETCH_COLUMN);
                        return $result = count($result);
                } catch (\PDOException $th) {
                        exit($th->getMessage());
                }
        }
        
        /**
         * Deletes a record from db. unlink all raw files
         * @param int $id
         * @return int
         */
        public function delete($id)
        {
                $res = $this->find($id);
                $key = $res["video_key"];
                $statement = <<<EOS
                        DELETE FROM `episodes` WHERE `episodes`.`id` = $id;
                        DELETE FROM `images` WHERE `images`.`image_key` = $key;
                        DELETE FROM `videos` WHERE `videos`.`video_key` = $key;
                        DELETE FROM `comment` WHERE `comment`.`comment_key` = $key;
                EOS;

                try {
                        $statement=$this->db->prepare($statement);
                        if($statement->execute()){
                                foreach ($res["images"] as $images) {
                                        unlink("../$images");
                                }
                                foreach ($res["videos"] as $video) {
                                        unlink("../$video[video_url]");
                                }
                        }
                        return $statement->rowCount();
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
}
