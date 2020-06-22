<?php

namespace Src\Controller;

use Src\TableGateways\UserGateway;
 /**
  * User Authentication Controller.
  * @author OBA JOHN
  */

class UserController extends UserGateway
{
    protected $username, $password, $db;
    public function __construct($username, $password, $db) {
        parent::__construct($db);
        $this->username = $username;
        $this->password = $password;
        $this->db = $db;
    }
    /**
     * Handles the incoming Auth Request, Interact with the Gateway and process output
     * @return json
     */
    public function proccessRequest()
    {
        
    }
    private function LoginControlller($username, $password)
    {
        
    }
}
