<?php

?>

<?php include_once '../src/View/layouts/header.php';?>

<main>
<?php
echo "Name: " . $product->getName() . "<br>";
echo "Description: " . $product->getDescription() . "<br>";
echo "Price: " . $product->getPrice() . "<br>";
?>
</main>

<?php include_once '../src/View/layouts/footer.php';?>