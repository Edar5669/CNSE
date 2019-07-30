<?php
session_start();
include 'config/database.php';

// //recuperation de la liste des type de supporter
// $queryGetTypeSupporter = "SELECT * FROM abonnement";
// $getTypeSupporterRequest = mysqli_query($db,$queryGetTypeSupporter);

// //recuperation des supporter
// $queryGetSupporter = "SELECT * FROM supporter";
// $getSupporterRequest = mysqli_query($db,$queryGetTypeSupporter);

if (
    isset($_SESSION['type_abonnement']) &&
    isset($_SESSION['numeroabonnement']) &&
    isset($_SESSION['montant_abonnement']) &&
    isset($_SESSION['prenom']) &&
    isset($_SESSION['nom'])
) {
    // recuperation des sessions
    $type_abonnement = $_SESSION['type_abonnement'];
    $nom = $_SESSION['nom'];
    $prenom = $_SESSION['prenom'];
    $telephone = $_SESSION['numeroabonnement'];

    // requete pour recuperer le montant
    $montant = $_SESSION['montant_abonnement'];
} else {
    echo '<script language="Javascript">';
    echo 'document.location.replace("index.php")';
    echo ' </script>';
}


// fin de recuperation du montant

// mise a jour du type de supporter
// if (isset($_POST['valider'])) {
//     $query = "UPDATE supporter SET type_supporter = '$type_supporter' WHERE telephone='$telephone'";
//     $resul=mysqli_query($db,$query );
//     if ($resul) {
//         // redirection
// 	 echo '<script language="Javascript">';
// 	 echo 'document.location.replace("success.php")';
//      echo ' </script>';
//     }else{
//         echo '<script language="Javascript">';
//         echo 'document.location.replace("echec.php")';
//         echo ' </script>';
//     }
// }






?>








<?php include 'views/recapitulatif.views.php'; ?>