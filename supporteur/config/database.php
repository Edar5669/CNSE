<?php
$servername = 'localhost';
$dbuser = 'root';
$dbname = 'c9supporter_cnse';
$dbpwd = '';

$datepost = date("Y-m-d h:i:s");
$browser = $_SERVER['HTTP_USER_AGENT'];

$db = mysqli_connect($servername, $dbuser, $dbpwd, $dbname);

//check connexion
if (!$db) {
  die('erreur de connexion a la bdd' . mysqli_connect_error());
}
