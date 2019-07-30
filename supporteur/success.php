<?php

include 'config/database.php';

$tuid = $_POST['tuid'];



$ch = curl_init();



curl_setopt($ch, CURLOPT_URL,               "https://api.hub2.io/v1/transactions/$tuid/status/in");

curl_setopt($ch, CURLOPT_RETURNTRANSFER,    true);

curl_setopt($ch, CURLOPT_HEADER,            false);

curl_setopt($ch, CURLOPT_GET,              false);

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,    true);

curl_setopt(
    $ch,
    CURLOPT_HTTPHEADER

);



$return           = curl_exec($ch);

$result          = json_decode($return, true);



$statut          = $result['status']['status'];

$codeH           = $result['status']['code'];

$tuid            = $result['tuid'];

$codetransaction = $result['purchase_ref'];

$action          = $result['action'];

$success         = $result['success'];

curl_close($ch);



if (isset($statut) and $statut == 'successful') {

    //CONNEXION A LA BASE DE DONNEES

    $statuts = 1;



    try {

        $dsn = "mysql:dbname=$dbname;host=$servername";

        $ps = new PDO($dsn, $dbuser, $dbpwd, array(PDO::ATTR_PERSISTENT => true));
    } catch (PDOException $e) {

        print 'Erreur ! : ' . $e->getMessage() . '<br/>';

        echo 'N° : ' . $e->getCode();

        //echo 'N° : '.$e->getLine();

        die();
    }



    //Modification des donn�es

    $update = $ps->prepare('UPDATE deposits SET  `status` = :nv_status WHERE transaction_id = :us_code');

    $update->execute(array(

        'nv_status' => ($statuts),

        'us_code' => ($codetransaction)

    )) or die(print_r($update->errorInfo()));



    header('location:https://psentreprise.com/user/dashboard');
}

?>

<html>
  <head>
    <!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=UA-142468561-1"></script>
    <script>
      window.dataLayer = window.dataLayer || [];
      function gtag(){dataLayer.push(arguments);}
      gtag('js', new Date());
      
      gtag('config', 'UA-142468561-1');
    </script>
  </head>
  <body>
    <div class="jumbotron text-xs-center" style="background-color:#f39c12; padding: 10%;">
    <h1 class="display-3" style="text-align:center; color:#27ae60; font-weight:bold;font-size:100px;">
        BRAVO!
    </h1>
    <p class="lead" style="text-align:center; color:#27ae60; font-size:49px;"><span> Vous êtes maintenant Supporter officiel des éléphants </span></p>
    <hr>
    <!-- <div class="imagesuppoter" style="text-align:center;">
        <img src="supporter.jpeg">
    </div> -->
    <p class=" lead" style="font-size:16px; color:#fff;">
        <a class="btn btn-success btn-sm" href="https://cnse.ci/" role="button">Retourner à la page d'accueil</a>
    </p>
</div>
  </body>
  
</html>
