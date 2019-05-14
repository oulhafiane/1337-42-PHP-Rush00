<?php
include("config.php");
include("functions.php");
$link = mysqli_connect($host, $user, $pass, $db);
if (!$link)
	die('Connexion impossible: '.mysqli_connect_error());
if (isset($_SESSION['token']))
	logout_user($link, $_SESSION['token']);
$_SESSION['token'] = "";
$_SESSION['admin'] = "";
header("Location: index.php");
?>
