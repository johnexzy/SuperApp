<?php
namespace Src\Controller;

use Src\TableGateways\EpisodeGateway;

class EpisodeController extends EpisodeGateway  
{
    private $requestMethod, $input, $short_url, $series_name, $id;

    public function __construct($db, $requestMethod,  $input = null, $id, $series_name = null, $short_url =null) {
        parent::__construct($db);
        $this->requestMethod = $requestMethod;
        $this->input = $input;
        $this->short_url = $short_url;
        $this->id = $id;
        $this->series_name = $series_name;
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if (isset($this->short_url)) {
                    $response = $this->getEpisode($this->short_url, $this->series_name);
                }
                else {
                    $response = $this->notFoundResponse();
                }
                break;
            case 'POST':
                $response = ($this->input) ? $this->createEpisodeFromRequest($this->input) 
                : $this->notFoundResponse();
                break;
            case 'PUT':
                // $response = $this->updateEpisodeFromRequest($this->id);
                break;
            case 'DELETE':
                // $response = $this->deleteEpisode($this->id);
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

    private function getEpisode(String $short_url, String $series_name) {
        $result = $this->getByUrl($short_url, $series_name);
        if(!$result){
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    private function createEpisodeFromRequest($input) {
        if(!$this->validateInput($input)){
            return $this->unprocessableEntityResponse();
        }
        $res = $this->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = $res;
        return $response;
    }
    // private function updateEpisodeFromRequest($id) {
    //     $result = $this->find($id);
    //     if(!$result){
    //         return $this->notFoundResponse();
    //     }
    //     $input = (array) json_decode(file_get_contents('php://input'), TRUE);
    //     if(!$this->validateInput($input)){
    //         return $this->unprocessableEntityResponse();
    //     }
    //     $this->update($id, $input);
    //     $response['status_code_header'] = 'HTTP/1.1 200 OK';
    //     $response['body'] = null;
    //     return $response;
    // }
    // private function deleteEpisode($id) {
    //     $result = $this->find($id);
    //     if(!$result){
    //         return $this->notFoundResponse();
    //     }
    //     $this->delete($id);
    //     $response['status_code_header'] = 'HTTP/1.1 200 OK';
    //     $response['body'] = NULL;
    //     return $response;
    // }
    private function validateInput($input) {
        if (!isset($input['season_key'])) {
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
