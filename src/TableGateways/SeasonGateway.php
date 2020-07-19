<?php
namespace Src\TableGateways;

use Src\TableGateways\EpisodeGateway;
use Src\TableGateways\CommentsGateway;

class SeasonGateway
{
        private $db = null, $comment, $episode;
        public function __construct($db)
        {
                $this->db = $db;
                $this->comment = new CommentsGateway($db);
                $this->episode = new EpisodeGateway($db);
        }
        
        
        public function insert(Array $input)
        {
                $statement = "
                        INSERT INTO seasons
                                (season_key, series_key, short_url, season_name)
                        VALUES
                                (:season_key, :series_key, :short_url, :season_name)
                ";
                try {
                        $_key = $input['series_key'].md5($input['season_name'].mt_rand(0, 10));
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array(
                                'season_key' => $_key,
                                'series_key' => $input['series_key'],
                                'short_url' => str_replace(".", "-", str_replace(" ", "-", $input['season_name']."-".mt_rand())),
                                'season_name' => $input['season_name']
                        ));
                        return $statement->rowCount();
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
                                seasons
                        WHERE series_key = ?;
                ";
                try {
                        $result = [];
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($key));
                        while($res = $statement->fetch(\PDO::FETCH_ASSOC)){
                                $episodes = $this->episode->findAllWithKey($res["season_key"]);
                                $res += ["episodes" => $episodes];
                                $result[] = $res;
                        }
                        return $result;
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }


        /**
         * get Season by short url
         */
        public function getByUrl($short_url, $series_key)
        {
                $statement = "
                        SELECT
                                *
                        FROM
                                `seasons`
                        WHERE `seasons`.`short_url` = :short_url 
                        AND `seasons`.`series_key` = :series_key;
                ";
                
                try {   
                        $result = null;
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array(
                                'short_url' => $short_url,
                                'series_key' => $series_key
                        ));
                        $res = $statement->fetch(\PDO::FETCH_ASSOC);
                        $comm = $this->comment->findAllWithKey($res["season_key"]);
                        $episodes = $this->episode->findAllWithKey($res["season_key"]);
                        $res += ["comments" => $comm];
                        $res += ["episodes" => $episodes];
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
                                seasons
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
       

}
