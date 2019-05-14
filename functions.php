<?php
session_start();
include("config.php");

function ft_select_single($link, $cmd)
{
	$result = mysqli_query($link, $cmd);
	$tab = mysqli_fetch_array($result);
	return ($tab);
}

function ft_update($link, $cmd)
{
	$result = mysqli_query($link, $cmd);
}

function ft_select($link, $cmd)
{
	return (mysqli_query($link, $cmd));
}

function register($link, $login, $passwd)
{
	ft_update($link, "INSERT INTO users VALUES('".mysqli_real_escape_string($link, $login)."', '".hash("whirlpool", mysqli_real_escape_string($link, $passwd))."', 0, '')");
}

function auth($link, $login, $passwd)
{
	$tab = ft_select_single($link, "SELECT * FROM users WHERE login LIKE '".mysqli_real_escape_string($link, $login)."'");
	if ($tab['passwd'] === hash("whirlpool", $passwd))
	{
		$token = hash("whirlpool", random_bytes(50));
		ft_update($link, "UPDATE users SET token = '$token' WHERE login LIKE '".mysqli_real_escape_string($link, $login)."'");
		if (isset($_SESSION['basket']) && $_SESSION['basket'] != NULL && $_SESSION['basket'] != FALSE && $_SESSION['basket'] != "")
			ft_update($link, "UPDATE baskets SET login = '".mysqli_real_escape_string($link, $login)."' WHERE id_basket = ".mysqli_real_escape_string($link, $_SESSION['basket']));
		$_SESSION['token'] = $token;
		if ($tab['admin'] == 1)
		{
			$_SESSION['admin'] = true;
			return (2);
		}
		else
			return (1);
	}
	else
	{
		ft_update($link, "UPDATE users SET token = '' WHERE login LIKE '".mysqli_real_escape_string($link, $login)."'");
		$_SESSION['token'] = $token;
		return (0);
	}
}

function get_user($link, $token)
{
	$tab = ft_select_single($link, "SELECT login FROM users WHERE token LIKE '".mysqli_real_escape_string($link, $token)."' LIMIT 1");
	return ($tab['login']);
}

function logout_user($link, $token)
{
	ft_update($link, "UPDATE users SET token = '' WHERE login LIKE '".mysqli_real_escape_string($link, $login)."'");
}

function get_products($link, $categorie, $start, $offset)
{
	if (isset($categorie) && ft_select_single($link, "SELECT EXISTS(SELECT * FROM categories WHERE name_category LIKE '".mysqli_real_escape_string($link, $categorie)."')'true'")['true'] === "1")
		$tab = ft_select($link, "SELECT p.id_product, p.name_product, p.price_product, p.picture_product FROM products p JOIN categories c ON p.id_category = c.id_category WHERE c.name_category LIKE '".mysqli_real_escape_string($link, $categorie)."' LIMIT $start, $offset");
	else
		$tab = ft_select($link, "SELECT p.id_product, p.name_product, p.price_product, p.picture_product FROM products p LIMIT $start, $offset");
	return ($tab);
}

function get_baskets($link, $id_basket)
{
		$tab = ft_select($link, "SELECT * FROM `basket_intermidiate` b JOIN baskets bs ON b.id_basket = bs.id_basket JOIN products p on b.id_product = p.id_product JOIN categories c on c.id_category = p.id_category WHERE bs.id_basket = ".mysqli_real_escape_string($link, $id_basket));
	return ($tab);
}

function update_basket($link, $id_basket, $id_product, $qty)
{
	$result = ft_select_single($link, "SELECT EXISTS(SELECT * FROM basket_intermidiate WHERE id_basket = ".mysqli_real_escape_string($link, $id_basket)." AND id_product = ".mysqli_real_escape_string($link, $id_product).")'true'")['true'];
	if ($result === "1")
		ft_update($link, "UPDATE basket_intermidiate SET qty = qty + ".mysqli_real_escape_string($link, $qty)." WHERE id_basket = ".mysqli_real_escape_string($link, $id_basket)." AND id_product = ".mysqli_real_escape_string($link, $id_product));
	else
		ft_update($link, "INSERT INTO basket_intermidiate VALUES(".mysqli_real_escape_string($link, $id_product).", ".mysqli_real_escape_string($link, $id_basket).", ".mysqli_real_escape_string($link, $qty).")");
}

function delete_basket($link, $basket, $id_product)
{
	ft_update($link, "DELETE FROM basket_intermidiate WHERE id_basket = ".mysqli_real_escape_string($link, $basket)." AND id_product = ".mysqli_real_escape_string($link, $id_product));
}

function validate_basket($link, $basket)
{
	ft_update($link, "UPDATE baskets SET purchased = 1 WHERE id_basket = ".mysqli_real_escape_string($link, $basket));
	$_SESSION['purchased'] = "true";
	header("Location: thankyou.php");
}

function check_basket($link, $basket)
{
	if (isset($basket) && $basket != NULL && $basket != FALSE && $basket != "")
		return (ft_select_single($link, "SELECT purchased from baskets where id_basket = ".mysqli_real_escape_string($link, $basket))['purchased']);
	else
		return (false);
}

function delete_product($link, $id)
{
	ft_update($link, "DELETE FROM products WHERE id_product = $id");
}

function delete_category($link, $id)
{
	ft_update($link, "DELETE FROM categories WHERE id_category = $id");
}

function delete_user($link, $id)
{
	ft_update($link, "DELETE FROM users WHERE login = $id");
}

function delete_order($link, $id)
{
	ft_update($link, "DELETE FROM baskets WHERE id_basket = $id");
}
?>
