<?php
ini_set('display_error',1); 
include("config.php");
include("functions.php");
$link = mysqli_connect($host, $user, $pass, $db);
$login = "";
if (!$link)
	die('Connexion impossible: '.mysqli_connect_error());
if (isset($_POST['action']) && mysqli_real_escape_string($link, $_POST['action']) === 'login' && isset($_POST['login']) && isset($_POST['passwd']))
{
	$ret = auth($link, $_POST['login'], $_POST['passwd']);
	if ($ret == 2)
		header("Location: admin.php");
	else if ($ret == 1)
		header("Location: index.php");
	else
		header("Location: login.php");
}
if (isset($_POST['action']) && mysqli_real_escape_string($link, $_POST['action']) === 'register' && isset($_POST['login']) && isset($_POST['passwd']))
{
	register($link, $_POST['login'], $_POST['passwd']);
	header("Location: login.php");
}
else
{
	$title = "Connexion";
	$action = "login";
	if (isset($_GET['action']) && mysqli_real_escape_string($link, $_GET['action']) === "register")
	{
		$title = "Inscription";
		$action = "register";
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>login</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" media="screen" href="css/login.css" />
	<link href="https://fonts.googleapis.com/css?family=Cairo" rel="stylesheet">
</head>
<body>
	<div class="container col-12">
	<form class="col-6 form-l" method="POST" action="login.php">
		<div class="col-12 h-login">
			<br/>
			<label class="lb"><?=$title?></label>
		</div>
		<div class="col-12 h-login">
			<input class="input-l" type="text" name="login" placeholder=" username" autocomplete="off"/>
			<input class="input-l" type="password" name="passwd"  placeholder=" password"/>
			<input type="hidden" name="action" value="<?=$action?>">
			<div class="btn-ld">
<?php
	if ($title === "Connexion")
	{
?>
					<a href="login.php?action=register" class="l-r" >Inscription?</a>
<?php
	}
	else
	{
?>
					<a href="login.php" class="l-r" >Connexion?</a>
<?php
	}
?>
				<button class="btn-l" type="submit"> <?=$title?></button>
			</div>
		</div>
	</form>
	</div>
</body>
</html>
<?php
}
mysqli_close($link);
?>
