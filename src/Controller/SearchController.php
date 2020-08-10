<?php
namespace Src\Controller;

use PDO;
use Src\TableGateways\SeasonGateway;

class SearchController extends SeasonGateway
{
    private $query;
    public function __construct(PDO $db, String $query) {
        parent::__construct($db);
        $this->query = $query;
    }
}