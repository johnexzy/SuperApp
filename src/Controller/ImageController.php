<?php
namespace Src\Controller;

use PDO;
use Src\Logic\MakeFile;
use Src\TableGateways\ImageGateway;

/**
 * Handles Incoming Image Request
 */

 class ImageController extends ImageGateway
 {
     protected $key, $requestMethod, $input;
     public function __construct(PDO $db, String $key, Array $input = null, $requestMethod) {
         parent::__construct($db);
         $this->key = $key;
         $this->input = $input;
         $this->requestMethod = $requestMethod;
     }
     /**
      * Proccess All request for Images
      */
     public function processRequest()
     {
        switch ($this->requestMethod) {
            case 'GET':
                $response = $this->getKeyedImages($this->key);
                break;
            case 'POST':
                $response = $this->addImagesFromRequest($this->input, $this->key);
                break;
            case 'PUT':
                $response = $this->updateImagesFromRequest($this->key);
                break;
            case 'DELETE':
                $response = $this->deleteImagesFromRequest($this->key);
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
        $result = $this->getPostImages($key);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = \json_encode($result);
        return $response;
    }
    private function addImagesFromRequest($input, $key) {
        $images = MakeFile::reArrayFiles($input);
        $result = $this->createImage($images, $key);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = \json_encode($result);
        return $response;
    }
    private function addImagesFromGet($input) {
        
    }
    private function updateImagesFromRequest($id) {
        
    }
    private function deleteImagesFromRequest($key) {
        $result = $this->getPostImages($key);
        if(!$result){
            return $this->notFoundResponse();
        }
        $result = $this->deleteImages($key);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = \json_encode($result);
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

 