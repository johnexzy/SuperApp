<?php
namespace Src\Controller;

use PDO;
use Src\TableGateways\ImageGateway;

/**
 * Handles Incoming Image Request
 */

 class ImageController extends ImageGateway
 {
     protected $key, $requestMethod;
     public function __construct(PDO $db, String $key, $requestMethod) {
         parent::__construct($db);
         $this->key = $key;
         $this->requestMethod = $requestMethod;
     }
     /**
      * Proccess All request for Images
      */
     public function processRequest()
     {
        switch ($this->requestMethod) {
            case 'GET':
                $response = ($this->key)? $this->getKeyedImages($this->key)
                : $this->addImagesFromGet($this->key);
                break;
            case 'POST':
                $response = $this->addImagesFromRequest();
                break;
            case 'PUT':
                $response = $this->updateImagesFromRequest($this->key);
                break;
            case 'DELETE':
                $response = $this->deleteImages($this->key);
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

    private function getKeyedImages($key)
    {
        
    }
    private function addImagesFromRequest() {
        
    }
    private function addImagesFromGet($input) {
        
    }
    private function updateImagesFromRequest($id) {
        
    }
    private function deleteImages($key) {
        $result = $this->getPostImages($key);
        if(!$result){
            return $this->notFoundResponse();
        }
        $this->deleteImages($key);
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

 