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
         # code...
     }
 }
 