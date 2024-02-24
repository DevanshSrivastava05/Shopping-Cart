<?php include 'header.php'; ?>
<?php include 'db.php'; ?>

<?php
function test_input($data)
{
	$host = "localhost";
	$username = "root";
	$password = "";
	$student = "shopping-cart";
	$con = mysqli_connect($host, $username, $password, $student);
	if ($con == False) {
		die("ERROR: Could not connect. "
			. mysqli_connect_error());
	}
	$data = trim($data);
	$data = stripcslashes($data);
	$data = mysqli_real_escape_string($con, $data);
	$data = htmlspecialchars($data);
	return $data;
}
$first_name_err = $last_name_err =  $Email_err  = $Mobile_num_err  = $Address_err = $City_err = $Zip_err = $State_err = $Country_err = $cardNumberErr = $expiryMonthErr = $expiryYearErr = $cvcCodeErr =  "";
$first_name = $last_name = $Email = $Mobile_num = $Address = $City = $Zip = $State = $Country = $cardNumber = $expiryMonth = $expiryYear = $cvcCode = "";



if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['make-purchase'])) {


	if (empty($_POST["first_name"])) {
		$first_name_err = "Please enter your first name";
	} else {
		$first_name = test_input($_POST["first_name"]);
		if (!preg_match('/^[a-zA-Z\s]+$/', $first_name)) {
			$first_name_err = "First Name should only contains Alphabets";
		}
	}

	if (empty($_POST["last_name"])) {
		$last_name_err = "Please enter your last name";
	} else {
		$last_name = test_input($_POST["last_name"]);
		if (!preg_match('/^[a-zA-Z\s]+$/', $last_name)) {
			$last_name_err = "Last Name should only contains Alphabets";
		}
	}
	if (empty($_POST["email"])) {
		$Email_err = "Please enter your email address";
	} else {
		$Email = test_input($_POST["email"]);
		if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
			$Email_err = "Invalid email format";
		}
	}

	if (empty($_POST["contact_no"])) {
		$Mobile_num_err = "Please enter your mobile number";
	} else {
		$Mobile_num = test_input($_POST["contact_no"]);
		if (!preg_match('/^\d{10}$/', $Mobile_num)) {
			$Mobile_num_err = "Please enter a valid mobile number";
		}
	}
	if (empty($_POST["address_line_1"])) {
		$Address_err = "Please enter your address";
	} else {
		$Address = test_input($_POST["address_line_1"]);
	}

	if (empty($_POST["city"])) {
		$City_err = "Please enter your city name";
	} else {
		$City = test_input($_POST["city"]);
		if (!preg_match('/^[a-zA-Z\s]+$/', $City)) {
			$City_error = "City name should only contains Alphabets";
		}
	}

	if (empty($_POST["pincode"])) {
		$Zip_err = "Please enter your zipcode";
	} else {
		$Zip = test_input($_POST["pincode"]);
		if (!preg_match('/^\d{6}$/', $Zip)) {
			$Zip_err = "Please enter valid Pincode";
		}
	}

	if (empty($_POST["state"])) {
		$State_err = "Please enter your state";
	} else {
		$State = test_input($_POST["state"]);
		if (!preg_match('/^[a-zA-Z\s]+$/', $State)) {
			$State_err = "State name should only contains Alphabets";
		}
	}

	if (empty($_POST["country"])) {
		$Country_err = "Please select your country";
	} else {
		$Country = test_input($_POST["country"]);
	}
}
if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
	foreach ($_SESSION['cart'] as $item) {
		$product_details[] = array(
			'product_id' => $item['Product_id'],
			'product_name' => $item['Product_name'],
			'product_quantity' => $item['Quantity'],
			'product_price_per_item' => $item['Price']
		);
	}
} else {
	// If the cart is empty, set $success to false
	$success = false;
	echo "Cart is empty!";
}

// Store the product details in the session
$_SESSION['product_details'] = $product_details;

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['make-purchase']) && empty($first_name_err) && empty($last_name_err) && empty($Email_err) && empty($Mobile_num_err) && empty($Address_err) && empty($City_err) && empty($Zip_err) && empty($State_err) && empty($Country_err)) {
	$first_name =  $_REQUEST['first_name'];
	$last_name = $_REQUEST['last_name'];
	$Email = $_REQUEST['email'];
	$Mobile_num = $_REQUEST['contact_no'];
	$Address = $_REQUEST['address_line_1'];
	$City = $_REQUEST['city'];
	$Zip = $_REQUEST['pincode'];
	$State = $_REQUEST['state'];
	$Country = $_REQUEST['country'];

	// Initialize a variable to track the success of both operations
	$success = true;


	// Initialize an array to store product details
	$product_details = array();

	if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
		foreach ($_SESSION['cart'] as $item) {
			$product_details[] = array(
				'product_id' => $item['Product_id'],
				'product_name' => $item['Product_name'],
				'product_quantity' => $item['Quantity'],
				'product_price_per_item' => $item['Price']
			);
		}
	} else {
		// If the cart is empty, set $success to false
		$success = false;
		echo "Cart is empty!";
	}

	// Store the product details in the session
	$_SESSION['product_details'] = $product_details;


	// If both operations were successful, display the success message


}

$selectedPaymentMethod = isset($_POST['payment_method']) ? $_POST['payment_method'] : '';
$isAuthorizeNet = $selectedPaymentMethod === 'authorize_net';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['make-purchase']) && $isAuthorizeNet) {
	if (empty($_POST["first_name"])) {
		$first_name_err = "Please enter your first name";
	} else {
		$first_name = test_input($_POST["first_name"]);
		if (!preg_match('/^[a-zA-Z\s]+$/', $first_name)) {
			$first_name_err = "First Name should only contains Alphabets";
		}
	}

	if (empty($_POST["last_name"])) {
		$last_name_err = "Please enter your last name";
	} else {
		$last_name = test_input($_POST["last_name"]);
		if (!preg_match('/^[a-zA-Z\s]+$/', $last_name)) {
			$last_name_err = "Last Name should only contains Alphabets";
		}
	}
	if (empty($_POST["email"])) {
		$Email_err = "Please enter your email address";
	} else {
		$Email = test_input($_POST["email"]);
		if (!filter_var($Email, FILTER_VALIDATE_EMAIL)) {
			$Email_err = "Invalid email format";
		}
	}

	if (empty($_POST["contact_no"])) {
		$Mobile_num_err = "Please enter your mobile number";
	} else {
		$Mobile_num = test_input($_POST["contact_no"]);
		if (!preg_match('/^\d{10}$/', $Mobile_num)) {
			$Mobile_num_err = "Please enter a valid mobile number";
		}
	}
	if (empty($_POST["address_line_1"])) {
		$Address_err = "Please enter your address";
	} else {
		$Address = test_input($_POST["address_line_1"]);
	}

	if (empty($_POST["city"])) {
		$City_err = "Please enter your city name";
	} else {
		$City = test_input($_POST["city"]);
		if (!preg_match('/^[a-zA-Z\s]+$/', $City)) {
			$City_error = "City name should only contains Alphabets";
		}
	}

	if (empty($_POST["pincode"])) {
		$Zip_err = "Please enter your zipcode";
	} else {
		$Zip = test_input($_POST["pincode"]);
		if (!preg_match('/^\d{6}$/', $Zip)) {
			$Zip_err = "Please enter valid Pincode";
		}
	}

	if (empty($_POST["state"])) {
		$State_err = "Please enter your state";
	} else {
		$State = test_input($_POST["state"]);
		if (!preg_match('/^[a-zA-Z\s]+$/', $State)) {
			$State_err = "State name should only contains Alphabets";
		}
	}

	if (empty($_POST["country"])) {
		$Country_err = "Please select your country";
	} else {
		$Country = test_input($_POST["country"]);
	}

	// Store and validate Card Number
	$cardNumber = isset($_POST['card_number']) ? test_input($_POST['card_number']) : '';
	if (empty($cardNumber)) {
		$cardNumberErr = 'Please enter card number';
	} elseif (!preg_match('/^[0-9]{16}$/', $cardNumber)) {
		$cardNumberErr = 'Invalid card number format';
	}

	// Store and validate Expiry Month
	$expiryMonth = isset($_POST['expiry_month']) ? test_input($_POST['expiry_month']) : '';
	if (empty($expiryMonth)) {
		$expiryMonthErr = 'Please enter expiry month';
	} elseif (!preg_match('/^(0[1-9]|1[0-2])$/', $expiryMonth)) {
		$expiryMonthErr = 'Invalid expiry month';
	}

	// Store and validate Expiry Year
	$expiryYear = isset($_POST['expiry_year']) ? test_input($_POST['expiry_year']) : '';
	if (empty($expiryYear)) {
		$expiryYearErr = 'Please enter expiry year';
	} elseif (!preg_match('/^[0-9]{4}$/', $expiryYear)) {
		$expiryYearErr = 'Invalid expiry year';
	}

	// Store and validate CVC Code
	$cvcCode = isset($_POST['cvc_code']) ? test_input($_POST['cvc_code']) : '';
	if (empty($cvcCode)) {
		$cvcCodeErr = 'Please enter CVC code';
	} elseif (!preg_match('/^[0-9]{3}$/', $cvcCode)) {
		$cvcCodeErr = 'Invalid CVC code';
	}
}
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['make-purchase']) && $isAuthorizeNet && empty($first_name_err) && empty($last_name_err) && empty($Email_err) && empty($Mobile_num_err) && empty($Address_err) && empty($City_err) && empty($Zip_err) && empty($State_err) && empty($Country_err) && empty($cardNumberErr) && empty($expiryMonthErr) && empty($expiryYearErr) && empty($cvcCodeErr)) {
	$first_name =  $_REQUEST['first_name'];
	$last_name = $_REQUEST['last_name'];
	$Email = $_REQUEST['email'];
	$Mobile_num = $_REQUEST['contact_no'];
	$Address = $_REQUEST['address_line_1'];
	$City = $_REQUEST['city'];
	$Zip = $_REQUEST['pincode'];
	$State = $_REQUEST['state'];
	$Country = $_REQUEST['country'];
	$cardNumber = $_REQUEST['card_number'];
	$expiryMonth = $_REQUEST['expiry_month'];
	$expiryYear = $_REQUEST['expiry_year'];
	$cvcCode = $_REQUEST['cvc_code'];
	// Initialize a variable to track the success of both operations
	$success = true;


	// Initialize an array to store product details
	$product_details = array();

	if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
		foreach ($_SESSION['cart'] as $item) {
			$product_details[] = array(
				'product_id' => $item['Product_id'],
				'product_name' => $item['Product_name'],
				'product_quantity' => $item['Quantity'],
				'product_price_per_item' => $item['Price']
			);
		}
	} else {
		// If the cart is empty, set $success to false
		$success = false;
		echo "Cart is empty!";
	}

	$_SESSION['product_details'] = $product_details;
}
?>
<div class="container">
	<div class="checkout">
		<h2 class="page-title">Checkout</h2>
		<form id="sample" method="post" action="process_payment.php">
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="form-group">
						<label>First Name <span class="color-danger">*</span></label>
						<input type="text" class="form-control" id="first_name" name="first_name" data-rule-firstname="true" value="<?php echo htmlspecialchars($first_name); ?>" />
						<span style="color: red"><?php echo $first_name_err; ?></span>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="form-group">
						<label>Last Name <span class="color-danger">*</span></label>
						<input type="text" class="form-control" id="last_name" name="last_name" data-rule-lastname="true" value="<?php echo htmlspecialchars($last_name); ?>" />
						<span style="color: red"><?php echo $last_name_err; ?></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="form-group">
						<label>Mobile Number <span class="color-danger">*</span></label>
						<input type="text" id="contact_no" name="contact_no" value="<?php echo htmlspecialchars($Mobile_num); ?>" data-rule-mobile="true" class="form-control" />
						<span style="color: red"><?php echo $Mobile_num_err; ?></span>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="form-group">
						<label>Email <span class="color-danger">*</span></label>
						<input type="text" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($Email); ?>" data-rule-email="true" />
						<span style="color: red"><?php echo $Email_err; ?></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="form-group">
						<label>Address <span class="color-danger">*</span></label>
						<textarea class="form-control" id="address_line1" name="address_line_1"><?php echo htmlspecialchars($Address); ?></textarea><span style="color: red"><?php echo $Address_err; ?></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="form-group">
						<label>City <span class="color-danger">*</span></label>
						<input type="text" name="city" id="city" class="form-control" value="<?php echo htmlspecialchars($City); ?>" data-rule-mandatory="true" />
						<span style="color: red"><?php echo $City_err; ?></span>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="form-group">
						<label>Zip Code<span class="color-danger">*</span></label>
						<input type="text" name="pincode" id="pincode" class="form-control" value="<?php echo htmlspecialchars($Zip); ?>" data-rule-pincode="true" />
						<span style="color: red"><?php echo $Zip_err; ?></span>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="form-group">
						<label>State <span class="color-danger">*</span></label>
						<input type="text" name="state" id="state" class="form-control" value="<?php echo htmlspecialchars($State); ?>" data-rule-mandatory="true" />
						<span style="color: red"><?php echo $State_err; ?></span>
					</div>
				</div>
				<div class="col-lg-6 col-md-6 col-sm-6">
					<div class="form-group">
						<label>Country <span class="color-danger">*</span></label>
						<select name="country" class="form-control">

							<option value="" <?php echo (empty($Country)) ? 'selected' : ''; ?>>(Please select a country)</option>

							<?php
							// Display the list of countries
							$sql_countries = "SELECT * FROM country  ORDER BY country_id";
							$result_countries = mysqli_query($con, $sql_countries);

							if (!$result_countries) {
								die("Error fetching countries: " . mysqli_error($con));
							}

							// Loop through each country and create an option element
							while ($row = mysqli_fetch_assoc($result_countries)) {
								$country_name = $row['country_name'];
								$selected = ($Country == $country_name) ? "selected" : "";
								echo '<option value="' . $country_name . '" ' . $selected . '>' . $country_name . '</option>';
							}
							?>
						</select>
						<span style="color: red"><?php echo $Country_err; ?></span>

					</div>
				</div>

			</div>
	</div>
	<div class="row">
		<div class="col-lg-3 col-md-3 col-sm-3">
			<div class="form-group">
				<label for="payment_method">Select Payment Method*</label>
				<select name="payment_method" class="form-control">
					<option value="paypal" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'paypal') ? 'selected' : ''; ?>>PayPal</option>
					<option value="authorize_net" <?php echo (isset($_POST['payment_method']) && $_POST['payment_method'] == 'authorize_net') ? 'selected' : ''; ?>>Authorize.Net</option>
				</select>
			</div>
		</div>
	</div>
	<span style="color: red;">If Payment Method is Authorize.Net below fields needs to be filled also.</span>
	<div class="row">
		<div class="col-lg-6 col-md-6 col-sm-6 <?php echo $isAuthorizeNet  ?>">
			<div class="form-group">
				<label>Card Number <span class="color-danger">*</span></label>
				<input type="text" name="card_number" class="form-control" placeholder="Enter card number 1111 1111 1111 1111" maxlength="16" value="<?php echo htmlspecialchars($cardNumber); ?>" />
				<span style="color: red;"><?php echo $cardNumberErr; ?></span>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 <?php echo $isAuthorizeNet ?>">
			<div class="form-group">
				<label>Expiry Month <span class="color-danger">*</span></label>
				<input type="text" name="expiry_month" class="form-control" placeholder="MM" maxlength="2" value="<?php echo htmlspecialchars($expiryMonth); ?>" />
				<span style="color: red;"><?php echo $expiryMonthErr; ?></span>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 <?php echo $isAuthorizeNet ?>">
			<div class="form-group">
				<label>Expiry Year <span class="color-danger">*</span></label>
				<input type="text" name="expiry_year" class="form-control" placeholder="YYYY" maxlength="4" value="<?php echo htmlspecialchars($expiryYear); ?>" />
				<span style="color: red;"><?php echo $expiryYearErr; ?></span>
			</div>
		</div>
		<div class="col-lg-3 col-md-3 col-sm-3 <?php echo $isAuthorizeNet ?>">
			<div class="form-group">
				<label>CVC Code <span class="color-danger">*</span></label>
				<input type="text" name="cvc_code" class="form-control" placeholder="Enter CVC code" maxlength="3" value="<?php echo htmlspecialchars($cvcCode); ?>" />
				<span style="color: red;"><?php echo $cvcCodeErr; ?></span>
			</div>
		</div>
	</div>


	<!-- Example product details fields (modify as needed) -->
	<?php
	if (isset($_SESSION['product_details']) && !empty($_SESSION['product_details'])) {
		foreach ($_SESSION['product_details'] as $product) {
	?>
			<input type="hidden" name="product_id[]" value="<?php echo $product['product_id']; ?>">
			<input type="hidden" name="product_name[]" value="<?php echo $product['product_name']; ?>">
			<input type="hidden" name="product_quantity[]" value="<?php echo $product['product_quantity']; ?>">
			<input type="hidden" name="product_price_per_item[]" value="<?php echo $product['product_price_per_item']; ?>">
			<!-- Add other hidden fields as needed for each product -->
	<?php
		}
	}
	?>

	<!-- Total amount and other fields -->
	<input type="hidden" name="product_total_amount" value="<?php echo $_SESSION['total']; ?>">
	<!-- Add other hidden fields as needed -->

	<!-- Submit button -->
	<input type="submit" class="btn btn-blue" name="make-purchase" value="Complete Purchase">

	</form>
</div>

<?php include 'footer.php'; ?>