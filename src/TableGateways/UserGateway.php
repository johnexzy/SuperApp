<?php
namespace Src\TableGateways;

class UserGateway{
    protected $username;
    protected $password;

    public function __construct($username, $password) {
        $this->username = $username;
    }
}