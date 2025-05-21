<?php include_once '../src/View/layouts/header.php';?>

<main>
    <div class="container">

	<?php
	//	echo "Name: " . $product->getName() . "<br>";
	//	echo "Description: " . $product->getDescription() . "<br>";
	//	echo "Price: " . $product->getPrice() . "<br>";
	//var_dump($_SESSION);
	?>

	


	<!-- Product Section -->
	<div class="row">
	    <!-- Product Gallery -->
	    <div class="col-sm-6 mb-4 mb-lg-0">
		<div class="row">
		    <div class="col">
			<div class="product-img-container mb-2">
			    <img src="<?=$product->getImgPath();?>" class="img-fluid rounded" alt="product_image">
			</div>
		    </div>
		</div>
	    </div>
	    
	    <!-- Product Details -->
	    <div class="col">
		<h1 class="fw-bold mb-2"><?=$product->getName();?></h1>
		
		<!-- Rating -->
		<!-- <div class="mb-3">
		     <div class="d-flex align-items-center">
		     <div class="me-2">
		     <i class="bi bi-star-fill text-warning"></i>
		     <i class="bi bi-star-fill text-warning"></i>
		     <i class="bi bi-star-fill text-warning"></i>
		     <i class="bi bi-star-fill text-warning"></i>
		     <i class="bi bi-star-fill text-warning"></i>
		     </div>
		     <span>4.9</span>
		     </div>
		     </div> -->
		
		<!-- Description -->
		<p class="mb-4 description">
		    <?=$product->getDescription();?>
		</p>
		
		<!-- Price -->
		<div class="mb-3">
		    <h2 class="fw-bold">€ <?=$product->getPrice();?></h2>
		    <p class="text-muted small">Tax included.</p>
		</div>

		<!-- TODO color selection -->
		
		<!-- Size Selection -->
		<div class="mb-4">
		    <p class="mb-2 fw-medium">SIZE</p>
		    <div class="d-flex flex-wrap gap-2">
			<?php
			if(!$available_sizes){
			?>
			    <h6>OUT OF STOCK</h6>
			<?php 
			}else{
			    foreach($available_sizes as $size){
				$selected_size = isset($_GET['size']) ? htmlentities(trim($_GET['size'])) : false;
			?>
			    <a href="<?= "index.php?target=product&action=view&pname=$og_pname" . "&size=" . $size['name'];?>" class="btn size-option <?=($selected_size === $size['name']) ? 'active' : '';?>"><?=strtoupper($size['name']);?></a>
			<?php }} ?>
		    </div>
		</div>
		
		<!-- Add to Cart -->
		<?php if($available_sizes){ ?>
		    <form method="POST">
			<input type="hidden" name="id" value="<?=$product->getId()?>">
			<input type="hidden" name="selected_size" value="<?=$selected_size;?>">
			<input type="submit" class="btn btn-primary w-100 py-3 mb-4" name="add_to_cart_btn" value="Add To Cart">
		    </form>
		<?php }else{ ?>
		    <button class="btn btn-primary w-100 py-3 mb-4" disabled> Add To Cart</button>
		<?php } ?>
		
		<!-- Product Features -->
		<div class="mb-4 product-features">
		    <div class="d-flex align-items-center mb-2">
			<i class="bi bi-check-square me-2"></i>
			<span>2+ years guarantee</span>
		    </div>
		    <div class="d-flex align-items-center">
			<i class="bi bi-truck me-2"></i>
			<span>Ships from within the EU</span>
		    </div>
		</div>
	    </div>
</main>

<?php include_once '../src/View/layouts/footer.php';?>