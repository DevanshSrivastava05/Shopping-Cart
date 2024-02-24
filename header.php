<?php session_start(); ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<title>Shopping Cart</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@latest/font/bootstrap-icons.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha384-GLhlTQ8i6c6L1lDkEN6t9RSVmaiZ9bYQbEMaF+gP1I1EGg6Of5Fkwg8kRT66" crossorigin="anonymous">


	<link rel="stylesheet" href="css/font-awesome.min.css">
	<link rel="stylesheet" href="css/style.css">
	<style>

	</style>
</head>

<body>
	<header>
		<nav class="navbar navbar-expand-lg navbar-dark bg-light">
			<div class="container">
				<a class="navbar-brand" href="index.php">
					<img src="images/logo.png" alt="Logo" height="40">
				</a>
				<?php
				$count = 0;
				if (isset($_SESSION['cart'])) {
					$count = count($_SESSION['cart']);
				}
				?>
				<div class="collapse navbar-collapse" id="navbarNav">
					<ul class="navbar-nav ml-auto">
						<li class="nav-item active">
							<a class="nav-link" href="index.php" b>Home</a>
						</li>
						<li class="nav-item">
							<a class="nav-link cart-icon btn btn-success" href="cart.php">
								My Cart
								<i class="fa fa-shopping-cart"></i>
								<span class="cart-badge"><?php echo $count; ?></span>
							</a>
						</li>
					</ul>
				</div>
			</div>
		</nav>
	</header>




	<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
	<script src="https://www.paypal.com/sdk/js?client-id=ATJsDIHEFRUHt0KjeHdlM9-I0aXOIZOCpZQB7WN8syHCewVTuJJZAZaDE9PBooe5Mbcd56pPfl69ocfi"></script>
	<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"></script>
	<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8znzLrV6HdZLfnz4KMqQa0ImiRpaKxZ6L4uFpH6xgBYY6p+EG5HJlNr8z7N" crossorigin="anonymous"></script>
</body>
</body>

</html>