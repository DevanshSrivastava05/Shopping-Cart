<?php
include 'header.php';
include 'db.php';

// Function to update the quantity in the session cart
function updateQuantity($product_id, $new_quantity)
{
	foreach ($_SESSION['cart'] as &$item) {
		if ($item['Product_id'] == $product_id) {
			$item['Quantity'] = $new_quantity;
			break; // Exit the loop once the product is found
		}
	}
}

// Check if the form is submitted for updating quantity
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['update_cart'])) {
	foreach ($_POST['quantity'] as $product_id => $new_quantity) {
		echo '<script>
                var quantity = parseInt("' . $new_quantity . '");
                if (isNaN(quantity) || quantity < 0) {
                    alert("Please enter a valid quantity (a non-negative number).");
                    event.preventDefault(); 
                }
            </script>';

		// Update the quantity
		updateQuantity($product_id, $new_quantity);
	}
}

if (isset($_POST['remove_from_cart'])) {
	$remove_product_id = $_POST['remove_product_id'];

	foreach ($_SESSION['cart'] as $key => $item) {
		if ($item['Product_id'] == $remove_product_id) {
			// Decrement the item quantity by 1
			$_SESSION['cart'][$key]['Quantity']--;

			// If the quantity becomes zero, remove the item from the cart
			if ($_SESSION['cart'][$key]['Quantity'] <= 0) {
				unset($_SESSION['cart'][$key]);
				echo "<script>alert('Item Removed from Cart'); window.location.href='cart.php'</script>";
			} else {
				// Re-index the array after modification
				$_SESSION['cart'] = array_values($_SESSION['cart']);
				echo "<script>alert('Quantity Decreased'); window.location.href='cart.php'</script>";
			}
		}
	}
}


print_r($_SESSION['cart']);

// Display the cart
?>
<div class="container">
	<div class="cart">
		<h2 class="page-title">Cart</h2>
		<form method="post" action="cart.php">
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th>Product Name</th>
							<th>Unit Price</th>
							<th>Quantity</th>
							<th>Item Total</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$errorDisplayed = false; // Flag to check if the error has been displayed
						$grandTotal = 0;

						if (isset($_SESSION['cart'])) {
							foreach ($_SESSION['cart'] as $key => $value) {
								$product_id = $value['Product_id'];
								$result = mysqli_query($con, "SELECT quantity FROM products WHERE product_id = $product_id");
								$row = mysqli_fetch_assoc($result);
								$max_quantity = $row['quantity'];

								// Check if cart quantity exceeds max quantity
								if ($value['Quantity'] > $max_quantity) {
									$_SESSION['cart'][$key]['Quantity'] = $max_quantity;
									$value['Quantity'] = $max_quantity;

									// Display error message only once
									if (!$errorDisplayed) {
										echo '<tr>' .
											'<td colspan="5" style="color: red;">Stock not available in desired Quantity.Adjusted quantities accordingly.</td>' .
											'</tr>';
										$errorDisplayed = true;
									}
								}

								$itemTotal = $value['Quantity'] * $value['Price'];
								$grandTotal += $itemTotal;

								echo '<tr>' .
									'<td>' . $value['Product_name'] . '</td>' .
									'<td>' . $value['Price'] . '</td>' .
									'<td>
        <div class="form-group">
            <form method="post" action="cart.php">
                <input type="number" name="quantity[' . $product_id . ']" class="form-control" value="' . $value['Quantity'] . '" min="0" max="' . $max_quantity . '"/>
                <button type="submit" name="update_cart" class="btn btn-warning">Update</button>
            </form>
        </div>
    </td>' .
									'<td>' . $itemTotal . '</td>' .
									'<td>
        <form method="post" action="cart.php">
            <input type="hidden" name="remove_product_id" value="' . $value['Product_id'] . '">
            <button type="submit" name="remove_from_cart" class="btn btn-danger">Remove</button>
        </form>
    </td>' .
									'</tr>';
							}
						}
						?>
					</tbody>
				</table>
				<div class="grand-total">
					Total: <?php echo $grandTotal;
							$_SESSION['total'] = $grandTotal; ?>

				</div>
			</div>
		</form>

		<div class="cart-footer">
			<a href="index.php" class="btn btn-blue">
				<i class="bi bi-arrow-down-left-square-fill"></i> Continue Shopping
			</a>

			<a href="checkout.php" class="btn btn-green">
				<i class="bi bi-play-fill"></i> Check Out
			</a>
		</div>
	</div>
</div>

<?php include 'footer.php'; ?>