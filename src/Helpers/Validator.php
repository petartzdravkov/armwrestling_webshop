<?php

namespace Helpers;
use \Exception;
use Model\Dao\ProductDao;

class Validator
{
    // Validate and sanitize email
    public static function validateEmail($email)
    {
        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception("Invalid email format.");
        }
        return htmlentities($email);
    }

    // Validate password with minimum requirements
    public static function validatePassword($password)
    {
        if (
            strlen($password) < 8 ||
            !preg_match('/[A-Z]/i', $password) ||
            !preg_match('/[0-9]/', $password) ||
            !preg_match('/[\W]/', $password)
        ) {
            throw new Exception("Password must be at least 8 characters long and include a letter, a number, and a special character.");
        }
        return $password;
    }

    // Validate first and last name (letters, spaces, apostrophes, hyphens)
    public static function validateName($name, $label = "Name")
    {
        $name = trim($name);
        if (!preg_match('/^[\p{L} \'-]+$/u', $name)) {
            throw new Exception("$label contains invalid characters.");
        }
        return htmlentities($name);
    }

    // Generic input string validation
    public static function sanitizeString($string, $label = "Input")
    {
        $string = trim($string);
        if ($string === '') {
            throw new Exception("$label cannot be empty.");
        }
        return htmlentities($string);
    }

    // Validate integer
    public static function validateInt($number, $min = 0, $max = 999, $label = "Integer")
    {
        if (!filter_var($number, FILTER_VALIDATE_INT)) {
            throw new Exception("$label must be a valid integer.");
        }elseif($number < $min || $number > $max){
	    throw new Exception("$label must be between $min and $max.");
	}
        return htmlentities($number);
    }

    // Validate float
    public static function validateFloat($number, $min = 0, $max = 999, $label = "Float")
    {
        if (!filter_var($number, FILTER_VALIDATE_FLOAT)) {
            throw new Exception("$label must be a valid float.");
        }elseif($number < $min || $number > $max){
	    throw new Exception("$label must be between $min and $max.");
	}
        return htmlentities($number);
    }

    public static function validateEnum($value, array $allowedValues, $label = "Value")
    {
	if (!in_array($value, $allowedValues, true)) {
            throw new Exception("$label must be one of: " . implode(', ', $allowedValues));
	}
	return htmlentities($value);
    }

    public static function validateDescription($value, $label = "Description", $maxLength = 1000)
    {
	if (!is_string($value)) {
            throw new Exception("$label must be a string.");
	}

	$trimmed = trim($value);

	if (strlen($trimmed) === 0) {
            throw new Exception("$label cannot be empty.");
	}

	if (strlen($trimmed) > $maxLength) {
            throw new Exception("$label cannot exceed $maxLength characters.");
	}

	return htmlentities($trimmed);
    }

    public static function validateProductId($productId, $productDao, $product_name) {
	try{
	    $productId = self::validateInt($productId, 0, 99999, "Product Id");
	}
	catch(\Exception $e){
	    throw new Exception("Product ID not valid.");   
	}
	try{
	    $product = $productDao->getProductById($productId);
	}catch(\Exception $e){
	    throw new Exception("error getting product");
	}
	if (!$product) {
            throw new Exception("Product ID does not exist.");
	}elseif($product->getName() !== $product_name){
	    throw new Exception("Tampering with data, pls don't.");
	}

	return $productId;
    }
}
