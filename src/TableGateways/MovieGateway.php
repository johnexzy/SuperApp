<?php

namespace Src\TableGateWays;

/**
 * Description of MusicGateWay
 * @author ObaJohn
 */
use Src\TableGateWays\VideoGateway;
use Src\TableGateWays\CommentsGateway;
use Src\TableGateWays\ImageGateway;
class MovieGateway extends VideoGateway {
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
                $statement = "INSERT INTO movies
                                (video_name, video_details, video_key,  category, short_url, uploaded_by, popular)
                        VALUES
                                (:video_name, :video_details, :video_key,  :category, :short_url, :uploaded_by, :popular)";
                try {
                        $_key = md5($input['video_name'].mt_rand());
                        $query = $this->db->prepare($statement);
                        $query->execute(array(
                                'video_name' => $input['video_name'],
                                'video_details' => $input['video_details'],
                                'category' => $input['category'],
                                'video_key' => $_key,
                                'uploaded_by' => $input['author'],
                                'popular' => $input['popular'],
                                'short_url' => str_replace(".", "-", str_replace(" ", "-", $input['video_name']."-".mt_rand()))

                        ));
                        $this->imageInherited->createImage($input['images'], $_key);
                        $this->createvideo($input['video'], $input['video_name']."-". mt_rand(0, 200), $_key);
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
                                movies
                        ORDER 
                            BY id DESC;
                " : "
                        SELECT
                                *
                        FROM
                                movies
                        ORDER 
                            BY id DESC LIMIT $lim
                        ;
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
                                movies
                        ORDER BY id 
                            DESC LIMIT $startFrom, $limit;";
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
                                movies
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
                                $res += ["$videos" => $videos[0]]; //pnly one file is needed. just incase
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
                                movies
                        WHERE short_url = ?;
                ";
                
                try {   
                        $result = null;
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($short_url));
                        $res = $statement->fetch(\PDO::FETCH_ASSOC);
                        $comm = $this->comment->findAllWithKey($res["video_key"]);
                        $videos = $this->getAllWithKey($res["video_key"]);
                        $images = $this->imageInherited->getPostImages($res["video_key"]);
                        $res += ["videos" => $videos[0]]; //pnly one file is needed. just incase
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
                                movies
                        WHERE id = ?;
                ";
                try {   
                        $result = null;
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($id));
                        $res = $statement->fetch(\PDO::FETCH_ASSOC);
                        $comm = $this->comment->findAllWithKey($res["video_key"]);
                        $videos = $this->getAllWithKey($res["video_key"]);
                        $images = $this->imageInherited->getPostImages($res["video_key"]);
                        $res += ["videos" => $videos[0]]; //pnly one file is needed. just incase
                        $res += ["images" => $images];
                        $res += ["comments" => $comm];
                        $result = $res;
                        return $result;
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        // public function update($uid, Array $input)
        // {       $statement = "
        //                 UPDATE `Music` 
        //                 SET 
        //                         `post_title` = :post_title, 
        //                         `post_body` = :post_body,
        //                         `updated_at` = CURRENT_TIMESTAMP
        //                 WHERE `Music`.`id` = :id;
        //         ";
                
        //         try {
        //                 $statement = $this->db->prepare($statement);
        //                 $statement->execute(array(
        //                         'id' => (int) $uid,
        //                         'post_title' => $input['post_title'],
        //                         'post_body' => $input['post_body']
        //                 ));
        //                 return $statement->rowCount();
        //         } catch (\PDOException $e) {
        //                 exit($e->getMessage());
        //         }
        // }
        /**
         * return total records as integer
         * @return int
         */
        private static function getTotalRecord($db)
        {
                $statement = "SELECT movies.id FROM movies";
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
                $statement = "DELETE FROM `movies` WHERE `movies`.`id` = :id";

                try {
                        $statement=$this->db->prepare($statement);
                        $statement->execute(array('id' => $id));
                        return $statement->rowCount();
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
}