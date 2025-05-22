<?php

namespace Model\Dao;
require_once ROOT_PATH . '/src/Config/config.php';

abstract class AbstractDao {

    private static $pdo;

    private function __construct()    {    }

    public static function getPdoConnection() {
	if(self::$pdo === null){
            try {
		self::$pdo = new \PDO("mysql:host=" . DB_HOST . ":" . DB_PORT . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
		self::$pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );

            } catch (\PDOException $e){
		echo "Problem with db query  - " . $e->getMessage();
            }
	}

	return self::$pdo;
    }
}