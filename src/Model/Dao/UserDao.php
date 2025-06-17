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
	$stmt->execute([$user->getEmail(), password_hash($user->getPass(), PASSWORD_BCRYPT), 'active', 3]);
    }

    public function updatePass(User $user){
	$pdo = self::getPdoConnection();
	
	$sql = 'UPDATE dd_users SET password = ? WHERE id = ?';
	$stmt = $pdo->prepare($sql);
	$stmt->execute([password_hash($user->getPass(), PASSWORD_BCRYPT), $user->getId()]);
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
	    $user->setRole($row['role_id']);
	    $user->setId($row['id']);
	    $user->setStatus($row['status']);
            return $user;
	}

	return null;
    }

        public function findById($id) {
	$pdo = self::getPdoConnection();
	$sql = "SELECT * FROM dd_users WHERE id = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$id]);
	$row = $stmt->fetch();

	if ($row) {
            $user = new User();
            $user->setEmail($row['email']);
	    $user->setPass($row['password']);
	    $user->setRole($row['role_id']);
	    $user->setId($row['id']);
            return $user;
	}

	return null;
	}
    
    public function getUserIdByEmail($email){
	$pdo = self::getPdoConnection();
	$sql = "SELECT id FROM dd_users WHERE email = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$email]);
	$user_id = $stmt->fetchColumn();

	if ($user_id) {
	    return $user_id;
	}

	return null;
    }












}