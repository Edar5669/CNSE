<?php
include 'config/database.php';
//tableau contenant les erreurs
$errors = [];
//tableau contenant les alerts
$alerts = [];

//recuperation de la liste des pays
$queryGetCountry = "SELECT * FROM countries";
$getCountryRequest = mysqli_query($db, $queryGetCountry);


//quand le formulaire est soumit
if (isset($_POST['valider'])) {
  //recuperation des donnees envoyee
  $nom = '';
  $prenom = '';
  $email = '';
  $telephone = '';
  $pays = '';
  $ville = '';
  $region = '';
  $genre = '';
  $photo = '';

  //validation du formulaire
  if (!empty($_POST['ville'])) {
    $ville = addslashes($_POST['ville']);
    $getVilleRequest = mysqli_query($db, "SELECT name FROM cities WHERE id='$ville'");
    while ($rows = mysqli_fetch_assoc($getVilleRequest)) {
      $ville = addslashes($rows['name']);
    }
  }

  if (!empty($_POST['email'])) {
    $email = addslashes($_POST['email']);
  }
  if (!empty($_POST['photo'])) {
    $photo = addslashes($_POST['photo']);
  }

  if (!empty($_POST['region'])) {
    $region = addslashes($_POST['region']);
    $getRegionRequest = mysqli_query($db, "SELECT name FROM states WHERE id='$region'");
    while ($rows = mysqli_fetch_assoc($getRegionRequest)) {
      $region = addslashes($rows['name']);
    }
  }

  if (empty($_POST['nom'])) {
    array_push($errors, "le nom de famille est obligatoire");
  } else {
    $nom = addslashes($_POST['nom']);
  }

  if (empty($_POST['prenom'])) {
    array_push($errors, "le prenom  est obligatoire");
  } else {
    $prenom = addslashes($_POST['prenom']);
  }

  if (empty($_POST['telephone'])) {
    array_push($errors, "le telephone  est obligatoire");
  } else {
    $telephone = addslashes($_POST['telephone']);
  }

  if (empty($_POST['pays'])) {
    array_push($errors, "le pays de residence  est obligatoire");
  } else {
    $pays = addslashes($_POST['pays']);
    $getPaysRequest = mysqli_query($db, "SELECT name FROM countries WHERE id='$pays'");
    while ($rows = mysqli_fetch_assoc($getPaysRequest)) {
      $pays = addslashes($rows['name']);
    }
  }

  if (empty($_POST['genre'])) {
    array_push($errors, "votre genre est obligatoire");
  } else {
    $genre = addslashes($_POST['genre']);
  }


  //verifier s'il n'y a pas d'erreur

  if (count($errors) === 0) {
    //verifier pour voir si l'utilisateur existe deja
    $queryCheckUser = "SELECT telephone FROM supporter WHERE telephone='$telephone'";
    $checkRequest = mysqli_query($db, $queryCheckUser);

    //verfication de reponse
    if ($checkRequest) {

      $rows = mysqli_num_rows($checkRequest);

      if ($rows === 0 or $rows !== 0) {

        $queryInsert = "INSERT INTO supporter(id,nom,prenom,email,telephone,genre,pays,ville,region,datepost,browser,photo)
          VALUES('','$nom','$prenom','$email','$telephone','$genre','$pays','$ville','$region','$datepost','$browser','$photo')";

        $insertRequest = mysqli_query($db, $queryInsert);


        //verification si la requeste c'est faite avec success
        if ($insertRequest) {

          //insert des donnees en sessions
          session_start();
          $_SESSION['numeroabonnement'] = $telephone;
          $_SESSION['nom'] = $nom;
          $_SESSION['prenom'] = $prenom;

          echo '<script language="Javascript">';
          echo 'document.location.replace("./abonnement.php")';
          echo ' </script>';
        } else {
          array_push($errors, "Echec d'abonnement Veuillez reessayer");
        }
      } else {
        array_push($alerts, "Vous deja inscrit dans la base donner des supporters");
      }
    }
  }
}
?>

<?php include 'views/index.views.php'; ?>
