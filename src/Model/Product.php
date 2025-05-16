<?php

namespace Model;

class Product{

    private $name;
    private $price;
    private $description;
    private $img_path;
    private $date_added;
    private $status;
    private $category_id;

    // Name
    public function getName() {
        return $this->name;
    }
    public function setName($name) {
        if (empty($name) || strlen($name) > 50) {
            throw new \InvalidArgumentException("Product name must be a non-empty string with a maximum of 255 characters.");
        }
        $this->name = $name;
    }

    // Price
    public function getPrice() {
        return $this->price;
    }
    public function setPrice($price) {
        if (!is_numeric($price) || $price < 0) {
            throw new \InvalidArgumentException("Price must be a non-negative number.");
        }
        $this->price = $price;
    }

    // Description
    public function getDescription() {
        return $this->description;
    }
    public function setDescription($description) {
        if (!is_string($description)) {
            throw new \InvalidArgumentException("Description must be a string.");
        }
        $this->description = $description;
    }

    // Image Path
    public function getImgPath() {
        return $this->img_path;
    }
    public function setImgPath($img_path) {
        if (!empty($img_path) && strlen($img_path) > 255) {
            throw new \InvalidArgumentException("Image path must be a valid string with a maximum of 255 characters.");
        }
        $this->img_path = $img_path;
    }

    // Date Added
    public function getDateAdded() {
        return $this->date_added;
    }
    public function setDateAdded($date_added) {
        if (!strtotime($date_added)) {
            throw new \InvalidArgumentException("Date added must be a valid date string.");
        }
        $this->date_added = $date_added;
    }

    // Status
    public function getStatus() {
        return $this->status;
    }
    public function setStatus($status) {
        $validStatuses = ['draft','published','deleted'];
        if (!in_array($status, $validStatuses)) {
            throw new \InvalidArgumentException("Invalid status value.");
        }
        $this->status = $status;
    }

    // Category ID
    public function getCategoryId() {
        return $this->category_id;
    }
    public function setCategoryId($category_id) {
        if (!is_numeric($category_id) || $category_id <= 0) {
            throw new \InvalidArgumentException("Category ID must be a positive number.");
        }
        $this->category_id = $category_id;
    }
    
}