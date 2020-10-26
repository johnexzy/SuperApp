<?php
namespace Src\TableGateways;

use PDO;
use PDOException;

define('LIMIT_PER_PAGE', 300);
class SearchGateway
{
    private $db;
    private $imageInherited, $comment;
    public function __construct(PDO $db) {
        $this->db = $db;
        $this->imageInherited = new ImageGateway($db);
        $this->comment = new CommentsGateway($db);
    }
    
    /**
     * Compile Search Query and Processes Output search
     * @return array
     */
    public function handleSearchQuery($query)
    {
        // $query = mysqli_real_escape_string(mysqli_init(), $query);
        $query = trim(str_replace("'", "", $query));
        return array(
            "errors" => 0,
            "msg" => "Search result for $query",
            "data"=>array(
                        $this->QueryMusic($query), 
                        $this->QueryMovie($query),
                        $this->QuerySeries($query)
                    )
        );
            
    }

    private function QueryMusic($query, $pageNo = 1)
    {
        $sql = self::createQuery($query, "music");
        $limit = LIMIT_PER_PAGE;
        $startFrom = ($pageNo - 1) * $limit;
        $statement = "SELECT * FROM `music`
                        $sql 
                        ORDER BY `music_name`
                        DESC LIMIT $startFrom, $limit;
                    ";
        try {   
            $data = array();
            $statement = $this->db->query($statement);
            while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
                    $comm = $this->comment->findAllWithKey($res["music_key"]);
                    $images = $this->imageInherited->getPostImages($res["music_key"]);
                    $res += ["images" => $images];
                    $res += ["comments" => $comm];
                    $data[] = $res;
            }
            $result = ["group" => "music"];
            $result += ["data" => $data];
            return $result;
        } catch (\PDOException $e) {
                exit($e->getMessage());
        }
        
    }

    private function QueryMovie($query, $pageNo = 1)
    {
        $sql = self::createQuery($query, "video");
        $limit = LIMIT_PER_PAGE;
        $startFrom = ($pageNo - 1) * $limit;
        $statement = "SELECT * FROM `movies` 
                        $sql 
                        ORDER BY `video_name`
                        DESC LIMIT $startFrom, $limit;
                    ";
        try {   
            $data = array();
            $statement = $this->db->query($statement);
            while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
                    $comm = $this->comment->findAllWithKey($res["video_key"]);
                    $images = $this->imageInherited->getPostImages($res["video_key"]);
                    $res += ["images" => $images];
                    $res += ["comments" => $comm];
                    $data[] = $res;
            }
            $result = ["group" => "movies"];
            $result += ["data" => $data];
            return $result;
        } catch (\PDOException $e) {
                exit($e->getMessage());
        }
    }

    private function QuerySeries($query, $pageNo = 1){
        $sql = self::createQuery($query, "series");
        $limit = LIMIT_PER_PAGE;
        $startFrom = ($pageNo - 1) * $limit;
        $statement = "SELECT * FROM `series` 
                        $sql 
                        ORDER BY `series_name`
                        DESC LIMIT $startFrom, $limit;
                    ";
        try {   
            $data = array();
            $statement = $this->db->query($statement);
            while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
                    $comm = $this->comment->findAllWithKey($res["series_key"]);
                    
                    $images = $this->imageInherited->getPostImages($res["series_key"]);
                    $res += ["images" => $images];
                    $res += ["comments" => $comm];
                    $data[] = $res;
            }
            $result = ["group" => "series"];
            $result += ["data" => $data];
            
            return $result;
        } catch(PDOException $e){
            exit($e->getMessage()());
        }
    }
    /**
     * @param String $query Users search input
     * @param String $group Field to search for (video, music, series)
     * @return String $querySql
     */
    private static function createQuery(String $query, $group)
    {
        $listOddWord = [
            "a",
            "the",
            "be",
            "and",
            "in",
            "is",
            "of",
            "me",
            "they",
            "she",
            "he",
            "it",
            "I",
            "us",
            "or"
        ];

        $queryChar = [];
        $lim = 0;
        foreach (explode(" ", $query) as $val) {
            if (in_array($val, $listOddWord)) {
                continue;
            }
            array_push($queryChar, $val);
            if($lim == 30) break;
            $lim ++;
        }
        $querySql = "WHERE (`".$group."_name` LIKE '%$query%' )";
        if($group == "music") {
            $querySql .= "OR (`artist` LIKE '%$query%')";
            
        }
        foreach ($queryChar as $val) {
                switch ($group) {
                    case 'music':
                        $querySql .= "OR (`music_name` LIKE '%$val%')";
                        $querySql .= "OR (`artist` LIKE '%$val%')";
                        break;
                    
                    case 'series':
                        $querySql .= "OR (`series_name` LIKE '%$val%')";
                        break;
                    case 'video':
                        $querySql .= "OR (`video_name` LIKE '%$val%')";
                        break;
                    default:
                        # code...
                        break;
                }
        }
        return $querySql;
    }

}
