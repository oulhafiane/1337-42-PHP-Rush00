<?php
include("config.php");
function ft_exec_cmd($link, $cmd, $success, $error)
{
	if (mysqli_query($link, $cmd) && $success != NULL)
		echo $success;
	else if ($error != NULL)
		die($error);
}
function success_table($table)
{
	return ("Table $table created successfully<br />");
}
function fail_table($link, $table)
{
	return ("Error creating table $table: ".mysqli_error($link)."<br />");
}
function insert_into_categories($link, $name_category)
{
	ft_exec_cmd($link, "INSERT INTO categories(name_category) VALUES('$name_category')", "Category : $name_category creted successfully<br />", "");
}
function insert_into_products($link, $name, $price, $picture, $categorie)
{
	$result = mysqli_query($link, "SELECT id_category FROM categories WHERE name_category LIKE '$categorie' LIMIT 1");
	$id_category = mysqli_fetch_array($result)['id_category'];
	ft_exec_cmd($link, "INSERT INTO products(name_product, price_product, picture_product, id_category) VALUES('$name', $price, '$picture', $id_category)", "Product : $name added successfully<br />", "");
}
$link = mysqli_connect($host, $user, $pass);
if (!$link)
	die('Connexion impossible: '.mysqli_connect_error());
$create_db = "CREATE DATABASE IF NOT EXISTS $db DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci";
$success_create_db = "Database $db created successfully<br />";
$fail_create_db = "Error creating database $db: ".mysqli_error($link)."<br />";
$create_users = "CREATE TABLE IF NOT EXISTS users (login varchar(256) PRIMARY KEY, passwd varchar(128), admin bit DEFAULT 0, token varchar(256))";
$add_admin = "INSERT INTO users VALUES('admin', '".hash("whirlpool", "toor")."', 1, '')";
$add_zakariaa = "INSERT INTO users VALUES('zakariaa', '".hash("whirlpool", "zoulhafi")."', 0, '')";
$add_guest = "INSERT INTO users VALUES('guest', '".hash("whirlpool", "kak!!akdasjkk3214@@jelkqwekqwnd5342$$@##4adkaslk!ewqe")."', 0, '')";
$create_category = "CREATE TABLE IF NOT EXISTS categories (id_category int PRIMARY KEY auto_increment, name_category varchar(256) UNIQUE)";
$create_product = "CREATE TABLE IF NOT EXISTS products (id_product int PRIMARY KEY auto_increment, name_product varchar(256) UNIQUE, price_product REAL, picture_product varchar(256) DEFAULT 'resources/HK962.jpeg', id_category int, CONSTRAINT FK_Product_Category FOREIGN KEY(id_category) REFERENCES categories(id_category))";
ft_exec_cmd($link, $create_db, $success_create_db, $fail_create_db);
$create_basket = "CREATE TABLE IF NOT EXISTS baskets (id_basket int PRIMARY KEY auto_increment, date_basket datetime NOT NULL DEFAULT CURRENT_TIMESTAMP, login varchar(256), purchased bit DEFAULT b'0', CONSTRAINT FK_BASKET_USERS FOREIGN KEY(login) REFERENCES users(login))";
$create_basket_intermidiate = "CREATE TABLE IF NOT EXISTS basket_intermidiate (id_product int, id_basket int, qty int, CONSTRAINT PK_BASKET_INERMIDIATE PRIMARY KEY(id_product, id_basket), CONSTRAINT FK_BASKET_PRODUCTS FOREIGN KEY(id_product) REFERENCES products(id_product), CONSTRAINT FK_BASKET_INTERMIDIATE FOREIGN KEY(id_basket) REFERENCES baskets(id_basket))";
ft_exec_cmd($link, "use $db", "Using db : $db<br />", "Error using db : $db<br />");
ft_exec_cmd($link, $create_users, success_table("users"), fail_table($link, "users"));
ft_exec_cmd($link, $create_category, success_table("categories"), fail_table($link, "categories"));
ft_exec_cmd($link, $create_product, success_table("products"), fail_table($link, "products"));
ft_exec_cmd($link, $create_basket, success_table("baskets"), fail_table($link, "baskets"));
ft_exec_cmd($link, $create_basket_intermidiate, success_table("basket_intermidiate"), fail_table($link, "basket_intermidiate"));
ft_exec_cmd($link, $add_admin, "User : admin created successfuly<br />", "");
ft_exec_cmd($link, $add_zakariaa, "User : zakariaa created successfuly<br />", "");
ft_exec_cmd($link, $add_guest, "User : guest created successfuly<br />", "");
insert_into_categories($link, "Boitiers");
insert_into_categories($link, "Capteurs");
insert_into_categories($link, "Electroniques");
insert_into_categories($link, "Leds");
insert_into_categories($link, "Microcontrolleurs");
insert_into_categories($link, "Modules");
insert_into_categories($link, "Relais");
insert_into_products($link, "Carte Raspberry Pi 3 B", 599, "resources/micros/carte-raspberry-pi-3-b.jpg", "Microcontrolleurs");
insert_into_products($link, "KIT Arduino Complet", 550, "resources/micros/kit-arduino-complet-maroc.jpg", "Microcontrolleurs");
insert_into_products($link, "Arduino Mega 2560 R3", 179, "resources/micros/arduino-mega-2560-r3-maroc.jpg", "Microcontrolleurs");
insert_into_products($link, "ARDUINO LEONARDO", 200, "resources/micros/arduino-leonardo-maroc.jpg", "Microcontrolleurs");
insert_into_products($link, "Arduino Uno R3 Avec Cable", 119, "resources/micros/arduino-uno-r3-avec-cable-maroc.jpg", "Microcontrolleurs");
insert_into_products($link, "BOITIER POUR ARDUINO MEGA", 119, "resources/boitiers/boitier-pour-arduino-mega-maroc.jpg", "Boitiers");
insert_into_products($link, "BOITIER POUR ARDUINO UNO", 45, "resources/boitiers/boitier-pour-arduino-uno-maroc.jpg", "Boitiers");
insert_into_products($link, "Boitier Raspberry Pi", 89, "resources/boitiers/boitier-raspberry-pi-maroc.jpg", "Boitiers");
insert_into_products($link, "Afficheur 7 Segments", 4, "resources/electronics/afficheur-7-segments-maroc.jpg", "Electroniques");
insert_into_products($link, "Afficheur LCD Format 128 64", 169, "resources/electronics/afficheur-lcd-format-128-64-maroc.jpg", "Electroniques");
insert_into_products($link, "Afficheur LCD Format 20 04", 119, "resources/electronics/afficheur-lcd-format-20-04-maroc.jpg", "Electroniques");
insert_into_products($link, "Buzzer 16 Ohms 2KHz", 3, "resources/electronics/buzzer-16-ohms-2khz-maroc.jpg", "Electroniques");
insert_into_products($link, "Circuit ATMEGA328P", 49, "resources/electronics/circuit-atmega328p-maroc.jpg", "Electroniques");
insert_into_products($link, "Interrupteur 2 Positions", 6, "resources/electronics/interrupteur-2-positions-maroc.jpg", "Electroniques");
insert_into_products($link, "Joystick Analogique", 29, "resources/electronics/joystick-analogique-maroc.jpg", "Electroniques");
insert_into_products($link, "Photo Résistance LDR", 3, "resources/electronics/photo-resistance-ldr.jpg", "Electroniques");
insert_into_products($link, "Potentiometer 10 Kohms", 10, "resources/electronics/potentiometer-10-kohms-maroc.jpg", "Electroniques");
insert_into_products($link, "Régulateur De Tension 7805", 4, "resources/electronics/regulateur-de-tension-7805.jpg", "Electroniques");
insert_into_products($link, "LED 5mm Bleu", 1, "resources/leds/led-5mm-bleu-maroc.jpg", "Leds");
insert_into_products($link, "LED 5mm Jaune", 1, "resources/leds/led-5mm-jaune-maroc.jpg", "Leds");
insert_into_products($link, "LED 5mm Rouge", 1, "resources/leds/led-5mm-rouge-maroc.jpg", "Leds");
insert_into_products($link, "LED 5mm Vert", 1, "resources/leds/led-5mm-vert-maroc.jpg", "Leds");
insert_into_products($link, "LED RGB Trois Couleurs", 2.5, "resources/leds/led-rgb-trois-couleurs-maroc.jpg", "Leds");
insert_into_products($link, "Capteur D'Empreinte Digitale", 499, "resources/capteurs/capteur-dempreinte-digitale.jpg", "Capteurs");
insert_into_products($link, "Capteur De Débit 1 À 30 L/Min", 119, "resources/capteurs/capteur-de-debit-1-a-30-l-min.jpg", "Capteurs");
insert_into_products($link, "Capteur De Mouvement Infrarouge", 39, "resources/capteurs/capteur-de-mouvement-infrarouge-maroc.jpg", "Capteurs");
insert_into_products($link, "Capteur Infrarouge KY022", 20, "resources/capteurs/capteur-infrarouge-ky022-maroc.jpg", "Capteurs");
insert_into_products($link, "DHT11 Capteur De Température Et Humidité", 29, "resources/capteurs/dht11-capteur-de-temperature-et-humidite.jpg", "Capteurs");
insert_into_products($link, "DHT22 Capteur Température Et Humidité", 65, "resources/capteurs/dht22-capteur-temperature-et-humidite.jpg", "Capteurs");
insert_into_products($link, "MQ2 Détecteur De Gaz Et De Fumees", 50, "resources/capteurs/mq2-detecteur-de-gaz-et-de-fumees.jpg", "Capteurs");
insert_into_products($link, "Module 1 Relais 5v", 29, "resources/relais/module-1-relais-5v-maroc.jpg", "Relais");
insert_into_products($link, "Module 16 Relais 5v/12v", 249, "resources/relais/module-16-relais-5v-12v.jpg", "Relais");
insert_into_products($link, "Module 2 Relais 5v", 49, "resources/relais/module-2-relais-5v-maroc.jpg", "Relais");
insert_into_products($link, "Module 4 Relais 5v", 79, "resources/relais/module-4-relais-5v-maroc.jpg", "Relais");
insert_into_products($link, "Module 8 Relais 5v", 140, "resources/relais/module-8-relais-5v-maroc.jpg", "Relais");
insert_into_products($link, "Module Caméra OV7670", 125, "resources/modules/module-camera-ov7670.jpg", "Modules");
insert_into_products($link, "Module Badge RFID RC522", 49, "resources/modules/module-badge-rfid-rc522-maroc.jpg", "Modules");
insert_into_products($link, "Module Ethernet ENC28J60", 70, "resources/modules/module-ethernet-enc28j60-maroc.jpg", "Modules");
insert_into_products($link, "Module Ethernet W5100", 149, "resources/modules/module-ethernet-w5100-maroc.jpg", "Modules");
insert_into_products($link, "Module GSM SIM900A 1800/1900 MHz", 299, "resources/modules/module-gsm-sim900a-1800-1900-mhz.jpg", "Modules");
insert_into_products($link, "Module GSM/GPRS SIM900A", 349, "resources/modules/module-gsm-gprs-sim900a.jpg", "Modules");
insert_into_products($link, "Module Wifi ESP8266", 69, "resources/modules/module-wifi-esp8266-maroc.jpg", "Modules");
mysqli_close($link);
?>
