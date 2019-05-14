<?php
session_start();
include("functions.php");
if(!isset($_SESSION['admin']) || $_SESSION['admin'] !== true)
	header("Location: index.php");
$link = mysqli_connect($host, $user, $pass, $db);
$loging = "";
if (!$link)
	die('Connexion impossible: '.mysqli_connect_error());
if (isset($_SESSION['token']) && $_SESSION['token'] != null && $_SESSION['token'] != false && $_SESSION['token'] != '')
	$login = get_user($link, $_SESSION['token']);
$action = "products";
if (isset($_GET['action']) && $_GET['action'] != "")
{
	$action = mysqli_real_escape_string($link, $_GET['action']);
	switch($action)
	{
	case "products":
		$tab = ft_select($link, "SELECT * FROM products");
		break;
	case "categories":
		$tab = ft_select($link, "SELECT * FROM categories");
		break;
	case "users":
		$tab = ft_select($link, "SELECT login, admin FROM users");
		break;
	case "orders":
		$tab = ft_select($link, "SELECT bs.id_basket, login, SUM(ps.price_product)'sum' FROM baskets bs JOIN basket_intermidiate bi ON bs.id_basket = bi.id_basket JOIN products ps ON ps.id_product = bi.id_product WHERE bs.purchased = 1 GROUP BY id_basket");
		break;
	case "delete":
		if (isset($_GET['w']) && isset($_GET['id']) && $_GET['id'] != "")
		{
			$w = mysqli_real_escape_string($link, $_GET['w']);
			$id = mysqli_real_escape_string($link, $_GET['id']);
			switch($w)
			{
				case "products":
					delete_product($link, $id);
					break;
				case "categories":
					delete_category($link, $id);
					break;
				case "users":
					delete_user($link, $id);
					break;
				case "orders":
					delete_order($link, $id);
					break;
			}
			Header("Location: admin.php?action=$w");
			$tab = ft_select($link, "SELECT * FROM products");
			$action = $w;
		}
		break;
	default:
		$tab = ft_select($link, "SELECT * FROM products");
		break;
	}
}
else
	$tab = ft_select($link, "SELECT * FROM products");
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Administrateur</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" media="screen" href="css/admin.css" />
</head>

<body>
	<div class="container">
		<div class="c-shild">
			<div class="dash col-4">
				<div class="img-h">
					Rush00
				</div>
				<div class="nav">
					<ul class="nav-dash">
						<li> <a href="?action=products">Produits</a></li>
						<li> <a href="?action=categories">Categories</a></li>
						<li> <a href="?action=users">Utilisateurs</a></li>
						<li> <a href="?action=orders">Commandes</a></li>
						<li> <a href="logout.php">Se déconnecter</a></li>
					</ul>
				</div>
			</div>
				<div class="c-dash-fixer col-4">
				</div>
				<div class="cotent-holder col-8">
				<table class="table-dash">
<?php
switch($action)
{
case "products":
	$v1 = "name_product";
	$v4 = "picture_product";
	$v2 = "price_product";
?>
						<tr class="head">
							<td>Nom Produit</td>
							<td>Image</td>
							<td>Prix</td>
							<td colspan="2">Actions</td>
						</tr>
<?php
	break;
case "categories":
	$v1 = "id_category";
	$v3 = "name_category";
?>
						<tr class="head">
							<td>Id Categorie</td>
							<td>Nom Categorie</td>
							<td colspan="2">Actions</td>
						</tr>
<?php
	break;
case "users":
	$v1 = "login";
	$v3 = "admin"
?>
						<tr class="head">
							<td>Nickname Utilisateur</td>
							<td>Type</td>
							<td colspan="2">Actions</td>
						</tr>
<?php
	break;
case "orders":
	$v1 = "id_basket";
	$v3 = "login";
	$v2 = "sum";
?>
						<tr class="head">
							<td>N° Commande</td>
							<td>Utilisateur</td>
							<td>Total</td>
							<td colspan="2">Actions</td>
						</tr>
<?php
	break;
}
?>
<?php
while ($value = mysqli_fetch_array($tab))
{
?>
	<tr>
		<td>
			<?=$value[$v1]?>
		</td>
<?php
	if (isset($value[$v4]) && $value[$v4] != NULL && $value[$v4] != "")
	{
?>
		<td>
			<img style='heigth:50px;width:100px' src='<?=$value[$v4]?>'/>
		</td>
<?php
	}
?>
<?php
	if (isset($value[$v2]) && $value[$v2] != NULL && $value[$v2] != "")
	{
?>
		<td>
			<?=$value[$v2]?> Dh
		</td>
<?php
	}
?>
<?php
	if (isset($value[$v3]) && $value[$v3] != NULL && $value[$v3] != "")
	{
?>
		<td>
			<?=$value[$v3]?>
		</td>
<?php
	}
?>
		<td>
			<button class='btn-d' type='button'><a href='edit.php?w=<?=$action?>&id=<?=$value[0]?>'>Modifier</a></button>
		</td>
		<td>
			<button class='btn-d' type='button'><a href='admin.php?action=delete&w=<?=$action?>&id=<?=$value[0]?>'>Supprimer</a></button>
		</td>
	</tr>
<?php
}
?>
				</table>
				</div>
			</div>
		</div>
	</div>
<script src="js/admin.js">
</script>
</body>

</html>
