<?php

namespace Src\Controller;

use Src\TableGateways\UserGateway;
 /**
  * User Authentication Controller.
  * @author OBA JOHN
  */

class UserController extends UserGateway
{
    protected $email, $password, $db;
    public function __construct($email, $password, $db) {
        parent::__construct($db);
        $this->$email = $email;
        $this->password = $password;
    }
    /**
     * Handles the incoming Auth Request, Interact with the Gateway and process output
     * @return json
     */
    public function proccessRequest()
    {
        $response = $this->LoginControlller($this->email, $this->password);
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }
    private function LoginControlller($email, $password)
    {
        $result = $this->Login($email, $password);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = \json_encode($result);
        return $response;
    }
}
