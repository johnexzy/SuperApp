<?php

namespace Src\Controller;

use Src\TableGateways\CarouselGateway;

class CarouselController  
{
    private $db;
    private $requestMethod;
    private $id;
    private $short_url;
    private $carouselGateway;

    public function __construct($db, $requestMethod, $short_url, $id) {
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->short_url = $short_url;
        $this->id = $id;

        $this->carouselGateway = new CarouselGateway($db);
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if($this->id){
                    $response = $this->getCarouselById($this->id);
                }
                elseif($this->short_url){
                    $response = $this->getCarouselByUrl($this->short_url);
                }
                else{
                    $response = $this->getAllCarousel();
                }
                break;
            case 'POST':
                $response = $this->addCarouselFromRequest();
                break;
            case 'PUT':
                $response = $this->updateCarouselFromRequest($this->id);
                break;
            case 'DELETE':
                $response = $this->deleteCarousel($this->id);
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

    private function getCarouselById($id)
    {
        $result = $this->carouselGateway->find($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    private function getCarouselByUrl($Url)
    {
        $result = $this->carouselGateway->findByUrl($Url);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    private function getAllCarousel()
    {
        $result = $this->carouselGateway->getAll();
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    private function addCarouselFromRequest() {
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if(!$this->validateInput($input)){
            return $this->unprocessableEntityResponse();
        }
        $result = $this->carouselGateway->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
       $response['body'] = json_encode($result);
        return $response;
    }
    private function updateCarouselFromRequest($id) {
        $result = $this->carouselGateway->find($id);
        if(!$result){
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if(!$this->validateUpdateInput($input)){
            return $this->unprocessableEntityResponse();
        }
        $this->carouselGateway->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }
    private function deleteCarousel($id) {
        $result = $this->carouselGateway->find($id);
        if(!$result){
            return $this->notFoundResponse();
        }
        $this->carouselGateway->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = NULL;
        return $response;
    }
    private function validateInput($input) {
        if ((!isset($input['carousel_body'])) || (! isset($input['carousel_title'])) || 
                (! isset($input['carousel_image'])) || 
                (! isset($input['author']))
           ) {
            return false;
        }
        return true;
    }
    private function validateUpdateInput($input) {
        if (! isset($input['carousel_body'])) {
            return false;
        }
        if (! isset($input['carousel_title'])) {
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
