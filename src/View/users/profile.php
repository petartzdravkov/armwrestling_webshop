<?php

 //redirect if user not logged in
 if(!isset($_SESSION['email'])){
     header("Location: index.php?target=user&action=login");
 }

 ?>

<?php include_once '../src/View/layouts/header.php';?>

<main class="container py-5">
    <!-- Profile Card -->
    <div class="card border-1 mb-5">
	<h6 class="h4 p-3 card-title m-0">Profile</h6>
	
	<div class="card border-0 shadow-sm">
	    <div class="card-body px-4 py-2">

		<p class="text-danger p-0 m-0"><?=$error;?></p>
		<p class="text-success p-0 m-0"><?=$success;?></p>
		
		<!-- Username -->
		<!-- <div class="d-flex align-items-center mb-2">
		     <p class="text-muted me-1 mb-0 p-0">Username</p>
		     <button class="btn btn-sm btn-link p-0" aria-label="Edit profile">
		     <i class="bi bi-pencil"></i>
		     </button>
		     </div> -->

		<!-- Email -->
		<div class="mb-2">
		    <p class="text-muted mb-1">Email</p>
		    <p class="mb-0"><?=$_SESSION['email']?></p>
		</div>

		<!-- Pass -->
		<div class="d-flex align-items-center mb-3">
		    <p class="text-muted me-1 mb-0 p-0">Password</p>
		    <button type="button" class="btn btn-sm btn-link p-0" data-bs-toggle="modal" data-bs-target="#passModal" aria-label="Edit profile">
			<i class="bi bi-pencil"></i>
		    </button>
		</div>
		
		<!-- Logout -->
		<form method="POST" action="index.php?target=user&action=profile">
		    <div class="text-center my-3">
			<input type="submit" class="btn btn-secondary" name="logout_btn" value="Logout" class="m-2">
		    </div>
		</form>
	    </div>
	</div>
    </div>


    <!-- Order Card -->
    <div class="card border-1">
	<h6 class="h4 p-3 card-title m-0">Orders</h6>
	
	<div class="card border-0 shadow-sm">
	    <div class="card-body px-4 py-2">
		
		<div class="accordion" id="accordionOrders">
		    <?php
		    foreach($orders as $key_order => $order){
			// date object
			$date = new DateTime($order->getDatetime());
		    ?>
			<div class="accordion-item">
			    <h2 class="accordion-header">
				<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?=$key_order?>" aria-expanded="true" aria-controls="collapseOne">
				    Order #<?=$key_order + 1 . ", " . $date->format('d M Y, H:i') . ", status: " . $order->getStatus();?>
				</button>
			    </h2>
			    <div id="collapse<?=$key_order?>" class="accordion-collapse collapse" data-bs-parent="#accordionOrders">
				<div class="accordion-body">
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
						$sold_items = $orderDao->getAllSoldItemsFromOrder($order->getOrderId());
						foreach($sold_items as $sold_item){

						    $product = $productDao->getProductById($sold_item['product_id']);
						    /* foreach($cart_items as $key => $amount){
						   [$id, $size] = explode("|", $key);
						   $cart_item = $productDao->getProductById($id);
						   $sizes_in_stock = $productDao->getAmountByIdAndSize($id, $size); */
						?>
						<tr>
						    <td>
							<div class="d-flex align-items-center">
							    <div class="cart-img-container me-3">
								<img src="<?=$product->getImgPath();?>" alt="pdouct-img" style="height: 100px; width: 100px;" class="object-fit-cover">
							    </div>
							    <div>
							      <h5 class="fw-bold mb-1"><?=$product->getName();?></h5>
							      <p class="text-muted mb-0">Size: <?=$productDao->getSizeNameBySizeId($sold_item['size_id']);?></p>
							    </div>
							</div>
						    </td>						    
						    <td class="text-center">
							<h5 class="h5"><?=$product->getPrice();?>&euro;</h5>
						    </td>
						    <td class="text-center">
							<div class="d-flex flex-column flex-md-row justify-content-md-center">
							    <h5><?= $sold_item['amount']?></h5>
							</div>
						    </td>
						    <td class="text-end">
							<div class="d-flex flex-column ">
							    <h5 class=""><?=$sold_item['amount'] * $product->getPrice();?>&euro;</h5>
							</div>
						    </td>
						</tr>
						<?php } ?>
					    </tbody>
					</table>
				    </div>
				</div>
			    </div>
			</div>
		    <?php } ?>
		</div>
	    </div>
	</div>
    </div>
</main>

<!-- Modal -->
<div class="modal fade" id="passModal" tabindex="-1" aria-labelledby="passModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
	<div class="modal-content">
	    <div class="modal-header">
		<h1 class="modal-title fs-5" id="exampleModalLabel">Change Password</h1>
		<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
	    </div>
	    <form method="POST" action="index.php?target=user&action=profile">
		<div class="modal-body">
		    <div class="row mb-3">
			<div class="col-md-6 mb-3 mb-md-0">
			    <label for="oldPass" class="form-label text-muted">Old Password</label>
			    <input type="password" class="form-control form-control-sm" id="oldPass" name="oldPass" required>
			</div>
			<div class="col-md-6">
			    <label for="newPass" class="form-label text-muted">New Password</label>
			    <input type="password" class="form-control form-control-sm" id="newPass" name="newPass" required>
			</div>
		    </div>
		</div>
		<div class="modal-footer">
		    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
		    <input type="submit" class="btn btn-primary" name="change_pass_btn" value="Change Password">		
		</div>
	    </form>
	</div>
    </div>
</div>

<?php include_once '../src/View/layouts/footer.php';?>