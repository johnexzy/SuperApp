<?php

namespace Src\Controller\ViewController\ViewSeries;

use PDO;
use Src\Layout\LayoutClass;
use Src\TableGateways\EpisodeGateway;

/**
 * get episode url and display details about an episode
 */
class ViewEpisode extends EpisodeGateway
{
	protected $db;
	protected $_short_url;
	protected $_series_name;
	public function __construct($short_url, $_series_name, PDO $db)
	{
		parent::__construct($db);
	    $this->short_url = $short_url;
	    $this->db        = $db;
	    $this->series_name = $_series_name;
	}

	public function getEpisode($short_url, $series_name)
	{
		// $res = $this->findByUrl($short_url, $series_name);
	 //    if (!$res) {
	 //        return null;
	 //    }
	    $res = $this->getByUrl($short_url, $series_name);
        return $res;
    }
	
	public function bodyParser()
	{
		$response = $this->getEpisode($this->short_url, $this->series_name);
		// if (!$response) {
		//    return 
		//    <<<HTML
  //               <h1>Page not found. 404</h1>
  //           HTML;
  // 		}
		return $response; 
	}
}