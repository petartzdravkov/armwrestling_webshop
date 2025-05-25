<?php

namespace Model;

class User{

    private $email;
    private $pass;
    private $role;
    private $id;

    public function setEmail($email){
	//TODO validate
	$this->email = $email;
    }

    public function getEmail(){
	return $this->email;
    }    

    public function setPass($pass){
	//TODO validate
	$this->pass = $pass;
    }

    public function getPass(){
	return $this->pass;
    }

    public function setRole($role){
	//TODO validate
	$this->role = $role;
    }

    public function getRole(){
	return $this->role;
    }

    public function setId($id){
	//TODO validate
	$this->id = $id;
    }

    public function getId(){
	return $this->id;
    }
    
}