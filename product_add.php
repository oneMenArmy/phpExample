<?php
	require_once "Database.php"
?>

<!DOCTYPE html>
<html lang="ru">

<head>

	<meta charset="utf-8">
	<!-- <base href="/"> -->

	<title>OptimizedHTML 4</title>
	<meta name="description" content="">

	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
	
	<!-- Template Basic Images Start -->
	<meta property="og:image" content="path/to/image.jpg">
	<link rel="icon" href="img/favicon/favicon.ico">
	<link rel="apple-touch-icon" sizes="180x180" href="img/favicon/apple-touch-icon-180x180.png">
	<!-- Template Basic Images End -->
	
	<!-- Custom Browsers Color Start -->
	<meta name="theme-color" content="#000">
	<!-- Custom Browsers Color End -->

	<link rel="stylesheet" href="css/main.min.css">

</head>

<body>

	<?php
		$db = new Database();

		if (count($_POST) > 0) {
			$sku = trim($_POST['sku']);
			$name = trim($_POST['name']);
			$price = trim($_POST['price']);

			$size = trim($_POST['prodSize']);
			$height = trim($_POST['prodHeight']);
			$width = trim($_POST['prodWidth']);
			$length = trim($_POST['prodLength']);
			$weight = trim($_POST['prodWeight']);

			$emptyProdParam = false;

			//Check prod param valid...
			if (!empty($size)) { 
				if ($size < 0) {
					$emptyProdParam = true;
				} else {
					$productProp = $size;
					$type = 'DVD'; //Save Type for prod sort in home page
				}
			} else if (!empty($weight)) {
				if ($weight < 0) {
					$emptyProdParam = true;
				} else {
					$productProp = $weight;
					$type = 'FURNITURE';
				}
			}
			else if (!empty($height) && !empty($width) && !empty($length)) {
				if ($height < 0 || $width < 0 || $length < 0) {
					$emptyProdParam = true;
				} else {
					$productProp = $height.'x'.$width.'x'.$length;
					$type = 'BOOK';
				}
			} else {
				$emptyProdParam = true;
			}

			/**
				'' EKRANATION with / FROM SQL INJECTION
			**/
			$skuArr = ['sku'   => $sku];
			$skuExists= $db->getItem("SELECT * FROM items WHERE sku= ?", [$skuArr['sku']]);

			if (empty($sku) || empty($name) || empty($price)) {
				$errorMessage = "One of the required fields is empty.";
			} else if (!empty($skuExists)) {
				$errorMessage = "Such SKU alredy exist, try one more time.";
			} else if ($price < 0) {
				$errorMessage = "Low price.";
			} else if ($emptyProdParam == true) {
				$errorMessage = "Incorrect product parameters.";
			} else {
				/**
					'' EKRANATION with / FROM SQL INJECTION
				**/
				$valArr = ['sku'   => $sku,
									 'name'  => $name,
									 'price' => $price,
									 'type'  => $type,
									 'prop'  => $productProp];

				$db->saveItem("INSERT INTO items(sku, name, price, type, prop) VALUE(?, ?, ?, ?, ?)", [$valArr['sku'], $valArr['name'], $valArr['price'], $valArr['type'], $valArr['prop']]);

				$sku = '';
				$name = '';
				$price = '';
				$errorMessage = "";
			}
		}
	?>

	<header class="header-product-list">
		<div class="container">
			<h1>Product Add</h1>
		</div>
	</header>

	<section class="product-form">	
		<div class="add-form container">	
			<form id="product_post_form" method="post">
				<div>SKU <input class="product-info" type="text" name="sku" value="<?php echo $sku;?>"></div><br>
				<div>Name<input class="product-info" type="text" name="name" value="<?php echo $name;?>"></div><br>
				<div>Price<input class="product-info" type="number" step="0.01" name="price" value="<?php echo $price;?>"></div><br>
				<span>Select Product Type:</span>
				<select id="select-prod-attr">
					<option disabled selected></option>
				  <option value="dvd">DVD-disc</option>
				  <option value="book">Book</option>
				  <option value="furniture">Furniture</option>
				</select><br>
				<input class="button button-add" type="submit" value="Save"><br>
				<div id="prod-opt">
					
				</div>
			</form>
			<?php
				echo $errorMessage."<br>";
			?>
			<a class="button" href="index.php">Home Page</a>.
		</div>
	</section>

	<script src="js/scripts.min.js"></script>

</body>
</html>
