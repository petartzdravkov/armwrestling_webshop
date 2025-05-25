<?php

namespace Model\Dao;

use Model\Dao\AbstractDao;
use Model\Product;

class ProductDao extends AbstractDao{

    public function __construct(){
	//so that it doesn't inherit private constructor
    }

    public function getAllProducts($category){
	$pdo = self::getPdoConnection();
	$category_id = null;
	
	if($category === 'clothing'){
	    $category_id = 1;
	}else{
	    $category_id = 2;
	}
	$sql = "SELECT * FROM dd_products WHERE category_id = ? AND status = 'published'";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$category_id]);
	$rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

	$products = [];
	if($rows){
	    foreach($rows as $row){
		$product = new Product();
		$product->setId($row['id']);
		$product->setName($row['name']);
		$product->setPrice($row['price']);
		$product->setDescription($row['description']);
		$product->setImgPath($row['image_path'] ?? null);
		$product->setDateAdded($row['date_added']);
		$product->setStatus($row['status']);
		$product->setCategoryId($row['category_id']);
		$products[] = $product;
	    }
	}
	return $products;
    }

    public function getProductByName($pname){
	$pdo = self::getPdoConnection();
	$sql = "SELECT * FROM dd_products WHERE name = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$pname]);
	$row = $stmt->fetch(\PDO::FETCH_ASSOC);

	$product = null;
	if($row){
	    $product = new Product();
	    $product->setId($row['id']);
	    $product->setName($row['name']);
	    $product->setPrice($row['price']);
	    $product->setDescription($row['description']);
	    $product->setImgPath($row['image_path'] ?? null);
	    $product->setDateAdded($row['date_added']);
	    $product->setStatus($row['status']);
	    $product->setCategoryId($row['category_id']);
	}
	return $product;

    }

        public function getProductById($id){
	$pdo = self::getPdoConnection();
	$sql = "SELECT * FROM dd_products WHERE id = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$id]);
	$row = $stmt->fetch(\PDO::FETCH_ASSOC);

	$product = null;
	if($row){
	    $product = new Product();
	    $product->setId($row['id']);
	    $product->setName($row['name']);
	    $product->setPrice($row['price']);
	    $product->setDescription($row['description']);
	    $product->setImgPath($row['image_path'] ?? null);
	    $product->setDateAdded($row['date_added']);
	    $product->setStatus($row['status']);
	    $product->setCategoryId($row['category_id']);
	}
	return $product;

    }

    public function getAllSizesByProductId($product_id){
	$pdo = self::getPdoConnection();

	$sql = "
SELECT s.name, ps.amount
FROM dd_products as p
JOIN sd_category_sizes as cs
ON p.category_id = cs.category_id
RIGHT JOIN dd_product_sizes as ps
ON cs.size_id = ps.size_id
JOIN sd_sizes as s
ON cs.size_id = s.id
WHERE p.id = ? AND ps.product_id = ? AND ps.amount > 0;
";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$product_id, $product_id]);
	$rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

	return $rows;
    }

    public function getSizeId($size){
	$pdo = self::getPdoConnection();

	$sql = "SELECT id FROM sd_sizes WHERE name = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$size]);
	$size_id = $stmt->fetchColumn();

	return $size_id;	
    }

    public function getAmountByIdAndSize($prod_id, $size){
	$pdo = self::getPdoConnection();

	$size_id = $this->getSizeId($size);
	$sql = "SELECT amount FROM dd_product_sizes WHERE product_id = ? AND size_id = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$prod_id, $size_id]);
	$amount = $stmt->fetchColumn();
	
	return $amount;
    }

    public function decreaseStock($id, $size, $bought_amount){
	$pdo = self::getPdoConnection();

	$size_id = $this->getSizeId($size);
	$name = $this->getProdNameById($id);
	$sql = "
UPDATE dd_product_sizes
SET amount = amount - ?
WHERE product_id = ? AND size_id = ?
";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$bought_amount, $id, $size_id]);

	if ($stmt->rowCount() === 0) {
	    throw new \Exception("Not enough stock or item not found. Item: $name, size: $size.");
	}
    }

    public function getProdNameById($prod_id){
	$pdo = self::getPdoConnection();

	$sql = "SELECT name FROM dd_products WHERE id = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$prod_id]);
	$name = $stmt->fetchColumn();
	
	return $name;
    }

    public function getSizeNameBySizeId($size_id){
	$pdo = self::getPdoConnection();

	$sql = "SELECT name FROM sd_sizes WHERE id = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$size_id]);
	$name = $stmt->fetchColumn();
	
	return $name;
    }
}