<?php

?>

<?php include_once '../src/View/layouts/header.php';?>

<main>
    <div class="container">

	<?php if(count($_SESSION['cart']) == 0){ ?>
	    <div class="wrapper min-vh-100 d-flex justify-content-center align-items-center">
		<div class="text-center">
		    <h4> Your cart is currently empty </h4>
		    <a href="index.php?target=product&action=clothing" class="btn btn-outline-secondary px-5 py-1 rounded-0 ">Return To Shop</a>
		</div>
	    </div>
	<?php }else{ ?>

	    <!-- Cart Table -->
	    <div class="table-responsive">
		<table class="table align-middle">
		    <thead>
			<tr class="text-uppercase">
			    <th scope="col" class="text-start">Product</th>
			    <th scope="col" class="text-center">Price</th>
			    <th scope="col" class="text-center">Quantity</th>
			    <th scope="col" class="text-end">Total</th>
			</tr>
		    </thead>
		    <tbody>
			<?php
			foreach($cart_items as $key => $amount){
			    [$id, $size] = explode("|", $key);
			    $cart_item = $productDao->getProductById($id);
			    $sizes_in_stock = $productDao->getAmountByIdAndSize($id, $size);
			?>
			    <tr>
				<td>
				    <div class="d-flex align-items-center">
					<div class="cart-img-container me-3">
					    <img src="<?=$cart_item->getImgPath()?>" alt="pdouct-img" style="height: 100px; width: 100px;" class="object-fit-cover">
					</div>
					<div>
					    <h5 class="fw-bold mb-1"><?=$cart_item->getName()?></h5>
					    <p class="text-muted mb-0">Size: <?=strtoupper($size);?></p>
					</div>
				    </div>
				</td>
				<td class="text-center">
				    <span class="fw-medium"><?=$cart_item->getPrice()?> &euro;</span>
				</td>
				<td class="text-center">
				    <div class="d-flex flex-column flex-md-row justify-content-md-center">
					<button class="btn btn-outline-secondary" onclick="decrementQuantity(this)">−</button>
					<input type="number" class="btn btn-secondary" value="<?=$amount;?>" min="1" max="<?=$sizes_in_stock;?>">
					<button class="btn btn-outline-secondary" onclick="incrementQuantity(this)">+</button>
				    </div>
				</td>
				<td class="text-end">
				    <div class="d-flex flex-column ">
					<span class="fw-medium"><?=$cart_item->getPrice() * $amount?>&euro;</span>
					<form method="POST">
					    <input type="hidden" name="key" value="<?=$key;?>">
					    <button type="submit" class="btn btn-sm btn-light">
						<i class="bi bi-trash"></i>
					    </button>
					</form>
				    </div>
				</td>
			    </tr>
			<?php } ?>
		    </tbody>
		</table>
	    </div>

	<!-- Order Summary -->
	<div class="row">
	    <div class="col-md-6 ms-auto">
		<div class="d-flex justify-content-between align-items-center mb-2">
		    <h6 class="">Subtotal</h6>
		    <h6 class=""><?=$total?> &euro;</h6>
		</div>
		<p class="text-muted mb-1 small">Taxes included. Discounts and shipping calculated at checkout.</p>
		<a href="index.php?target=checkout&action=index" class="btn btn-primary w-100 py-3 text-uppercase fw-bold">Check out</a>
	    </div>
	</div>
	<?php } ?>
    </div>


    
</main>

<script>
 function incrementQuantity(button) {
     var input = button.previousElementSibling;
     var currentValue = parseInt(input.value);
     if (currentValue < parseInt(input.max)) {
         input.value = currentValue + 1;
     }
 }
 
 function decrementQuantity(button) {
     var input = button.nextElementSibling;
     var currentValue = parseInt(input.value);
     if (currentValue > parseInt(input.min)) {
         input.value = currentValue - 1;
     }
 }
</script>
<?php include_once '../src/View/layouts/footer.php';?>