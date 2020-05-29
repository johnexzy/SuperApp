<?php

namespace Src\Controller;

use Src\TableGateways\NewsGateway;

class NewsController  
{
    private $db;
    private $requestMethod;
    private $id;
    private $cat;
    private $newsGateway;
    private $short_url;

    public function __construct($db, $requestMethod, $cat, $id, $short_url) {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->cat = $cat;
        $this->id = $id;
        $this->short_url = $short_url;
        $this->newsGateway = new NewsGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if($this->cat){
                    $response = $this->getNewsByCat($this->cat);
                }
                elseif($this->id){
                    $response = $this->getNewsById($this->id);
                }
                elseif($this->short_url){
                    $response = $this->getNewsByUrl($this->short_url);
                }
                else{
                    $response = $this->getAllNews();
                }
                break;
            case 'POST':
                $response = $this->addNewsFromRequest();
                break;
            case 'PUT':
                $response = $this->updateNewsFromRequest($this->id);
                break;
            case 'DELETE':
                $response = $this->deleteNews($this->id);
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

    private function getNewsById($id)
    {
        $result = $this->newsGateway->find($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    private function getNewsByUrl($Url)
    {
        $result = $this->newsGateway->findByUrl($Url);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    private function getAllNews()
    {
        $result = $this->newsGateway->getAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    private function getNewsByCat($cat)
    {
        $result = $this->newsGateway->getAllWithCategory($cat);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    private function addNewsFromRequest() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if(!$this->validateInput($input)){
            return $this->unprocessableEntityResponse();
        }
        $result = $this->newsGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
       $response['body'] = json_encode($result);
        return $response;
    }
    private function updateNewsFromRequest($id) {
        $result = $this->newsGateway->find($id);
        if(!$result){
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if(!$this->validateUpdateInput($input)){
            return $this->unprocessableEntityResponse();
        }
        $this->newsGateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }
    private function deleteNews($id) {
        $result = $this->newsGateway->find($id);
        if(!$result){
            return $this->notFoundResponse();
        }
        $this->newsGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = NULL;
        return $response;
    }
    private function validateInput($input) {
        if ((!isset($input['post_body'])) || (! isset($input['post_title']))
                || (! isset($input['post_images'][0])) || 
                (! isset($input['post_category'])) || (! isset($input['author']))
           ) {
            return false;
        }
        return true;
    }
    private function validateUpdateInput($input) {
        if (! isset($input['post_body'])) {
            return false;
        }
        if (! isset($input['post_title'])) {
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
        $response['body'] = null;
        return $response;
    }
}
