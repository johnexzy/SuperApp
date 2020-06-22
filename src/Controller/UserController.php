<?php

namespace Src\Controller;

use Src\TableGateways\UserGateway;
 /**
  * User Authentication Controller.
  * @author OBA JOHN
  */

class UserController extends UserGateway
{
    protected $username, $password;
    public function __construct($username, $password) {
        $this->username = $username;
        $this->password = $password;
    }
    /**
     * Handles the incoming Auth Request, Interact with the Gateway and process output
     * @return json
     */
    public function proccessRequest()
    {
        
    }
}
