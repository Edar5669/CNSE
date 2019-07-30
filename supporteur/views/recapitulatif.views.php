<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="STEPS | Multipurpose Working Wizard with Branches">
  <meta name="author" content="Ansonika">
  <title>CNSE, devenir supporter</title>

  <!-- Favicons-->
  <link rel="shortcut icon" href="./assets/img/favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" type="image/x-icon" href="./assets/img/apple-touch-icon-57x57-precomposed.png">
  <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="./assets/img/apple-touch-icon-72x72-precomposed.png">
  <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="./assets/img/apple-touch-icon-114x114-precomposed.png">
  <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="./assets/img/apple-touch-icon-144x144-precomposed.png">

  <!-- GOOGLE WEB FONT -->
  <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,400,700" rel="stylesheet">

  <!-- HUB2 -->
  <!-- HUB2 -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css">
  <link rel="stylesheet" href="https://static.hub2.io/v1/api.css" />

  <!-- BASE CSS -->
  <link href="./assets/css/bootstrap.min.css" rel="stylesheet">
  <link href="./assets/css/style.css" rel="stylesheet">
  <link href="./assets/css/menu.css" rel="stylesheet">
  <link href="./assets/css/vendors.min.css" rel="stylesheet">
  <link href="./assets/css/icon_fonts/css/all_icons.min.css" rel="stylesheet">
  <link href="./assets/css/skins/square/grey.css" rel="stylesheet">

  <!-- YOUR CUSTOM CSS -->
  <link href="./assets/css/custom.css" rel="stylesheet">

  <script src="./assets/js/modernizr.js"></script>
  <!-- Modernizr -->
  
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

  <div id="preloader">
    <div data-loader="circle-side"></div>
  </div><!-- /Preload -->

  <main>
    <div class="container">
      <div id="wizard_container">
        <form name="" id="wrapped" method="POST">
          <input id="website" name="website" type="hidden" value="">
          <!-- Leave input above for security protection, read docs for details -->
          <div id="middle-wizard">
            <!-- Last step ============================== -->
            <div class="submit step" id="end">
              <div class="question_title">
                <!-- Debut recapitulatif modifie Edar5669-->
                <h1 style="color:#00873D; font-weight:bold;">BRAVO !</h1>
                <p style="font-size:20px">
                  <span style="font-weight:bold;"><?php echo $prenom . " "; ?></span> Vous êtes inscrit en tant que supporter
                  <span style="font-weight:bold;"><?php echo " " . $type_abonnement; ?>.</span>
                  Validez maintenant votre paiement de <span style="font-weight:bold;"><?php echo " " . $montant . " "; ?></span>
                  pour finaliser votre inscription.
                </p>
                <!--Fin du recapitulatif-->
              </div>
              <div class="row justify-content-center">
                <div class="col-lg-5">
                  <div class="box_general">
                    <div class="form-group">
                      <label for="">Votre nom</label>
                      <input type="text" value="<?= $nom ?>" name="nom" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                      <label for="">Votre Prenom</label>
                      <input type="text" value="<?= $prenom ?>" name="prenom" class="form-control" readonly>
                    </div>
                    <div class="form-group">
                      <label for="">Votre numero de telephone</label>
                      <input type="text" value="<?= $telephone ?>" name="telephone" class="required form-control" readonly>
                    </div>
                    <div class="form-group">
                      <label for="">Type de supporter souhaité</label>
                      <input type="texte" value="<?= $type_abonnement ?>" name="type_supporter" class="required form-control" readonly>
                    </div>
                    <div class="form-group">
                      <label for="">Montant a payer</label>
                      <input type="text" value="<?= $montant ?>" name="telephone" class="form-control" readonly>
                    </div>

                  </div>
                  <div class="item hidden">
                    <input id="customer_id" type="text" name="customer_id" value="<?php echo $_SESSION['numeroabonnement'] ?>" hidden />
                    <input id="purchase_ref" type="text" name="purchase_ref" value="<?php echo $_SESSION['type_abonnement'] ?>" hidden />
                    <input id="amount" type="text" name="amount" value="<?php echo $_SESSION['montant_abonnement'] ?>" hidden />
                    <input id="currency" type="text" name="currency" value="XOF" hidden />
                  </div>


                  <div class="form-group" style="margin-left:-31%">
                    <button type="submit" name="valider" id='panier_checkout' class="valider btn btn-success btn-md">VALIDER VOTRE INSCRIPTION</button>
                  </div>
                  <!-- /box_general -->
                  <!-- <div id="" >
                    <button type="submit" name="valider" id='panier_checkout' class="valider btn btn-success btn-md">VALIDER VOTRE INSCRIPTION</button>
                  </div> -->

                </div>





              </div>
              <!-- /row -->
            </div>


            <!-- /Last step ============================== -->
          </div>
      </div>
      <!-- /middle-wizard -->

      <!-- /bottom-wizard -->
      </form>
    </div>
    <!-- /Wizard container -->
    </div>
    <!-- /Container -->
  </main>
  <!-- /main -->

  <!-- Jquery-->
  <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>

  <!-- HUB2-->
  <script src="https://js.hub2.io/external/jquery-redirect.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>
  <script src="https://js.hub2.io/v1/api.js"></script>

  <!-- COMMON SCRIPTS -->
  <script src="./assets/js/common_scripts.min.js"></script>
  <script src="./assets/js/main.js"></script>

  <script>
    $(document).ready(function() {
      $("#panier_checkout").click(function(event) {
        event.preventDefault();
        // On récuèpre les informations du panier
        var POSTdata = {
          "customer_id": $('#customer_id').val(),
          "purchase_ref": $('#purchase_ref').val(),
          "in": {
            "amount": parseFloat($("#amount").val()),
            "currency": $("#currency").val()
          }
        };

        var transaction_id = $('#purchase_ref').val();
        var usersid = $('#customer_id').val();
        var amount = $('#amount').val();
        var currency = $('#currency').val();

        $.ajax({
            // URL vers une page du serveur du partenaire
            // Les autre informations importantes seront inséré dans cette page
            url: './extras/pay.php',
            type: 'POST',
            dataType: 'json',
            data: POSTdata
          })
          .done(function(data) {
            $.ajax({
              url: 'extras/enregistrer.php',
              data: {
                'tuid': data.tuid,
                'token': data.token,
                'transaction_id': transaction_id,
                'usersid': usersid,
                'amount': amount
              },
              type: 'POST',
              success: function(d) {
                console.log("Enregistrement dans la base effectué avec succès");
                /*
                 * On récupère le resultat de la page pay.php pour lancer le formulaire de paiement HUB2.
                 */
                var hub2 = new Hub2(data.tuid, data.token);
                hub2.init();
              }

            });



          })
          .fail(function(jqXHR, textStatus) {
            try {
              var msg = $.parseJSON(jqXHR.responseText);
              console.error("Filter error " + msg.code + ' - ' + msg.message);
            } catch (e) {
              console.error("First call : " + textStatus + ' - ' + e);
            }
          });
      });
    });
  </script>


</body>

</html>