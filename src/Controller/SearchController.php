<?php
namespace Src\Controller;

use PDO;
use Src\TableGateways\SearchGateway;
class SearchController extends SearchGateway
{
    private $query;
    public function __construct(PDO $db, String $query) {
        parent::__construct($db);
        $this->query = $query;
    }

    public function proccesRequest()
    {
        $response = $this->getSearch($this->query);
        header($response['status_code_header']);
        if ($response['body']) {
            echo $response['body'];
        }
    }
    
    private function getSearch($query)
    {
        $result = $this->handleSearchQuery($query);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
}