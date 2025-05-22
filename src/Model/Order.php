<?php

namespace Model;

class Order{

    private $user_id;
    private $cart_items = []; // [$key (id|size) => $amount]

    public function __construct($cart_items, $user_id = null){
	$this->cart_items = $cart_items;
	$this->user_id = $user_id;
    }

    public function getUserId(){
	return $this->user_id;
    }

    public function getCartItems(){
	return $this->cart_items;
    }
    
}