<?php

namespace ViewController\viewAlbum;

use PDO;

/**
 * get album shortUrl and display Album
 */
class viewAlbum{
    protected PDO $db;
    protected $short_url;
    public function __construct(String $short_url, PDO $db) {
        $this->short_url = $short_url;
        $this->db = $db;
    }


    
}