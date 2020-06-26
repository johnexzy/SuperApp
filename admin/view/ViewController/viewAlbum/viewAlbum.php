<?php

// namespace ViewController\;
namespace View;

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
        if (!$res) {
            return null;
        }
        $res = $this->getByUrl($short_url);
        return $res;
    }

    public function bodyParser()
    {
        $response = $this->getAlbum($this->short_url);

        if (!$response) {
            return <<<HTML
                <h1>Page not found. 404</h1>
            HTML;
        }

    }

}