<?php
namespace Src\TableGateways;

use Src\Logic\MakeFile;
use Src\TableGateways\CommentsGateway;


class NewsGateway extends CommentsGateway
{
    private $db = null;
    public function __construct($db)
        {       
                parent::__construct($this->db);
                $this->db = $db;
                
                
        }
        public function getAll()
        {
                $statement = "
                        SELECT
                                *
                        FROM
                                News
                        ORDER 
                            BY id DESC;
                ";
                
                try {   
                        $result = array();
                        $statement = $this->db->query($statement);
                        while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
                                $comm = $this->findAllWithKey($res["post_key"]);
                                $images = $this->getPostImages($res["post_key"]);
                                $res["post_images"] = $images;
                                $res += ["comments" => $comm];
                                $result[] = $res;
                        }
                        return $result;
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }

        public function getAllWithCategory($cat)
        {
                $statement = "
                        SELECT * 
                        FROM  News
                        WHERE post_category = ?
                        ORDER BY id DESC;
                ";
                
                try {
                        $result = array();
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($cat));
                        while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
                                $comm = $this->findAllWithKey($res["post_key"]);
                                $images = $this->getPostImages($res["post_key"]);
                                $res["post_images"] = $images;
                                $res += ["comments" => $comm];
                                $result[] = $res;
                        }
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
                                News
                        WHERE id = ?;
                ";
                try {
                        $result = null;
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($id));
                        while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
                                $comm = $this->findAllWithKey($res["post_key"]);
                                $images = $this->getPostImages($res["post_key"]);
                                $res["post_images"] = $images;
                                
                                $res += ["comments" => $comm];
                                $result = $res;
                        }
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
                                News
                        WHERE post_short_url = ?;
                ";
                try {
                        $result = null;
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($short_url));
                        while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
                                $comm = $this->findAllWithKey($res["post_key"]);
                                $images = $this->getPostImages($res["post_key"]);
                                $res["post_images"] = $images;
                                
                                $res += ["comments" => $comm];
                                $result = $res;
                        }
                        return $result;
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        public function insert(Array $input)
        {
                $ddd = new MakeFile();
                $statement = "
                        INSERT INTO News
                                (post_title, post_body, post_images, post_key, post_category, author, post_short_url)
                        VALUES
                                (:post_title, :post_body, :post_images, :post_key, :post_category, :author, :post_short_url)
                ";
                $statementImage = "
                        INSERT INTO images
                                (image_url, image_key)
                        VALUES
                                (:image_url, :image_key)
                ";
                try {
                        $post_key = md5($input['post_title'].rand(123, 2345621));
                        $statement = $this->db->prepare($statement);
                        $statementImage = $this->db->prepare($statementImage);
                        $statement->execute(array(
                                'post_title' => $input['post_title'],
                                'post_body' => $input['post_body'],
                                'post_images' => $ddd->makeImg($input['post_images'][0]),
                                'post_key' => $post_key,
                                'post_category' => $input['post_category'],
                                'author' => $input['author'],
                                'post_short_url' => str_replace(".", "-", str_replace(" ", "-", $input['post_title']."-".rand(12345, 23456219090)))
                                
                        ));
                        foreach ($input['post_images'] as $image) {
                                
                                $statementImage->execute(array(
                                        'image_url' => $ddd->makeImg($image),
                                        'image_key' => $post_key
                                ));
                        }
                        return $statement->rowCount();
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        public function getPostImages(String $postKey)
        {
                $statement = "
                        SELECT
                                image_url
                        FROM
                                images
                        WHERE image_key = ?;
                ";
                try {
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($postKey));
                        $result = $statement->fetchAll(\PDO::FETCH_COLUMN);
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
                $statement = "DELETE FROM `news` WHERE `news`.`id` = :id";

                try {
                        $statement=$this->db->prepare($statement);
                        $statement->execute(array('id' => $id));
                        return $statement->rowCount();
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
}
