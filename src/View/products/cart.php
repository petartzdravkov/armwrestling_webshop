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
//			var_dump($_SESSION['cart']);
			foreach($cart_items as $key => $amount){
			    [$id, $size] = explode("|", $key);
			    $cart_item = $productDao->getProductById($id);
			    $size_id = $productDao->getSizeId($size);
			    $sizes_in_stock = $productDao->getAmountByIdAndSize($id, $size_id);
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
				    <span class="fw-medium" id="" data-key="<?=$key;?>" data-price="<?=$cart_item->getPrice();?>"><?=$cart_item->getPrice()?> &euro;</span>
				</td>
				<td class="text-center">
				    <div class="d-flex flex-column flex-md-row justify-content-md-center">
					<button class="btn btn-outline-secondary" onclick="decrementQuantity(this)">−</button>
					<input type="number" class="btn btn-secondary" name="<?=$key;?>" value="<?=$amount;?>" min="1" max="<?=$sizes_in_stock;?>" data-amount="<?=$amount;?>">
					<button class="btn btn-outline-secondary" onclick="incrementQuantity(this)">+</button>
				    </div>
				</td>
				<td class="text-end">
				    <div class="d-flex flex-column ">
					<span class="fw-medium total_price" id="total_price_<?=$key;?>"><?=$cart_item->getPrice() * $amount?>&euro;</span>
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
		    <h6 class="" id="subtotal"><?=$total?> &euro;</h6>
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
     
     updateCartRequest(input);
     updatePrices(input);
     updateSubtotal();
 }
 
 
 function decrementQuantity(button) {
     var input = button.nextElementSibling;
     var currentValue = parseInt(input.value);
     if (currentValue > parseInt(input.min)) {
         input.value = currentValue - 1;
     }

     updateCartRequest(input);
     updatePrices(input);
     updateSubtotal();
 }

 function updateCartRequest(input){
     var r = new XMLHttpRequest();
     r.open("get", "index.php?target=ajax&action=updateCart&key=" + input.name + "&value=" + input.value);
     r.send();
 }

 function updatePrices(input){
     var key = input.name;
     var quantity = parseInt(input.value);
     
     var priceSpan = document.querySelector(['[data-key="' + key + '"]']);
     var unitPrice = parseInt(priceSpan.dataset.price);

     var totalPrice = document.getElementById("total_price_" + key);
     var newTotal = unitPrice * quantity;
     totalPrice.innerHTML = newTotal + "&euro;";     
 }

 function updateSubtotal(){
     var subtotal = 0;

     document.querySelectorAll('.total_price').forEach((element) => {
	 let raw = element.textContent;
	 let num = parseInt(raw.replace("€", ""));
	 subtotal += num;
     })

     var subtotal_element = document.getElementById('subtotal');
     subtotal_element.innerHTML = subtotal + ' &euro;';
 }
</script>
<?php include_once '../src/View/layouts/footer.php';?>