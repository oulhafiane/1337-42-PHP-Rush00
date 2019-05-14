<?php
include("config.php");
include("functions.php");
$link = mysqli_connect($host, $user, $pass, $db);
$login = "guest";
if (!$link)
	die('Connexion impossible: '.mysqli_connect_error());
if (isset($_SESSION['token']) && $_SESSION['token'] != null && $_SESSION['token'] != false && $_SESSION['token'] != '')
	$login = get_user($link, $_SESSION['token']);
if (isset($_GET['categorie']) && ft_select_single($link, "SELECT EXISTS(SELECT * FROM categories WHERE name_category LIKE '".mysqli_real_escape_string($link, $_GET['categorie'])."')'true'")['true'] === "1")
	$count = ft_select_single($link, "SELECT count(*) AS count FROM products p JOIN categories c ON p.id_category = c.id_category WHERE c.name_category LIKE '".mysqli_real_escape_string($link, $_GET['categorie'])."'")['count'];
else
	$count = ft_select_single($link, "SELECT count(*) AS count FROM products")['count'];
if (is_numeric($count))
{
	$modulo = $count % 6;
	$count = intval($count / 6);
	if ($modulo > 0)
		$count++;
}
else
	$count = 1;
if (!isset($_SESSION['step']) || !isset($_GET['page']))
{
	$_SESSION['step'] = 0;
	$active = 1;
	$start = 0;
	$tmp_page = 1;
}
else
{
	$tmp_page = mysqli_real_escape_string($link, $_GET['page']);
	if (is_numeric($tmp_page))
		$active = ($_SESSION['step'] + $tmp_page) % ($count + 1);
	else
	{
		$tmp_page = 1;
		$active = 1;
	}
	if ($_SESSION['step'] < 0)
		$_SESSION['step'] = 0;
	else if ($_SESSION['step'] > $count)
		$_SESSION['step'] = $count;
	$start = ($_SESSION['step'] + $tmp_page - 1) * 6;

}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Rush00</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" media="screen" href="css/index.css" />
	<link href="https://fonts.googleapis.com/css?family=Cairo" rel="stylesheet">
</head>
<body>
	<div class="container col-12">
			<div class="header">
					<nav class="navbar">
						<ul>
							<li><a href="index.php">Rush00</a></li>
<?php
if (isset($login) && $login != null && $login != false && $login != "guest")
{
?>
							<li><a href="logout.php">Déconnexion de <?=$login?></a></li>
<?php
}
else
{
?>
							<li><a href="login.php">Connexion</a></li>
							<li><a href="login.php?action=register">Inscription</a></li>
<?php
}
?>
							<div class="subnav">
								<button class="subnavbtn">Categorie</button>
								<div class="subnav-content">
									<a href="index.php?page=1&categorie=">Tous</a>
<?php
$categories = ft_select($link, "SELECT name_category FROM categories");
while ($category = mysqli_fetch_array($categories))
{
?>
									<a href="index.php?page=1&categorie=<?=$category['name_category']?>"><?=$category['name_category']?></a>
<?php
}
?>
								</div>
							</div>
							<li><a href="basket.php">Panier</a></li>
						</ul>
					</nav>
			</div>
	</div>
	<div class="body-box2 col-12">
<?php
$products = get_products($link, $_GET['categorie'], $start, 6);	
while ($product = mysqli_fetch_array($products))
{
?>
			<div class="box-product col-3">
				<div class="img-holder">
					<img class="img-p" src="<?=$product['picture_product']?>"/>
					<span class="info-h title"><?=$product['name_product']?></span>
				</div>
				<form class="info-h" action="basket.php" method="post">
					<label class="lab-p"><?=$product['price_product']?> DH</label>
					<input type="hidden" name="id_product" value="<?=$product['id_product']?>">
					<input class="qts-p" type="number" name="qty" value="1" min="1" max="99">
					<button class="btn-p">Ajouter au panier</button>
				</form>
			</div>
<?php
}
?>
	</div>
	<div class="pagination">
		<a href="index.php?page=1&categorie=<?=$_GET['categorie']?>">&laquo;</a>
<?php
for($i = 1; $i <= $count ; $i++)
{
?>
		<a class="<?=$active - $_SESSION['step'] == $i ? "active" : ""?>" href="index.php?page=<?=$i?>&categorie=<?=$_GET['categorie']?>"><?=$_SESSION['step'] + $i?></a>
<?php
}
?>
		<a href="index.php?page=<?=$count?>&categorie=<?=$_GET['categorie']?>">&raquo;</a>
	</div>
</body>
</html>
<?php
	mysqli_close($link);
?>
