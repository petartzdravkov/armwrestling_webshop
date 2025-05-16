<?php include_once '../src/View/layouts/header.php';?>

<div class="container-fluid pt-1 pb-3 px-0 position-relative">
    <div class="ratio ratio-16x9" style="height: calc(100vh - 150px);">
	<video muted autoplay loop class="object-fit-cover">
	    <source src="assets/images/home_video.mp4" type="video/mp4">
	</video>

	<!-- Overlay content - positioned bottom left -->
	<div class="position-absolute w-100 h-100">
	    <!-- Optional semi-transparent overlay for better text visibility -->
	    <div class="position-absolute top-0 start-0 w-100 h-100 bg-dark opacity-50"></div>
	    
	    <!-- Content positioned at bottom left -->
	    <div class="position-absolute bottom-0 start-0 p-5 mb-3 mx-md-5 mx-1 text-white">
		<h1 class="display-6 fw-bold mb-2">Strength.</h1>
		<a href="#" class="btn btn-primary px-5 py-1 rounded-0">Shop Now</a>
	    </div>
	</div>
    </div>
</div>

<div class="mission text-center">
    <h5 class="my-1">OUR MISSION</h5>
    <p class="my-2">Bring quality products to the fans and athletes of armwrestling.</p>
</div>

<?php include_once '../src/View/layouts/footer.php';?>