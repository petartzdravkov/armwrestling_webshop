<?php
//var_dump($_SESSION);
if($_SESSION['role'] > 2 || empty($_SESSION['role'])) header("Location: index.php");
?>

<?php include_once '../src/View/layouts/header.php';?>

<main class="container py-4 px-4">
    <h1 class="fw-bold"> Manage Products </h1>

    <!-- TODO Filter Tabs -->


    <!-- Products Table -->
    <div class="card-body p-0">
        <div class="table-responsive">
	    <form method="POST">
		<table class="table table-hover mb-0">
		    <thead class="table border-bottom">
			<tr>
			    <th scope="col">Product</th>
			    <th scope="col">Status</th>
			    <th scope="col">Inventory</th>
			    <th scope="col">
				<button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal" data-bs-target="#modalAdd">Add Product</button>
			    </th>
			</tr>
		    </thead>
		    <tbody>
			<!-- Products -->
			<?php foreach($products as $product){
			    $product_id = $product->getId();
			    $current_product_status = $product->getStatus();
			?>
			    <tr class="border-bottom">
				<td>
				    <div class="d-flex align-items-center">
					<div class="cart-img-container me-3">
					    <img src="<?=$product->getImgPath()?>" alt="product-img" style="height: 30px; width: 30px;" class="object-fit-cover">
					</div>
					<div>
					    <h6 class="mb-1"><?=$product->getName()?></h5>
					</div>
				    </div>
				</td>
				<td class="align-middle">
				    <div class="dropdown">
					<select name="status_<?=$product->getId();?>" class="form-select btn btn-secondary">
					    <option value="draft" <?=$current_product_status === 'draft' ? 'selected' : '';?>>Draft</option>
					    <option value="published" <?=$current_product_status === 'published' ? 'selected' : '';?>>Published</option>
					    <option value="deleted" <?=$current_product_status === 'deleted' ? 'selected' : '';?>>Deleted</option>
					</select>
				    </div>
				</td>
				<td class="align-middle"><?=$productDao->getTotalAmountByProductId($product->getId());?> in stock for <?=$productDao->getAmountOfSizesByProductId($product->getId());?> sizes</td>
				<td class="align-middle">
				    <button type="button" class="btn btn-secondary" data-bs-toggle="modal" data-bs-target="#orderModal_<?=$product_id?>">Edit</button>
				</td>
			    </tr>
			<?php } ?>

			<tr>
			    <td></td>
			    <td class="text-center"><input type="submit" name="update_status" value="Update Status" class="btn btn-secondary"></td><td></td><td></td>
			</tr>
		    </tbody>
		</table>
	    </form>
	</div>
    </div>		    
</main>

<?php include_once '../src/View/layouts/footer.php';?>

<!-- Modal Edit-->
<?php foreach($products as $product){
    $product_id = $product->getId();
?>
    <form method="POST" enctype="multipart/form-data">
	<div class="modal fade" id="orderModal_<?=$product_id;?>" tabindex="-1">
	    <div class="modal-dialog">
		<div class="modal-content">
		    <div class="modal-header">
			<h1 class="modal-title fs-5" id="orderModalLabel">Edit Product</h1>
			<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		    </div>
		    <div class="modal-body">
			<div class="row mb-2">
			    <div class="col">
				<input type="text" class="form-control mb-2" placeholder="Name" name="name" id="prod_name_<?=$product_id;?>" />
				<input type="number" class="form-control mb-2" placeholder="Price" min="0" name="price" id="prod_price_<?=$product_id;?>" />
				
				<select class="form-select" onchange="updateSizes(this, <?=$product_id;?>)" name="category" id="category_select_<?=$product_id;?>">
				    <option value="clothing" <?=$product->getCategoryId() == 1 ? 'selected' : '';?>>Clothing</option>
				    <option value="equipment" <?=$product->getCategoryId() == 2 ? 'selected' : '';?>>Equipment</option>
				</select>

				<div class="input-group" id="sizes_amount_input_<?=$product_id;?>">
				    <!-- JS fills this in -->
				</div>
			    </div>
			    <div class="col">
				<textarea class="form-control h-50 mb-2" placeholder="Description" name="description" id="prod_description_<?=$product_id;?>"></textarea>
				<div class="cart-img-container mb-2 text-center">
				    <img src="<?=$product->getImgPath()?>" alt="product-img" style="height: 100px; width: 100px;" class="object-fit-cover">
				</div>
				<input type="file" name="img_path" accept="image/*" id="img_path_<?=$product_id;?>" class="form-control mb-2" />
			    </div>
			</div>
			<div class="row p-2">
			    <input type="hidden" name="productId" value="<?=$product_id;?>"/>
			    <input type="submit" name="save_edits" value="Save" class="btn btn-secondary mb-2"/>
			    <!-- <input type="submit" name="delete_product" value="Delete" class="btn btn-danger"/> -->
			</div>
		    </div>
		</div>
	    </div>
	</div>
    </form>
<?php } ?>

<!-- Modal Add-->
<form method="POST" enctype="multipart/form-data">
    <div class="modal fade" id="modalAdd" tabindex="-1">
	<div class="modal-dialog">
	    <div class="modal-content">
		<div class="modal-header">
		    <h1 class="modal-title fs-5" id="orderModalLabel">Add Product</h1>
		    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
		</div>
		<div class="modal-body">
		    <div class="row mb-2">
			<div class="col">
			    <input type="text" class="form-control mb-2" placeholder="Name" name="name" id="prod_name_add" />
			    <input type="number" class="form-control mb-2" placeholder="Price" min="0" name="price" id="prod_price_add" />
			    
			    <select class="form-select" onchange="addSizes(this)" name="category" id="category_select_add">
				<option value="clothing">Clothing</option>
				<option value="equipment">Equipment</option>
			    </select>

			    <div class="input-group" id="sizes_amount_input_add">
				<!-- JS fills this in -->
			    </div>
			</div>
			<div class="col">
			    <textarea class="form-control h-50 mb-2" placeholder="Description" name="description" id="prod_description_add"></textarea>
			    <input type="file" name="img_path" accept="image/*" id="img_path_add" class="form-control mb-2" />
			</div>
		    </div>
		    <div class="row p-2">
			<input type="submit" name="save_new_product" value="Save Product" class="btn btn-secondary mb-2"/>
		    </div>
		</div>
	    </div>
	</div>
    </div>
</form>

<script>
 
 // fill available info and update sizes if dropdown changed
 document.addEventListener("DOMContentLoaded", function () {
     const modals = document.querySelectorAll(".modal");

     modals.forEach(modal => {
	 // Skip the Add Product modal
	 if (modal.id === "modalAdd"){
	     const select = document.getElementById("category_select_add");
	     addSizes(select);
	     return;
	 }
	 
         modal.addEventListener("shown.bs.modal", function () {
             const productId = this.id.split("_")[1]; // "orderModal_4" => "4"
             const select = document.getElementById("category_select_" + productId);
	     fillAvailableInfo(productId);

             if (select) {
                 updateSizes(select, productId);
             }
         });
     });
 });

 function updateSizes(input, product_id){
//          console.log(input.value);
     var r = new XMLHttpRequest();
     r.open("get", "index.php?target=ajax&action=getSizes&category=" + input.value + "&product_id=" + product_id);
     r.onreadystatechange = function(e){
	 if(this.readyState == 4 && this.status == 200){
	     var sizes_amount_wrapper = document.getElementById("sizes_amount_input_" + product_id);
	     sizes_amount_wrapper.innerHTML = '';
	     
	     //get json from server
	     var amounts = JSON.parse(this.responseText);
	     
	     //create input fields
	     for(var size in amounts){
		 var individual_size_wrapper = document.createElement("div");
		 individual_size_wrapper.className = "input-group m-0";
		 sizes_amount_wrapper.appendChild(individual_size_wrapper);
		 
		 var size_label = document.createElement("span");
		 size_label.setAttribute("class", "input-group-text w-25");
		 size_label.innerHTML = size;
		 individual_size_wrapper.appendChild(size_label);

		 var size_amount = document.createElement("input");
		 size_amount.setAttribute("type", "number");
		 size_amount.setAttribute("class", "form-control");
		 size_amount.setAttribute("placeholder", "Amount");
		 size_amount.setAttribute("min", "0");
		 size_amount.setAttribute("name", size + "_amount");
		 size_amount.value = amounts[size];
		 individual_size_wrapper.appendChild(size_amount);
	     }	  
	 }
     }
     r.send();
 }

function addSizes(input){
//          console.log(input.value);
     var r = new XMLHttpRequest();
     r.open("get", "index.php?target=ajax&action=getSizes&category=" + input.value);
     r.onreadystatechange = function(e){
	 if(this.readyState == 4 && this.status == 200){
	     var sizes_amount_wrapper = document.getElementById("sizes_amount_input_add");
	     sizes_amount_wrapper.innerHTML = '';
	     
	     //get json from server
	     var sizes = JSON.parse(this.responseText);
	     
	     //create input fields
	     for(var i = 0; i < sizes.length; i++){
		 var individual_size_wrapper = document.createElement("div");
		 individual_size_wrapper.className = "input-group m-0";
		 sizes_amount_wrapper.appendChild(individual_size_wrapper);
		 
		 var size_label = document.createElement("span");
		 size_label.setAttribute("class", "input-group-text w-25");
		 size_label.innerHTML = sizes[i];
		 individual_size_wrapper.appendChild(size_label);

		 var size_amount = document.createElement("input");
		 size_amount.setAttribute("type", "number");
		 size_amount.setAttribute("class", "form-control");
		 size_amount.setAttribute("placeholder", "Amount");
		 size_amount.setAttribute("min", "0");
		 size_amount.setAttribute("name", sizes[i] + "_amount");
		 individual_size_wrapper.appendChild(size_amount);
	     }	  
	 }
     }
     r.send();
 }
 
 function fillAvailableInfo(productId){
     var r = new XMLHttpRequest();
     r.onreadystatechange = function(e){
	 if(this.readyState == 4 && this.status == 200){
	     var prod_name = document.getElementById("prod_name_" + productId);
	     var prod_price = document.getElementById("prod_price_" + productId);
	     var prod_description = document.getElementById("prod_description_" + productId);
	     
	     // get json from server
	     var sizes = JSON.parse(this.responseText);

	     // fill values
	     prod_name.value = sizes['name'];
	     prod_price.value = sizes['price'];
	     prod_description.value = sizes['description'];
	 }
     }
     r.open("get", "index.php?target=ajax&action=fillInfo&productId=" + productId);
     r.send();
 }
 
</script>