<?php
namespace Src\TableGateways;

use PDO;
use PDOException;

define('LIMIT_PER_PAGE', 10);
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
        $res = array_merge(
            $this->QueryMusic($query), 
            $this->QueryMovie($query),
            $this->QuerySeries($query)
        );
        shuffle($res);
        return array(
            "errors" => 0,
            "data"=>array($this->QueryMusic($query), 
            $this->QueryMovie($query),
            $this->QuerySeries($query))
        );
            
    }

    private function QueryMusic($query, $pageNo = 1)
    {
        $limit = LIMIT_PER_PAGE;
        $startFrom = ($pageNo - 1) * $limit;
        $totalRecord = self::getTotalRecord($this->db, $query, "music");
        $totalPages = \ceil($totalRecord / $limit);
        $statement = "SELECT * FROM `music` 
                        WHERE (`music_name` LIKE '%$query%') 
                        OR (`artist` LIKE '%$query%') 
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
            $result = ["music" => $data];
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

    private function QueryMovie($query, $pageNo = 1)
    {
        $limit = LIMIT_PER_PAGE;
        $startFrom = ($pageNo - 1) * $limit;
        $totalRecord = self::getTotalRecord($this->db, $query, "movies");
        $totalPages = \ceil($totalRecord / $limit);
        $statement = "SELECT * FROM `movies` 
                        WHERE (`video_name` LIKE '%$query%') 
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
            $result = ["movies" => $data];
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

    private function QuerySeries($query, $pageNo = 1){
        
        $limit = LIMIT_PER_PAGE;
        $startFrom = ($pageNo - 1) * $limit;
        $totalRecord = self::getTotalRecord($this->db, $query, "series");
        $totalPages = \ceil($totalRecord / $limit);
        $statement = "SELECT * FROM `series` 
                        WHERE (`series_name` LIKE '%$query%') 
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
            $result = ["series" => $data];
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
        } catch(PDOException $e){
            exit($e->getMessage()());
        }
    }
    private static function getTotalRecord(PDO $db, $query, $group)
    {   
        $statement = "";
        switch ($group) {
            case 'music':
                $statement = "SELECT * FROM `music` 
                                WHERE (`music_name` LIKE '%$query%') 
                                OR (`artist` LIKE '%$query%')
                                ORDER BY `music_name`
                        ";
                break;
            case 'movies':
                $statement = "SELECT * FROM `movies` 
                        WHERE (`video_name` LIKE '%$query%') 
                        ORDER BY `video_name`
                    ";
                break;
            case 'series':
                $statement = "SELECT * FROM `series` 
                        WHERE (`series_name` LIKE '%$query%') 
                        ORDER BY `series_name`
                    ";
                break;
            
            default:
                # code...
                break;
        }
        try {
            $statement = $db->query($statement);
            $result = $statement->fetchAll(\PDO::FETCH_COLUMN);
            return $result = count($result);
        } catch (\PDOException $th) {
            exit($th->getMessage());
        }
    }


}
