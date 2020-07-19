<?php
namespace Src\Controller;

use Src\TableGateways\SeriesGateway;
class SeriesController extends SeriesGateway  
{
    private $requestMethod, $input, $limit, $popular, $pageNo, $short_url, $id;

    public function __construct($db, $requestMethod,  $input = null, $id = null, $lim = null, $pn=null, $short_url =null) {
        parent::__construct($db);
        $this->requestMethod = $requestMethod;
        $this->input = $input;
        $this->short_url = $short_url;
        $this->limit = $lim;
        $this->pageNo = $pn;
        $this->id = $id;
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            case 'GET':
                if (isset($this->short_url)) {
                    $response = $this->getSeries($this->short_url);
                }
                elseif (isset($this->pageNo)) {
                    $response = $this->getSeriesByPage($this->pageNo);
                }
                else {
                    $response = $this->getAllSeries($this->limit);
                }
                break;
            case 'POST':
                $response = ($this->input) ? $this->createSeriesFromRequest($this->input) 
                : $this->notFoundResponse();
                break;
            case 'PUT':
                $response = $this->updateSeriesFromRequest($this->id);
                break;
            case 'DELETE':
                $response = $this->deleteSeries($this->id);
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

    private function getAllSeries($limit)
    {
        $result = $this->getAll($limit);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    private function getSeries(String $short_url) {
        $result = $this->findByUrl($short_url);
        if(!$result){
            return $this->notFoundResponse();
        }
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($this->getByUrl($short_url));
        return $response;
    }
    private function getSeriesByPage($pageNo)
    {
        $result = $this->getPages($pageNo);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    private function createSeriesFromRequest($input) {
        if(!$this->validateInput($input)){
            return $this->unprocessableEntityResponse();
        }
        $res = $this->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = $res;
        return $response;
    }
    private function updateSeriesFromRequest($id) {
        $result = $this->find($id);
        if(!$result){
            return $this->notFoundResponse();
        }
        $input = (array) json_decode(file_get_contents('php://input'), TRUE);
        if(!$this->validateInput($input)){
            return $this->unprocessableEntityResponse();
        }
        $this->update($id, $input);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = null;
        return $response;
    }
    private function deleteSeries($id) {
        $result = $this->find($id);
        if(!$result){
            return $this->notFoundResponse();
        }
        $this->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = NULL;
        return $response;
    }
    private function validateInput($input) {
        if (! isset($input['series_name'])) {
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
