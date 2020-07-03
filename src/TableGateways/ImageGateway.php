<?php



namespace Src\TableGateways;

/**
 * Description of ImageGateway
 *
 * @author hp
 */
use Src\Logic\MakeFile;
/**
 * Image Processor
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
        public function createImage(Array $images, string $key) {
                if(!is_countable($images) || count($images) < 1)  return null;
                
                $statement = "
                        INSERT INTO images
                                (image_url, image_key)
                        VALUES
                                (:image_url, :image_key)     
                ";
                try {
                        
                        $query = $this->db->prepare($statement);
                        foreach ($images as $image) {
                                $query->execute(array(
                                        'image_url' => MakeFile::makeImg($image),
                                        'image_key' => $key
                                ));
                        }
                        
                        return $query->rowCount();
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
                
        }
        public function deleteImages($key)
        {
                
                $statement = "
                        DELETE FROM images
                        WHERE image_key = ?;
                ";

                try {
                        $statement=$this->db->prepare($statement);
                        $statement->execute(array($key));
                        return $statement->rowCount();
                } catch (\PDOException $e) {
                        exit($e->getMessage());
                }
        }

}
