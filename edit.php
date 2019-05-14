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
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset="utf-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<title>Page Title</title>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="stylesheet" type="text/css" media="screen" href="css/edit.css" />
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
                        <li> <a href="admin.php?action=products">Produits</a></li>
                        <li> <a href="admin.php?action=categories">Categories</a></li>
                        <li> <a href="admin.php?action=users">Utilisateurs</a></li>
                        <li> <a href="admin.php?action=orders">Commandes</a></li>
                        <li> <a href="logout.php">Se déconnecter</a></li>
					</ul>
				</div>
			</div>
			<div class="content-dash col-12">
				<div class="col-12">
					<div class="c-shild">
						<!--start form -->
						<div class="content-dash col-12">
							<div class="c-dash-fixer col-4">
							</div>
							<div class="cotent-holder col-8">
								<form class="col-12 form-l" method="get" action="admin.php">
									<div class="col-12 h-login">
										<label class="input-lable"> Ajouter un produit</label>
										<div class="img-prev">
											<img id="img-preview" src="resources/HK962.jpeg" />
										</div>
									</div>
									<div class="col-12 h-login">
										<input class="input-l" type="text" name="nam_product" placeholder=" Nom du produit"
											autocomplete="off" />
										<input class="input-l" type="number" name="price" placeholder=" Prix" />
										<input class="input-l img-input" type="file" name="img" id="imginput">
										<select class="input-l" name="category">
											<?php
                                                    $cat = ft_select($link, "SELECT * FROM categories");
                                                    while ($vc = mysqli_fetch_array($cat))
                                                    {
                                                        echo "<option value='$vc[0]'>$vc[1]</option>";
                                                    }
                                                ?>
										</select>
										<div class="btn-ld">
											<button class="btn-l" type="submit" name="btn" value="add_p"> Add</button>
										</div>
									</div>
								</form>
							</div>
						</div>
					</div>
				</div>
				</div>
			</div>
		</div>
	</div>
	<script src="js/admin.js">
	</script>
</body>

</html>
