<?php
namespace Src\TableGateways;

class UserGateway{
    protected $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function Login($username, $password)
    {
        $statement = <<<EOS
        SELECT * FROM  user WHERE name = '$username';
    EOS;
        $statement = $this->db->prepare($statement);
        $statement->execute();
        $row = $statement->rowCount();
    }
}