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

    public function getAlbum()
    {
        $res = $this->getByUrl($this->short_url);
    }


}