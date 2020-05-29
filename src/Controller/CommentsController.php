<?php

namespace Src\Controller;

use Src\TableGateWays\CommentsGateway;

class CommentsController  
{
    private $db;
    private $requestMethod;
    private $comId;
    private $key;
    private $commentsGateway;

    public function __construct($db, $requestMethod, $key, $comId) {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->key = $key;
        $this->comId = $comId;

        $this->commentsGateway = new CommentsGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                $response = $this->getKeyedComments($this->key);
                break;
            case 'POST':
                $response = $this->addCommentFromRequest();
                break;
            case 'PUT':
                $response = $this->updateCommentFromRequest($this->comId);
                break;
            case 'DELETE':
                $response = $this->deleteComment($this->comId);
                break;
            default:
                $response = $this->notFoundResponse();
                break;
        }
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }

    private function getKeyedComments($key)
    {
        $result = $this->commentsGateway->findAllWithKey($key);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    private function addCommentFromRequest() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if(!$this->validateInput($input)){
            return $this->unprocessableEntityResponse();
        }
        $result = $this->commentsGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = json_encode($result);
        return $response;
    }
    private function updateCommentFromRequest($id) {
        $result = $this->commentsGateway->find($id);
        if(!$result){
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if(!$this->validateUpdateInput($input)){
            return $this->unprocessableEntityResponse();
        }
        $result = $this->commentsGateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = $result;
        return $response;
    }
    private function deleteComment($id) {
        $result = $this->commentsGateway->find($id);
        if(!$result){
            return $this->notFoundResponse();
        }
        $this->commentsGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = NULL;
        return $response;
    }
    private function validateInput($input) {
        if (! isset($input['comment_key'])) {
            return false;
        }
        if (! isset($input['comment'])) {
            return false;
        }
        return true;
    }
    private function validateUpdateInput($input) {
        if (! isset($input['comment'])) {
            return false;
        }
        return true;
    }
    private function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 422 Unprocessable Entity';
        $response['body'] = json_encode([
            'error' => 'Invalid input'
        ]);
        return $response;
    }

    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = "not found";
        return $response;
    }
}
