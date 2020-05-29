<?php
namespace Src\Controller\ViewController;


use Src\Layout\Details;
use Src\TableGateways\CarouselGateway;
use Src\TableGateways\NewsGateway;
class ViewController
{
    private $short_url = null;
    private $id = null;
    private $root = null;
    private $group = null;
    private $db = null;
    public function __construct($db, $short_url, $id, $root, $group)
    {
        $this->short_url = $short_url;
        $this->id = $id;
        $this->root = $root;
        $this->group = $group;
        $this->db = $db;
    }

    public function makeRequest()
    {
        // $res = file_get_contents("http://127.0.0.1:8080/api/$this->group/$this->short_url", true, null);
        // $res = (array) json_decode($res, true);
        if ($this->group == 'carousel') {
            $carousel = new CarouselGateway($this->db);
            $res = $carousel->findByUrl($this->short_url);
        }
        else{
            $news = new NewsGateway($this->db);
            $res = $news->findByUrl($this->short_url);
        }
        return $res;
    }
    public function showView() {
        $detail = new Details($this->makeRequest(), $this->root, $this->db);
        return $detail->proccessView($this->group);
        
    }
}

