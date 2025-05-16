<?php

namespace Model\Dao;

abstract class AbstractDao {

    const DB_NAME = "armwrestling_webshop";
    const DB_IP = "localhost";
    const DB_PORT = "3306";
    const DB_USER = "aw_shop";
    const DB_PASS = "123456";

    //TODO convert to singleton
    /* @var $pdo \PDO */
    private static $pdo;

    private function __construct()    {    }

    public static function getPdoConnection() {
	if(self::$pdo === null){
            try {
		self::$pdo = new \PDO("mysql:host=" . self::DB_IP . ":" . self::DB_PORT . ";dbname=" . self::DB_NAME, self::DB_USER, self::DB_PASS);
		self::$pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );

            } catch (\PDOException $e){
		echo "Problem with db query  - " . $e->getMessage();
            }
	}

	return self::$pdo;
    }
}