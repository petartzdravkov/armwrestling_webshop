<?php

namespace Model;

class Order{

    private $user_id;
    private $cart_items = []; // [$key (id|size) => $amount]
    private $order_id;
    private $datetime;
    private $status; 

    public function __construct($cart_items = null, $user_id = null){
	$this->cart_items = $cart_items;
	$this->user_id = $user_id;
    }

    public function getUserId(){
	return $this->user_id;
    }

    public function getCartItems(){
	return $this->cart_items;
    }

    public function getOrderId() {
	return $this->order_id;
    }

    public function setOrderId($order_id) {
	$this->order_id = $order_id;
    }

    public function getDatetime() {
	return $this->datetime;
    }

    public function setDatetime($datetime) {
	$this->datetime = $datetime;
    }

    public function getStatus() {
	return $this->status;
    }

    public function setStatus($status) {
	$this->status = $status;
    }
}