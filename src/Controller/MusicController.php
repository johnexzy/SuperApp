<?php

/*
 * Copyright 2020 hp.
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *      http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Src\Controller;

/**
 * Description of MusicController
 *
 * @author Oba John
 */
use Src\TableGateWays\MusicGateWay;

class MusicController extends MusicGateWay{
    
    private $db;
    private $requestMethod;

    public function __construct($db, $requestMethod) {
        parent::__construct($db);
        $this->db = $db;
        $this->requestMethod = $requestMethod;
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            
            case 'POST':
                $response = $this->addNewSongFromRequest();
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

    
    private function addNewSongFromRequest() {
        $input = (array) $_POST;
        $input += ["song" => $_FILES['music_file']];
        
        $result = $this->insert($input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
       $response['body'] = json_encode($result);
        return $response;
    }
    private function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = null;
        return $response;
    }
    }
