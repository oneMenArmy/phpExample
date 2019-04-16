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

		$dropBoxVal = trim($_POST['categ_drop_box']);
		$itemsForDelete = $_POST['data'];

		if ($dropBoxVal == 'all') {
			$productsSelected = $db->getItems("SELECT * FROM items ORDER BY id ASC");
		} else if ($dropBoxVal == 'dvd') {
			$productsSelected = $db->getItems("SELECT * FROM items WHERE type= ? ORDER BY id ASC", ['DVD']);
		} else if ($dropBoxVal == 'book') {
			$productsSelected = $db->getItems("SELECT * FROM items WHERE type= ? ORDER BY id ASC", ['BOOK']);
		} else if ($dropBoxVal == 'furniture') {
			$productsSelected = $db->getItems("SELECT * FROM items WHERE type= ? ORDER BY id ASC", ['FURNITURE']);
		} else if ($dropBoxVal == 'delete') {
			if (isset($itemsForDelete)) {
				foreach ($itemsForDelete as $id) {
					$db->deleteItem("DELETE FROM items WHERE id= ?", [$id]);
				}
			} else {
				$productsSelected = $db->getItems("SELECT * FROM items ORDER BY id ASC");
			}
		} else {
				$productsSelected = $db->getItems("SELECT * FROM items ORDER BY id ASC");
		}
	?>

	<header class="header-product-list">
		<div class="container">
			<h1>Product List</h1>
			<div class="delete-action-form">
				<form id="product_post_form" method="post">
					<select id="home_select_id" class="action-options" name="categ_drop_box">
						<option value="all">All products</option>
					  <option value="dvd">DVD-disc</option>
					  <option value="book">Book</option>
					  <option value="furniture">Furniture</option>
					  <option value="delete">Mass Delete Action</option>
					</select>
					<input id="ApplyBtn" class="button button-apply" type="submit" value="Apply"><br>
				</form>
			</div>
		</div>
	</header>

	<section class="item-list">	
		<div class="container">
				<div class="all-items grid">	
					<?php
						foreach ($productsSelected as $line) {
							echo "<div class='item grid'>";
								echo "<input class='check-dele' type='checkbox' id='$line[id]' name='delete_item'>";
								echo $line[sku]."<br>";
								echo $line[name]."<br>";
								echo $line[price]." $<br>";
								if ($line[type] == "DVD") {
									echo "Size: ".$line[prop]." MB";
								} else if($line[type] == "FURNITURE") {
									echo "Weight: ".$line[prop]." KG";
								} else {
									echo "Dimension: ".$line[prop];
								}
							echo "</div>";
						}
					?>
				</div>
				<a class="button" href="/product_add.php">Add New Product</a>.
		</div>
	</section>

	<script src="js/scripts.min.js"></script>

</body>
</html>
