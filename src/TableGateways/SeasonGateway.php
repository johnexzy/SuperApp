<?php
namespace Src\TableGateways;

use PDOException;
use Src\Logic\MakeFile;
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
                                (series_name, season_key, series_key, short_url, season_name)
                        VALUES
                                (:series_name, :season_key, :series_key, :short_url, :season_name)
                ";
                try {
                        $_key = $input['series_key'].md5($input['season_name'].mt_rand(0, 10));
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array(
                                'series_name' => $input['series_name'],
                                'season_key' => $_key,
                                'series_key' => $input['series_key'],
                                'short_url' => MakeFile::normalizeString($input['season_name']."-").mt_rand(),
                                'season_name' => $input['season_name']
                        ));
                        return $this->findAllWithKey($input["series_key"]);
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
        public function getByUrl($short_url, $series_name)
        {
                $statement = "
                        SELECT
                                *
                        FROM
                                `seasons`
                        WHERE `seasons`.`short_url` = :short_url 
                        AND `seasons`.`series_name` = :series_name;
                ";
                
                try {   
                        $result = null;
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array(
                                'short_url' => $short_url,
                                'series_name' => $series_name
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
        
        public function findByUrl($short_url, $series_name)
        {
                $statement = "
                        SELECT
                        *
                        FROM
                                `seasons`
                        WHERE `seasons`.`short_url` = :short_url 
                        AND `seasons`.`series_name` = :series_name;
                ";
                
                try {   
                        
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array(
                                'short_url' => $short_url,
                                'series_name' => $series_name
                        ));
                        $res = $statement->fetch(\PDO::FETCH_ASSOC);
                        
                        return $res;
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        
        /**
         * find season by season_key
         * @param String $season_key
         * @return String
         */
        public function findNameByKey($season_key)
        {
                $statement = "
                        SELECT
                                season_name
                        FROM
                                `seasons`
                        WHERE season_key = ?;
                ";
                try {   
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($season_key));
                        $res = $statement->fetchAll(\PDO::FETCH_COLUMN);
                        
                                return $res[0];
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
                // $statement = "
                //         SELECT
                //                 image_url
                //         FROM
                //                 images
                //         WHERE image_key = ?;
                // ";
                // try {
                //         $statement = $this->db->prepare($statement);
                //         $statement->execute(array($season_key));
                //         $result = $statement->fetchAll(\PDO::FETCH_COLUMN);
                //         return $result;
                // } catch (\PDOException $e) {
                //         exit($e->getMessage());
                // }
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
        
        public function find($id)
        {
                
                $statement = "
                        SELECT
                                *
                        FROM
                                `seasons`
                        WHERE id = ?;
                ";
                try {   
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($id));
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


        public function delete($id, Array $res)
        {
                
                try {
                        
                        if (!$res) {
                                throw new PDOException("No data found with $id");
                        }
                        $key = $res["season_key"];
                        $serkey = $res["series_key"];
                        foreach ($res["episodes"] as $episodeId) {
                               $this->episode->delete($episodeId["id"]); 
                        }
                        
                        $statement = <<<EOS
                                DELETE FROM `seasons` WHERE `seasons`.`id` = $id;
                                DELETE FROM `comment` WHERE `comment`.`comment_key` = $key;
                        EOS;

                        $statement=$this->db->prepare($statement);
                        if($statement->execute()){
                                unset($statement);
                                return $this->findAllWithKey($serkey);
                        }



                        
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
}
