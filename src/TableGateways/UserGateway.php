<?php
namespace Src\TableGateways;

use PDO;

class UserGateway{
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function Login($email, $password)
    {
        $statement = <<<EOS
        SELECT * FROM  user WHERE email = 'leccelj@gmail.com';
    EOS;
        $statement = $this->db->prepare($statement);
        $statement->execute();
        $result = $statement->fetch(\PDO::FETCH_ASSOC);
        if ($statement->rowCount()) {
            if (password_verify($password, $result["password"])) {
                return array("status" => 1, "err" => 0);
            }
            else{
                return array("status" => 0, "err" => 1);
            }
        }
        
        else{
            return array("status" => 0, "err" => 1);
        }
    }
}