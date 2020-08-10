<?php
namespace Src\TableGateways;

use PDO;

class SearchGateway
{
    private $db;
    public function __construct(PDO $db) {
        $this->db = $db;
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
        return $res;
            
    }

    private function QueryMusic($query)
    {
        $statement = "SELECT * FROM `music` 
                        WHERE (`music_name` LIKE '%$query%') 
                        OR (`artist` LIKE '%$query%') 
                        ORDER BY `music_name`
                    ";
        $statement = $this->db->query($statement);
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
        
        
    }

    private function QueryMovie($query)
    {
        $statement = "SELECT * FROM `movies` 
                        WHERE (`video_name` LIKE '%$query%') 
                        ORDER BY `video_name`
                    ";
        $statement = $this->db->query($statement);
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }

    private function QuerySeries($query){
        $statement = "SELECT * FROM `series` 
                        WHERE (`series_name` LIKE '%$query%') 
                        ORDER BY `series_name`
                    ";
        $statement = $this->db->query($statement);
        $res = $statement->fetchAll(PDO::FETCH_ASSOC);
        return $res;
    }
    private static function getTotalRecord(PDO $db, $query, $group)
    {
        $statement = "SELECT movies.id FROM movies";
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
