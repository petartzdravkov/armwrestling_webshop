<?php

namespace Model\Dao;

use Model\Dao\AbstractDao;
use Model\User;

class UserDao extends AbstractDao{

    public function __construct(){
	//so that it doesn't inherit private constructor
    }

    public function create(User $user){
	$pdo = self::getPdoConnection();
	
	$sql = 'INSERT INTO dd_users (email, password, status, role_id) VALUES (?, ?, ?, ?)';
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$user->getEmail(), password_hash($user->getPass(), PASSWORD_BCRYPT), 'active', 2]);
    }
    
    public function findByEmail($email) {
	$pdo = self::getPdoConnection();
	$sql = "SELECT * FROM dd_users WHERE email = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$email]);
	$row = $stmt->fetch();

	if ($row) {
            $user = new User();
            $user->setEmail($row['email']);
	    $user->setPass($row['password']);
	    $user->setRole($row['role']);
            return $user;
	}

	return null;
    }












}