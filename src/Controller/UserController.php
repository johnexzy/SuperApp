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
    }
    /**
     * Handles the incoming Auth Request, Interact with the Gateway and process output
     * @return json
     */
    public function proccessRequest()
    {
        $response = $this->LoginControlller($this->username, $this->password);
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }
    private function LoginControlller($username, $password)
    {
        $result = $this->Login($username, $password);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = \json_encode($result);
        return $response;
    }
}
