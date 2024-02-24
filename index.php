<?php include 'header.php'; ?>
<?php include 'db.php'; ?>
<div class="container">
	<div class="product">
		<h2 class="page-title">Products</h2>
		<div class="searchbar">
			<form action="index.php" method="GET">
				<input type="text" name="query" placeholder="Search products..." value="<?php echo isset($_GET['query']) ? $_GET['query'] : ''; ?>">
				<button type="submit" class="search-icon">&#128269;</button>
			</form>
		</div>
		<div class="row">
			<?php
			$searchQuery = isset($_GET['query']) ? $_GET['query'] : '';
			if (isset($_GET['query'])) {

				$cleanedQuery = strtolower(str_replace(' ', '', $searchQuery));

				// Use the cleaned query for matching
				$result = mysqli_query($con, "SELECT * FROM products WHERE REPLACE(LOWER(product_name), ' ', '') LIKE '%$cleanedQuery%'");
			} else {
				$result = mysqli_query($con, "SELECT * FROM products");
			}

			if ($result) {
				while ($row = mysqli_fetch_assoc($result)) {
					$statusClass = ($row['status'] == 'Out of Stock') ? 'out-of-stock' : 'stock';
					echo '<div class="col-lg-4 col-md-4 col-sm-4">
                    <div class="product-item">
                        <img src="images/' . $row['image_url'] . '" class="img-responsive" />
                        <h4>' . $row['product_name'] . '</h4>
                        <h5 class="price">' . $row['price'] . '</h5>
                        <p class="' . $statusClass . '">' . $row['status'] . '</p>
                        <p><a href="product-detail.php?id=' . $row['product_id'] . '" class="btn btn-blue">Quick View</a></p>
                    </div>
                </div>';
				}
			}


			?>
		</div>
	</div>
</div>
<?php include 'footer.php'; ?>