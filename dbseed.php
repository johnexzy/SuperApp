<?php
require 'bootstrap.php';

$statement = <<<EOS
    CREATE TABLE IF NOT EXISTS person (
        id INT NOT NULL AUTO_INCREMENT,
        firstname VARCHAR(100) NOT NULL,
        lastname VARCHAR(100) NOT NULL,
        firstparent_id INT DEFAULT NULL,
        secondparent_id INT DEFAULT NULL,
        PRIMARY KEY (id),
        FOREIGN KEY (firstparent_id)
            REFERENCES person(id)
            ON DELETE SET NULL,
        FOREIGN KEY (secondparent_id)
            REFERENCES person(id)
            ON DELETE SET NULL
    ) ENGINE=INNODB;

    
    CREATE TABLE IF NOT EXISTS album (
        id INT NOT NULL AUTO_INCREMENT,
        year INT NOT NULL,
        album_name TEXT NOT NULL,
        album_details TEXT NOT NULL,
        artist TEXT NOT NULL,
        album_key TEXT NOT NULL,
        short_url TEXT NOT NULL,
        uploaded_by TEXT NOT NULL,
        created_at TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
        updated_at TIMESTAMP(0) NOT NULL DEFAULT CURRENT_TIMESTAMP(0),
        
        
        PRIMARY KEY (id)
    ) ENGINE=INNODB;
EOS;

try {
    $createTable = $dbConnection->exec($statement);
    echo "\nSuccess!\n";
} catch (\PDOException $e) {
    exit($e->getMessage());
}