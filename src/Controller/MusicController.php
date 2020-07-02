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
use Src\TableGateways\MusicGateway;

class MusicController extends MusicGateway{
    
    private $db, $requestMethod, $input, $limit, $popular, $pageNo, $short_url, $id;

    public function __construct($db, $requestMethod, Array $input = null, int $id = null, int $limit = null, int $popular = null, int $pageNo = null, String $short_url = null) {
        parent::__construct($db);
        $this->db = $db;
        $this->requestMethod = $requestMethod;
        $this->input = $input;
        $this->limit = $limit;
        $this->popular = $popular;
        $this->pageNo = $pageNo;
        $this->short_url = $short_url;
        $this->id = $id;
    }

    public function processRequest()
    {
        switch ($this->requestMethod) {
            
            case 'POST':
                $response = $this->addNewSongFromRequest();
                break;
            case 'GET' :
                if ($this->short_url !== null) {
                    $response = $this->getSongByUrl($this->short_url);
                }
                elseif ($this->popular) {
                    $response = $this->getSongByPopular($this->popular);
                }
                elseif ($this->pageNo) {
                    $response = $this->getSongByPage($this->pageNo);
                }
                else {
                    $response = $this->getAllSongs($this->limit);
                };
                break;
            case 'DELETE':
                if ($this->id) {
                    $response = $this->deleteMusic($this->id);
                }
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
        $result = $this->insert($this->input);
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
       $response['body'] = \json_encode($result);
        return $response;
    }
    private function getSongByUrl($short_url)
    {   $result = $this->findByUrl($short_url);
        if (!$result) {
            return $this->notFoundResponse();
        }
        $result = $this->getByUrl($short_url);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    private function getAllSongs($limit)
    {
        $result = $this->getAll($limit);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    private function getSongByPage($pageNo)
    {
        $result = $this->getPages($pageNo);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    private function getSongByPopular($popularInt)
    {
        $result = $this->getPopular($popularInt);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    private function updateMusicFromRequest($id)
    {
        $result = $this->find($id);
        if (!$result) {
            return $this->notFoundResponse();
            # code...
        }
        if (!$this->validateUpdateInput($this->input)) {
            return $this->unprocessableEntityResponse();
        }
        
        
    }
    private function deleteMusic($id) {
        $result = $this->find($id);
        if(!$result){
            return $this->notFoundResponse();
        }
        $result = $this->delete($id);
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = json_encode($result);
        return $response;
    }
    public static function reArrayFiles($file_post) {

        $file_arr = array();
        if(is_countable($file_post['name'])){
            $file_count = count($file_post['name']);
            $file_keys = array_keys($file_post);
            
            for ($i=0; $i<$file_count; $i++) {
                foreach ($file_keys as $key) {
                    $file_arr[$i][$key] = $file_post[$key][$i];
                    // $file_arr[$key] = $file_post[$key];
                }
            }
        }
        else{
            $file_keys = array_keys($file_post);
    
        // for ($i=0; $i<$file_count; $i++) {
            foreach ($file_keys as $key) {
                // $file_arr[$i][$key] = $file_post[$key][$i];
                $file_arr[$key] = $file_post[$key];

            }
        }
        
    
        return $file_arr;
        
        /**
         * @credit to : https://www.php.net/manual/en/features.file-upload.multiple.php#53240
         */
    }
    private function validateUpdateInput(Array $input) {
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
