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

namespace Src\TableGateWays;

/**
 * Description of ImageGateway
 *
 * @author hp
 */
class ImageGateway {
    //put your code here
    private  $db = null;
    public function __construct($db) {
        
        $this->db = $db;
    }
    public function getPostImages(String $postKey)
        {
                $statement = "
                        SELECT
                                image_url
                        FROM
                                images
                        WHERE image_key = ?;
                ";
                try {
                        $statement = $this->db->prepare($statement);
                        $statement->execute(array($postKey));
                        $result = $statement->fetchAll(\PDO::FETCH_COLUMN);
                        return $result;
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }
        
}
