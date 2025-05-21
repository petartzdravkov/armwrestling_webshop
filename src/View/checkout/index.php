<?php include_once '../src/View/layouts/header.php';?>


<main class="container">
    <div class="row">
      <!-- Checkout Form -->
      <div class="col-lg-7">
        <!-- Contact Section -->
        <div class="mb-4">
          <div class="d-flex justify-content-between align-items-center mb-3">
              <h2 class="mb-0">Contact</h2>
	      <?php if(!$isUserLogged){ ?>
		  <a href="index.php?target=user&action=login" class="text-decoration-none">Log in</a>
	      <?php } ?>
          </div>
          
          <div class="mb-3">
            <input type="email" class="form-control" placeholder="Email" value="<?=$_SESSION['email'] ?? '';?>">
          </div>
          
        </div>
        
        <!-- Delivery Section -->
        <div class="mb-5">
          <h2 class="mb-3">Delivery</h2>
          
          <div class="mb-3">
            <label for="country" class="form-label">Country/Region</label>
            <select class="form-select" id="country">
              <option selected>Bulgaria</option>
              <option>Austria</option>
              <option>Belgium</option>
              <option>Croatia</option>
              <option>Czech Republic</option>
              <!-- More countries  -->
            </select>
          </div>
          
          <div class="row mb-3">
            <div class="col-md-6 mb-3 mb-md-0">
              <input type="text" class="form-control" placeholder="First name" aria-label="First name">
            </div>
            <div class="col-md-6">
              <input type="text" class="form-control" placeholder="Last name" aria-label="Last name">
            </div>
          </div>
          
          <div class="mb-3">
            <input type="text" class="form-control" placeholder="Company (optional)" aria-label="Company">
          </div>
          
          <div class="mb-3">
            <input type="text" class="form-control" placeholder="Address" aria-label="Address">
          </div>
          
          <div class="mb-3">
            <input type="text" class="form-control" placeholder="Apartment, suite, etc. (optional)" aria-label="Apartment">
          </div>
          
          <div class="row mb-3">
            <div class="col-md-6 mb-3 mb-md-0">
              <input type="text" class="form-control" placeholder="Postal code" aria-label="Postal code">
            </div>
            <div class="col-md-6">
              <input type="text" class="form-control" placeholder="City" aria-label="City">
            </div>
          </div>
          
          <div class="mb-3">
            <div class="input-group">
              <input type="tel" class="form-control" placeholder="Phone" aria-label="Phone">
            </div>
          </div>
        </div>
        
        <!-- Payment Section -->
        <div class="mb-5">
          <h2 class="mb-2">Payment</h2>
          <p class="text-muted small mb-4">All transactions are secure and encrypted.</p>
          
          <div class="mb-4">
            
            <!-- Credit Card Form -->
            <div class="card-form p-3 border rounded mb-3">
              <div class="mb-3">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="Card number" aria-label="Card number">
                  <span class="input-group-text border-start-0">
                    <i class="bi bi-lock"></i>
                  </span>
                </div>
              </div>
              
              <div class="row mb-3">
                <div class="col-md-6 mb-3 mb-md-0">
                  <input type="text" class="form-control" placeholder="Expiration date (MM / YY)" aria-label="Expiration date">
                </div>
                <div class="col-md-6">
                  <div class="input-group">
                    <input type="text" class="form-control" placeholder="Security code" aria-label="Security code">
                    <span class="input-group-text border-start-0">
			<i class="bi bi-question-circle text-muted" type="button" class="btn" data-bs-toggle="popover" data-bs-title="CVC" data-bs-content="3-digit security code usually found on the back of your card."></i>
                    </span>
                  </div>
                </div>
              </div>
              
              <div class="mb-3">
                <input type="text" class="form-control" placeholder="Name on card" aria-label="Name on card">
              </div>
              
              <div class="form-check">
                <input class="form-check-input" type="checkbox" id="billingAddressCheck" checked>
                <label class="form-check-label" for="billingAddressCheck">
                  Use shipping address as billing address
                </label>
              </div>
            </div>
	    <!-- TODO integrate stripe -->
	    <form method="POST">
		<!-- <a href="index.php?target=checkout&action=success" class="btn btn-primary w-100 p-4">Pay Now</a> -->
		<input type="submit" class="btn btn-primary w-100 p-4" name="pay_btn" value="Pay Now"/>
	    </form>
            
          </div>
        </div>
      </div>

      
      <!-- Order Summary -->
      <div class="col-lg-5">
          <div class="bg-dark p-4 sticky-top">
              <!-- Cart Items -->
              <div class="mb-4">
		  <?php
		  foreach($cart_items as $key => $amount){
		      [$id, $size] = explode("|", $key);
		      $cart_item = $productDao->getProductById($id);
		  ?>
		      <div class="d-flex mb-3">
			  <div class="position-relative me-4">
			      <div class="cart-img-container me-2">
				  <img src="<?=$cart_item->getImgPath()?>" alt="pdouct-img" style="height: 80px; width: 80px;" class="object-fit-cover">
			      </div>
			      <span class="position-absolute top-0 start-100 translate-middle p-2 badge rounded-pill bg-secondary"><?=$amount;?></span>
			  </div>
			  <div class="flex-grow-1">
			      <h3 class="h6 mb-1"><?=$cart_item->getName();?></h3>
			      <p class="text-muted mb-0">Size: <?=strtoupper($size);?></p>
			  </div>
			  <div class="ms-3 text-end">
			      <p class="mb-0"><?=$cart_item->getPrice();?>&euro;</p>
			  </div>
		      </div>
		<?php } ?>
          
          <!-- Order Totals -->
          <div class="border-top pt-3">
            <div class="d-flex justify-content-between mb-2">
              <span>Subtotal</span>
              <span><?=$total;?>&euro;</span>
            </div>
            
            <div class="d-flex justify-content-between mb-3">
              <span>Shipping</span>
	      <p> 12&euro;</p>
            </div>
            
            <div class="d-flex justify-content-between align-items-center mb-2">
              <span class="fw-bold">Total</span>
              <div class="text-end">
                <span class="fs-4 fw-bold"><?=$total + 12;?>&euro;</span>
              </div>
            </div>
            
            <p class="small text-muted mb-1">Including <?=($total + 12)*0.21?>&euro; in taxes</p>
	    <p class="text-danger"><?=$payment_error;?></p>
          </div>
        </div>
      </div>
    </div>
</main>








<?php include_once '../src/View/layouts/footer.php';?>

<script>
 const popoverTriggerList = document.querySelectorAll('[data-bs-toggle="popover"]')
 const popoverList = [...popoverTriggerList].map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl))
</script>