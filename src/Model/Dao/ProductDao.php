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
WHERE p.id = ? AND ps.product_id = ?;
";
	$stmt = $pdo->prepare($sql);
	$stmt->execute([$product_id, $product_id]);
	$rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

	return $rows;
    }
}