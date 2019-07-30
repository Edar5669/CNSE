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
    <!--  Toastr -->


    <!--  validating the radio form -->

    <!--finish  validating the radio form -->
</head>

<body>

    <div id="preloader">
        <div data-loader="circle-side"></div>
    </div><!-- /Preload -->

    <main>
        <div class="container">
            <div id="wizard_container">
                <form name="" id="wrapped" method="POST">
                    <input id="website" name="website" type="text" value="">
                    <!-- Leave input above for security protection, read docs for details -->
                    <div id="middle-wizard">

                        <!-- First branch What Type of Project ============================== -->
                        <div class="" data-state="branchtype">
                            <div class="question_title">
                                <h3>Quel type de supporter voulez-vous être?</h3>
                            </div>
                            <div class="row">
                                <!-- recuperation des package-->
                                <?php if (isset($getTypeAbonnementRequest)) : ?>
                                    <?php while ($abonnement = mysqli_fetch_assoc($getTypeAbonnementRequest)) : ?>
                                        <div class="col-lg-3">
                                            <div class="item">
                                                <input id="<?= $abonnement['id'] ?>" name="abonnement" type="radio" value="<?= $abonnement['nom'] ?>" class="required" required>
                                                <label for="<?= $abonnement['id'] ?>"><img src="img/<?= $abonnement['nom'] ?>.png" height="160" width="160" alt=""><strong><?= $abonnement['nom'] ?></strong> <strong><?= $abonnement['montant'] ?></strong>.</label>
                                            </div>
                                        </div>
                                    <?php endwhile; ?>
                                <?php endif; ?>
                                <!-- fin de recuperation des package-->
                            </div>
                            <!-- /row-->

                        </div>
                        <!-- /middle-wizard -->
                        <!-- /First branch What Type of Project ============================== -->
                    </div>
                    <div id="">
                        <button type="submit" name="valider" class="valider btn btn-success btn-md">SUIVANT</button>
                    </div>

                    <!-- /bottom-wizard -->
                </form>
            </div>
            <!-- /Wizard container -->
        </div>
        <!-- /Container -->
    </main>
    <!-- /main -->



    <!-- COMMON SCRIPTS -->
    <script src="./assets/js/jquery-2.2.4.min.js"></script>
    <script src="./assets/js/common_scripts.min.js"></script>
    <script src="./assets/js/main.js"></script>
    <!--  Toastr -->



</body>

</html>