<?php
session_start();
include 'config/database.php';
//recuperation de la liste des type de supporter
$queryGetTypeAbonnement = "SELECT * FROM abonnement";
$getTypeAbonnementRequest = mysqli_query($db, $queryGetTypeAbonnement);



if (isset($_POST['valider'])) {
	// recuperation de la valeur du type supporter
	$montant = '';
	$abonnement = addslashes($_POST['abonnement']);
	$getAmountRequest = mysqli_query($db, "SELECT montant FROM abonnement WHERE nom ='$abonnement'");

	if ($getAmountRequest) {
		$rows = mysqli_num_rows($getAmountRequest);
		if ($rows == 1) {
			while ($row = mysqli_fetch_assoc($getAmountRequest)) {
				$montant = $row['montant'];
			}
			$_SESSION['type_abonnement'] = $abonnement;
			$_SESSION['montant_abonnement'] = $montant;
			// redirection
			echo '<script language="Javascript">';
			echo 'document.location.replace("recapitulatif.php")';
			echo ' </script>';
		} else {
			// redirection
			echo '<script language="Javascript">';
			echo 'document.location.replace("echec.php")';
			echo ' </script>';
		}
	} else {
		// redirection
		echo '<script language="Javascript">';
		echo 'document.location.replace("echec.php")';
		echo ' </script>';
	}
}






?>





<?php include 'views/abonnement.views.php'; ?>