<?php
include("config.php");
include("functions.php");
$link = mysqli_connect($host, $user, $pass, $db);
$login = "guest";
if (!$link)
	die('Connexion impossible: '.mysqli_connect_error());
if (isset($_SESSION['token']) && $_SESSION['token'] != null && $_SESSION['token'] != false && $_SESSION['token'] != '')
	$login = get_user($link, $_SESSION['token']);
if ($_SESSION['basket'] != "" && check_basket($link, $_SESSION['basket']) == "0")
	$basket = $_SESSION['basket'];
elseif ($login !== "guest")
{
	$basket = ft_select_single($link, "SELECT id_basket FROM baskets WHERE login like '$login' AND purchased = 0 ORDER BY date_basket DESC")['id_basket'];
	if (!isset($basket) || $basket == "")	
	{
		ft_update($link, "INSERT INTO baskets(login) VALUE('$login')");
		$basket = ft_select_single($link, "SELECT id_basket FROM baskets WHERE login like '$login' AND purchased = 0 ORDER BY date_basket DESC LIMIT 1")['id_basket'];
	}
}
else
{
	ft_update($link, "INSERT INTO baskets(login) VALUE('guest')");
	$basket = ft_select_single($link, "SELECT id_basket FROM baskets WHERE login like 'guest' AND purchased = 0 ORDER BY date_basket DESC LIMIT 1")['id_basket'];
	$_SESSION['basket'] = $basket;
}
if (isset($_POST['id_product']) && isset($_POST['qty']) && $basket != "")
	update_basket($link, $basket, $_POST['id_product'], $_POST['qty']);
else if (isset($_POST['id_product']) && isset($_POST['action']) && $_POST['action'] === "delete" && $basket != "")
	delete_basket($link, $basket, $_POST['id_product']);
else if (isset($_POST['action']) && $_POST['action'] == "validate" && isset($login) && $login != NULL && $login != false && $login != "guest")
	validate_basket($link, $basket);
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Panier</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" media="screen" href="css/index.css" />
	<link rel="stylesheet" type="text/css" media="screen" href="css/basket.css" />
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
		<div class="cotent-holder col-10">
					<table class="table-dash">
						<tr class="head">
							<th>
								Désignation
							</th>
							<th>
								Catégorie
							</th>
							<th>
								Prix
							</th>
							<th>
								Quantité
							</th>
							<th>
								Actions
							</th>
						</tr>
<?php
$total = 0;
$basket_intermidiate = get_baskets($link, $basket);
if (isset($basket_intermidiate) && $basket_intermidiate != NULL && $basket_intermidiate != "")
{
	while ($bs = mysqli_fetch_array($basket_intermidiate))
	{
		$total += ($bs['price_product'] * $bs['qty']);
?>
						<tr>
							<td>
								<?=$bs['name_product']?> 
							</td>
							<td>
								<?=$bs['name_category']?> 
							</td>
							<td>
								<?=$bs['price_product']?> DH
							</td>
							<td>
								<?=$bs['qty']?>	
							</td>
							<td>
								<form action="basket.php" method="post">
									<input type="hidden" name="action" value="delete">
									<input type="hidden" name="id_product" value="<?=$bs['id_product']?>">
									<button class="btn-d" type="submit">Supprimer</button>
								</form>
							</td>
						</tr>
<?php
	}
}
?>
						<tr>
							<td id="right" colspan="4">
								Total :
							</td>
							<td>
								<?=$total?> DH
							</td>
						</tr>
					</table>
				</div>
			<div class="box-product col-3">
				<div class="info-h">
<?php
if (isset($login) && $login != NULL && $login != false && $login != "guest")
{
?>
					<form action="basket.php" method="post">
						<input type="hidden" name="action" value="validate">
						<button id="validate" class="btn-p">Valider la commande</button>
					</form>
<?php
}
else
{
?>
					<form action="login.php" method="post">
						<input type="hidden" name="action" value="login">
						<button id="validate" class="btn-p">Connexion</button>
					</form>
<?php
}
?>
				</div>
			</div>
</body>
</html>
<?php
mysqli_close($link);
?>
