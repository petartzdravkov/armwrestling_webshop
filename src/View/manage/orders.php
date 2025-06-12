<?php
var_dump($_SESSION);
if($_SESSION['role'] > 2 || empty($_SESSION)) header("Location: index.php");
?>

<?php include_once '../src/View/layouts/header.php';?>

<main class="container py-4 px-4">
    <h1 class="fw-bold"> Manage Orders </h1>

    <!-- TODO Filter Tabs -->


    <!-- Orders Table -->
    <div class="card-body p-0">
        <div class="table-responsive">
	    <form method="POST">
		<table class="table table-hover mb-0">
		    <thead class="table">
			<tr>

			    <th scope="col">Order</th>
			    <th scope="col">Date</th>
			    <th scope="col">Customer</th>
			    <th scope="col">Total</th>
			    <th scope="col">Status</th>
			    <th scope="col">Items</th>
			</tr>
		    </thead>
		    <tbody>
			<!-- Order -->
			<?php foreach($orders as $order){
			    $order_id = $order->getOrderId();
			    
			    // date object
			    $date = new DateTime($order->getDatetime());

			    // user
			    $user = $userDao->findById($order->getUserId());

			    // current order status
			    $current_order_status = $orderDao->getOrderStatusByOrderId($order_id);

			    // sold items
			    $sold_items = $orderDao->getAllSoldItemsFromOrder($order_id);
			    $total_sum_paid = 0;
			    $total_items = 0;
			    foreach($sold_items as $sold_item){
				$product = $productDao->getProductById($sold_item['product_id']);
				$total_sum_paid += $product->getPrice() * $sold_item['amount'];
				$total_items += $sold_item['amount'];
			    }
			?>
			<tr data-bs-toggle="modal" data-bs-target="#orderModal_<?=$order_id;?>">
			    <td>#<?=$order_id;?></td>
			    <td><?=$date->format('d M Y, H:i');?></td>
			    <td><?=$user ? $user->getEmail() : 'Guest';?></td>
			    <td><?=$total_sum_paid;?>&euro;</td>
			    <td>
				<div class="dropdown">
				    <select name="status_<?=$order->getOrderid();?>" class="form-select btn btn-secondary">
					<option value="processing" <?=$current_order_status === 'processing' ? 'selected' : '';?>>Processing</option>
					<option value="shipped" <?=$current_order_status === 'shipped' ? 'selected' : '';?>>Shipped</option>
				    </select>
				</div>
			    </td>
			    <td><?=$total_items;?> items</td>
			</tr>


		    <?php } ?>
		    <tr>
			<td></td><td></td><td></td><td></td>
			<td><input type="submit" name="update_status" value="Update Status" class="btn btn-secondary"></td><td></td>
		    </tr>
		    </tbody>
		</table>
	    </form>
	</div>
    </div>		    
</main>

<?php include_once '../src/View/layouts/footer.php';?>


<!-- Modal -->
<?php foreach($orders as $order){
    $order_id = $order->getOrderId();
    
    // date object
    $date = new DateTime($order->getDatetime());

    // user
    $user = $userDao->findById($order->getUserId());

    // current order status
    $current_order_status = $orderDao->getOrderStatusByOrderId($order_id);

    // sold items
    $sold_items = $orderDao->getAllSoldItemsFromOrder($order_id);
?>
<!-- Modal -->
<div class="modal fade" id="orderModal_<?=$order_id;?>" tabindex="-1">
    <div class="modal-dialog">
	<div class="modal-content">
	    <div class="modal-header">
		<h1 class="modal-title fs-5" id="orderModalLabel">Order #<?=$order_id;?></h1>
		<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
	    </div>
	    <div class="modal-body">
		<table class="table">
		    <thead>
			<tr>
			    <th scope="col">Product</th>
			    <th scope="col">Size</th>
			    <th scope="col">Price</th>
			    <th scope="col">Quantity</th>
			</tr>
		    </thead>
		    <tbody>
			<?php
			foreach($sold_items as $sold_item){
			    $product = $productDao->getProductById($sold_item['product_id']);
			    $size = strtoupper($productDao->getSizeNameBySizeId($sold_item['size_id']));
			?>
			<tr>
			    <td><?=$product->getName();?></td>
			    <td><?=$size;?></td>			    
			    <td><?=$product->getPrice();?>&euro;</td>
			    <td><?=$sold_item['amount'];?></td>
			</tr>
			<?php } ?>
		    </tbody>
		</table>
	    </div>
	</div>
    </div>
</div>
<?php } ?>