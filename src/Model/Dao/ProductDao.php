<?php

namespace Model\Dao;

use Model\Dao\AbstractDao;
use Model\Product;

class ProductDao extends AbstractDao{

    public function __construct(){
	//so that it doesn't inherit private constructor
    }

    public function getAllPublishedProducts($category){
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

    public function getAllProducts(){
	$pdo = self::getPdoConnection();
	$sql = "SELECT * FROM dd_products";
	$stmt = $pdo->prepare($sql);
	$stmt->execute();
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
	$stmt->execute([$prod_id, $size]);
	$amount = $stmt->fetchColumn();
	if($amount == NULL) $amount = 0;
	return $amount;
    }

    public function setAmountByProductIdAndSizeId($prod_id, $size_id, $amount) {
	$pdo = self::getPdoConnection();

	$sql = "
INSERT INTO dd_product_sizes (product_id, size_id, amount)
VALUES (?, ?, ?)
ON DUPLICATE KEY UPDATE amount = VALUES(amount)
    ";

	$stmt = $pdo->prepare($sql);
	return $stmt->execute([$prod_id, $size_id, $amount]);
    }

    public function getTotalAmountByProductId($prod_id){
	$pdo = self::getPdoConnection();

	$sql = "SELECT SUM(amount) FROM dd_product_sizes WHERE product_id = ?;";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$prod_id]);
	$amount = $stmt->fetchColumn();
	if($amount == NULL) $amount = 0;
	return $amount;
    }

    public function getAmountOfSizesByProductId($prod_id){
	$pdo = self::getPdoConnection();

	$sql = "SELECT COUNT(*) FROM dd_product_sizes WHERE product_id = ?;";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$prod_id]);
	$amount = $stmt->fetchColumn();
	if($amount == NULL) $amount = 0;
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

    public function getAllSizesByCategory($category){
	$pdo = self::getPdoConnection();

	$sql = "
SELECT s.name
FROM categories AS c
JOIN sd_category_sizes AS cs
ON c.id = cs.category_id
JOIN sd_sizes AS s
ON cs.size_id = s.id
WHERE c.name=?;
";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$category]);
	$rows = $stmt->fetchAll(\PDO::FETCH_COLUMN);
	return $rows;
    }

    public function updateProduct($product) {
	$pdo = self::getPdoConnection();
	
        $sql = "
UPDATE dd_products 
SET name = ?, price = ?, description = ?, 
image_path = ?, status = ?, category_id = ?
WHERE id = ?";
        $stmt = $pdo->prepare($sql);
     
        return $stmt->execute([$product->getName(), $product->getPrice(), $product->getDescription(), $product->getImgPath(), $product->getStatus(), $product->getCategoryId(), $product->getId()]);
    }

    public function createProduct($product) {
	$pdo = self::getPdoConnection();

	$sql = "
INSERT INTO dd_products 
(name, price, description, image_path, date_added, status, category_id)
VALUES
(?, ?, ?, ?, NOW(), ?, ?)
    ";

	$stmt = $pdo->prepare($sql);

	$success = $stmt->execute([
            $product->getName(),
            $product->getPrice(),
            $product->getDescription(),
            $product->getImgPath(),
	    'draft',
            $product->getCategoryId()
	]);

	if ($success) {
            // Set the newly generated ID back on the product entity
            $product->setId($pdo->lastInsertId());
	}

	return $success;
    }

    public function getProductStatusByProductId($product_id){
	$pdo = self::getPdoConnection();
	
	$sql = "SELECT status FROM dd_products WHERE id = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$product_id]);
	$status = $stmt->fetchColumn();

	return $status;
    }

    public function updateStatus($status, $product_id){
	$pdo = self::getPdoConnection();
	
	$sql = "UPDATE dd_products SET status = ? WHERE id = ?";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$status, $product_id]);
    }
}