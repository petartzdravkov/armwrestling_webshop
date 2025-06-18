<?php

?>

<?php include_once '../src/View/layouts/header.php';?>

<main class="container py-4 px-4">
    <h1 class="fw-bold"> <?=$title;?> </h1>

    <!-- Filters and View Options -->
    <!-- <div class="d-flex justify-content-between align-items-center mb-4">
	 <div class="dropdown">
         <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
	 SORT BY
         </button>
         <ul class="dropdown-menu">
	 <li><a class="dropdown-item" href="#">Newest</a></li>
	 <li><a class="dropdown-item" href="#">Price: Low to High</a></li>
	 <li><a class="dropdown-item" href="#">Price: High to Low</a></li>
	 <li><a class="dropdown-item" href="#">Best Selling</a></li>
         </ul> -->
    <!-- TODO products for the correct category -->
    <!-- <span class="d-flex text-body-secondary py-2">2 products</span>
	 </div>
	 </div> -->

    <!-- Products Grid -->
    <div class="row g-4">
	<?php if($products){
	    foreach($products as $product){
		$available_sizes = $productDao->getAllSizesByProductId($product->getId());
	?>
	    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
		<div class="card border-0 h-100">
		    <div class="product-img-contatiner w-100 overflow-hidden position-relative" style="height: 285px">
			<a href="index.php?target=product&action=view&pname=<?=str_replace(" ", "-", strtolower($product->getName()));?>"> <img src="<?=$product->getImgPath();?>" class="card-img-top p-2 bg-secondary-subtle object-fit-cover w-100 h-100" alt="clothing_image"> </a>
			<h6 class="text-muted position-absolute bottom-0 start-50 translate-middle-x"><?=!$available_sizes ? 'OUT OF STOCK' : '';?></h6>
		    </div>
		    <div class="card-body px-0 pt-3 pb-0">
			<h5 class="card-title"><?=$product->getName();?></h5>
			<p class="card-text fw-bold"><?=$product->getPrice();?> &euro;</p>
		    </div>
		</div>
	    </div>
	<?php }
	}else{
	    echo "<h2> no available products</h2>";
	}?>
    </div>

    <!-- Pagination -->
    <nav aria-label="Product pagination">
        <ul class="pagination justify-content-center mt-4">
	    <?php if ($page > 1){ ?>
                <li class="page-item">
                    <a class="page-link" href="?target=product&action=<?=$category;?>&page=<?= $page - 1; ?>" aria-label="Previous">&laquo;</a>
                </li>
	    <?php } ?>
	    
	    <?php for ($i = 1; $i <= $total_pages; $i++){ ?>
                <li class="page-item <?= ($i === $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?target=product&action=<?=$category;?>&page=<?= $i; ?>"><?= $i; ?></a>
                </li>
	    <?php } ?>

	    <?php if ($page < $total_pages){ ?>
                <li class="page-item">
		    <a class="page-link" href="?target=product&action=<?=$category;?>&page=<?= $page + 1; ?>" aria-label="Next">&raquo;</a>
                </li>
	    <?php } ?>
        </ul>
    </nav>


</main>

<?php include_once '../src/View/layouts/footer.php';?>