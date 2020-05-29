<?php
namespace Src\TableGateways;

use Src\Logic\MakeImage;
use Src\TableGateWays\CommentsGateway;


class CarouselGateway
{
    private $db = null;
    public $getComment = null;
    public function __construct($db)
        {
                $this->db = $db;
                $this->getComment = new CommentsGateway($db);
                
        }
        public function makeImg(String $base64) {
        //     $img = Image::make($base64);
        //     $img->save('uploads/'. rand(123345, 13278787875335).'.jpg');
        //     return $img->dirname."/".$img->basename;

        }
        public function getAll()
        {
                $statement = "
                        SELECT
                                *
                        FROM
                                carousel
                        ORDER 
                            BY id DESC;
                ";
                
                try {   
                        $result = array();
                        $statement = $this->db->query($statement);
                        while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
                                $comm = $this->getComment->findAllWithKey($res["carousel_key"]);
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
                        FROM  carousel
                        WHERE  = ?
                        ORDER BY id DESC;
                ";
                
                try {
                        $result = array();
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($cat));
                        while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
                                $comm = $this->getComment->findAllWithKey($res["carousel_key"]);
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
                                carousel
                        WHERE id = ?;
                ";
                try {
                        $result = null;
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($id));
                        while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
                                $comm = $this->getComment->findAllWithKey($res["carousel_key"]);
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
                                carousel
                        WHERE carousel_short_url = ?;
                ";
                try {
                        $result = null;
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($short_url));
                        while ($res = $statement->fetch(\PDO::FETCH_ASSOC)) {
                                $comm = $this->getComment->findAllWithKey($res["carousel_key"]);
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
                $ddd = new MakeImage();
                $statement = "
                        INSERT INTO carousel
                                (carousel_title, carousel_body, carousel_image, carousel_key, author, carousel_short_url)
                        VALUES
                                (:carousel_title, :carousel_body, :carousel_image, :carousel_key, :author, :carousel_short_url)
                ";
                try {
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array(
                                'carousel_title' => $input['carousel_title'],
                                'carousel_body' => $input['carousel_body'],
                                'carousel_image' => $ddd->makeImg($input['carousel_image']),
                                'carousel_key' => md5($input['carousel_title'].rand(123, 2345621)),
                                'author' => $input['author'],
                                'carousel_short_url' => str_replace(".", "-", str_replace(" ", "-", $input['carousel_title'])."-".rand(12345, 1234587343))
                                
                        ));
                        return $statement->rowCount();
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        public function update($uid, Array $input)
        {       $statement = "
                        UPDATE `carousel` 
                        SET 
                                `carousel_title` = :carousel_title, 
                                `carousel_body` = :carousel_body,
                                `updated_at` = CURRENT_TIMESTAMP
                        WHERE `carousel`.`id` = :id;
                ";
                
                try {
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array(
                                'id' => (int) $uid,
                                'carousel_title' => $input['carousel_title'],
                                'carousel_body' => $input['carousel_body']
                        ));
                        return $statement->rowCount();
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        public function delete($id)
        {
                $statement = "DELETE FROM `carousel` WHERE `carousel`.`id` = :id";

                try {
                        $statement=$this->db->prepare($statement);
                        $statement->execute(array('id' => $id));
                        return $statement->rowCount();
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
}
