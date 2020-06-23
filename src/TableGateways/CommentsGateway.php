<?php
namespace Src\TableGateways;

class CommentsGateway
{
        private $db = null;
        public function __construct($db)
        {
                $this->db = $db;
        }
        public function findAllWithKey($key)
        {
                $statement = "
                        SELECT
                                *
                        FROM
                                comment
                        WHERE comment_key = ?;
                ";
                try {
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($key));
                        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
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
                                comment
                        WHERE id = ?;
                ";
                try {
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($id));
                        $result = $statement->fetchAll(\PDO::FETCH_ASSOC);
                        return $result;
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        public function insert(Array $input)
        {
                $statement = "
                        INSERT INTO comment
                                (name, comment, comment_key)
                        VALUES
                                (:name, :comment, :comment_key)
                ";
                try {
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array(
                                'name' => (!isset($input['name'])) 
                                ? "Anonymous" 
                                : (($input['name']=="") ? "Anonymous" : $input['name']),
                                
                                'comment' => strip_tags($input['comment']),
                                'comment_key' => $input['comment_key']
                        ));
                        return $this->findAllWithKey($input['comment_key']);
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        
        public function update(int $uid, Array $input)
        {
                $statement = "
                UPDATE `comment` 
                SET
                        `name` = :name, 
                        `comment` = :comment
                WHERE `comment`.`id` = :id;
                ";
                try {
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array(
                                'id' => (int) $uid,
                                'name' => $input['name'],
                                'comment' => strip_tags(stripslashes($input['comment'])),
                                
                        ));
                        return $statement->rowCount();
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        public function delete($id)
        {
                $statement = "
                        DELETE FROM comment
                        WHERE id = :id;
                ";

                try {
                        $statement=$this->db->prepare($statement);
                        $statement->execute(array('id' => $id));
                        return $statement->rowCount();
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
}
