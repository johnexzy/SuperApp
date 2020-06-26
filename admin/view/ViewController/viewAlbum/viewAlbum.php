<?php

namespace ViewController\viewAlbum;

use PDO;
use Src\TableGateways\AlbumGateway;

/**
 * get album shortUrl and display Album
 */
class viewAlbum extends AlbumGateway{
    protected PDO $db;
    protected $short_url;
    public function __construct(String $short_url, PDO $db) {
        parent::__construct($db);
        $this->short_url = $short_url;
        $this->db = $db;
    }

    public function getAlbum($short_url)
    {
        $res = $this->findByUrl($short_url);
        $res = $this->getByUrl($short_url);
        return $res;
    }

    public function bodyParser()
    {
        
    }

}