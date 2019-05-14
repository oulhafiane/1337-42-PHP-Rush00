<?php
include("config.php");
include("functions.php");
$link = mysqli_connect($host, $user, $pass, $db);
$login = "guest";
if (!$link)
	die('Connexion impossible: '.mysqli_connect_error());
if (isset($_SESSION['token']) && $_SESSION['token'] != null && $_SESSION['token'] != false && $_SESSION['token'] != '')
	$login = get_user($link, $_SESSION['token']);
if ($_SESSION['basket'] != "")
	$basket = $_SESSION['basket'];
elseif ($login !== "guest")
{
	$basket = ft_select_single($link, "SELECT id_basket FROM baskets WHERE login like '$login' ORDER BY date_basket DESC")['id_basket'];
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
if (!isset($_SESSION['purchased']) || $_SESSION['purchased'] != "true")
	header("Location: basket.php");
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
							<li><a href="panier.php">Panier</a></li>
						</ul>
					</nav>
			</div>
	</div>
	<div class="body-box2 col-12">
			<div class="box-product col-3">
				<div class="info-h">
					<form action="index.php">
						<button id="validate" class="btn-p">Merci pour votre achat dans notre magasin votre N° de command est : <?=$basket?><br />Cliquez ici pour revenir à l'accueil</button>
					</form>
				</div>
			</div>
	</div>
</body>
</html>
<?php
mysqli_close($link);
?>
