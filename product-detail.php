<?php include 'header.php'; ?>
<?php include 'db.php'; ?>
<div class="container">
	<div class="product-detail">
		<div class="row">
			<div class="col-lg-4 col-md-4 col-sm-4">
				<div class="product-image">
					<?php
					// Get the product ID from the URL
					$productID = isset($_GET['id']) ? $_GET['id'] : 0;

					// Fetch product details using the product ID
					$query = "SELECT * FROM products WHERE product_id = $productID";
					$result = mysqli_query($con, $query);

					if ($result && $row = mysqli_fetch_assoc($result)) {
						$statusClass = ($row['status'] == 'Out of Stock') ? 'out-of-stock' : 'stock';
						echo '<img src="images/' . $row['image_url'] . '" class="img-responsive"/>';
					}
					?>
				</div>
			</div>
			<div class="col-lg-8 col-md-8 col-sm-8">
				<div class="product-content">
					<?php
					if ($result && $row) {
						$_SESSION['product_name'] = $row['product_name'];
						$_SESSION['price'] = $row['price'];
						$_SESSION['Quantity'] = $row['quantity'];
						echo '<h2 class="product-title">' . $row['product_name'] . '</h2>';
						echo '<h5 class="price">' . $row['price'] . '</h5>';
						echo '<p class="' . $statusClass . '">' . $row['status'] . '</p>';
						echo '<p class="summary">' . $row['product_description'] . '</p>';
						echo '<form action="manage_cart.php" method="post">';
						echo '<input type="hidden" name="product_id" value="' . $row['product_id'] . '">';
						echo '<input type="hidden" name="product_name" value="' . $row['product_name'] . '">';
						echo '<input type="hidden" name="product_price" value="' . $row['price'] . '">';
						echo '<button type="submit" class="btn btn-danger" name="Add_To_Cart">
           				 	<i class="fa fa-shopping-cart"></i> Add to Cart
        					</button>
     						 </form>';
						if ($_SESSION['Quantity'] == 0) {
							echo '<script>"Error.. Item not available"</script>';
						} else {
							echo '<script>"Item added to cart"</script>';
						}
					} else {
						echo '<p>No product found</p>';
					}
					?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include 'footer.php'; ?>