<!doctype html>
<html lang="en" data-bs-theme="dark">
    <head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Armwrestling Shop</title>
	
	<!-- bootrstrap css -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
	<!-- bootstrap icons -->
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
	
	<!-- custom css -->
	<link rel="stylesheet" href="assets/css/overrides.css">
	
	<!-- fonts -->
	<!-- Optimize DNS and connection setup -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Inter:opsz@14..32&display=swap" rel="stylesheet">

    </head>
    <body class="d-flex flex-column min-vh-100">
	<nav class="navbar navbar-expand-md py-0">
	    <div class="container justify-content-between">
		
		<button class="navbar-toggler border border-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar">
		    <i class="bi bi-list"></i>
		</button>

		<a href="index.php" class="navbar-brand"> IP </a>

		<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasNavbar">
		    <div class="offcanvas-header">
			<button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
		    </div>
		    <div class="offcanvas-body py-2 justify-content-center">
			<ul class="navbar-nav">
			    <li class="nav-item px-2">
				<a href="index.php?target=product&action=clothing" class="nav-link"> Clothing </a>
			    </li>
			    <li class="nav-item px-2">
				<a href="index.php?target=product&action=equipment" class="nav-link"> Equipment </a>
			    </li>
			</ul>
		    </div>
		</div>

		
		<div class="d-flex">
		    <div class="search-icon p-2">
			<a href="#" class="nav-link">
			    <i class="bi bi-search"></i>
			</a>
		    </div>
		    <div class="account-icon p-2">
			<a href="index.php?target=user&action=profile" class="nav-link">
			    <i class="bi bi-person"></i>
			</a>
		    </div>
		    <div class="cart-icon p-2">
			<a href="#" class="nav-link">			
			    <i class="bi bi-bag"></i>
			</a>
		    </div>
		</div>
	    </div>
	</nav>

      

