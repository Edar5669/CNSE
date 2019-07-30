<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <meta name="description" content="MAVIA - Register, Reservation, Questionare, Reviews form wizard">
  <meta name="author" content="Ansonika">
  <title>CNSE, devenir supporter</title>

  <!-- Favicons-->
  <link rel="shortcut icon" href="img/favicon.ico" type="image/x-icon">
  <link rel="apple-touch-icon" type="image/x-icon" href="img/apple-touch-icon-57x57-precomposed.png">
  <link rel="apple-touch-icon" type="image/x-icon" sizes="72x72" href="img/apple-touch-icon-72x72-precomposed.png">
  <link rel="apple-touch-icon" type="image/x-icon" sizes="114x114" href="img/apple-touch-icon-114x114-precomposed.png">
  <link rel="apple-touch-icon" type="image/x-icon" sizes="144x144" href="img/apple-touch-icon-144x144-precomposed.png">

  <!-- GOOGLE WEB FONT -->
  <link href="https://fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i" rel="stylesheet">
  <!--  SELECT 2-->
  <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/css/select2.min.css" rel="stylesheet" />
  <!--  Toastr -->
  <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css">
  <!-- BASE CSS -->
  <link href="css/bootstrap.min.css" rel="stylesheet">
  <link href="css/select2-bootstrap4.min.css" rel="stylesheet">
  <link href="css/style.css" rel="stylesheet">
  <link href="css/responsive.css" rel="stylesheet">
  <link href="css/animate.min.css" rel="stylesheet">
  <link href="css/icon_fonts/css/all_icons_min.css" rel="stylesheet">
  <link href="css/skins/square/grey.css" rel="stylesheet">

  <!-- YOUR CUSTOM CSS -->
  <link href="css/custom.css" rel="stylesheet">

  <script src="js/modernizr.js"></script>
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
    <div id="form_container">
      <div class="row">
        <div class="col-lg-5">
          <div id="left_form" style="min-height:555px;">
            <figure><img src="img/logo.png" height="200" width="200" alt=""></figure>
            <h2>Devenez Supporter officiel des éléphants</h2>
            <p>

            </p>
            <a href="#0" id="more_info" data-toggle="modal" data-target="#more-info"><i class="pe-7s-info"></i></a>
          </div>
        </div>
        <div class="col-lg-7">

          <div id="wizard_container">
            <div id="top-wizard">
              <div id="progressbar"></div>
            </div>
            <!-- /top-wizard -->
            <form name="" id="wrapped" method="POST" action="index.php">
              <input id="website" name="website" type="text" value="">
              <!-- Leave for security protection, read docs for details -->
              <div id="middle-wizard">

                <div class="step">
                  <h3 class="main_question"><strong>1/2</strong>Veuillez remplir ce formulaire </h3>
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" name="nom" class="form-control required" placeholder="Nom de famille">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" name="prenom" class="form-control required" placeholder="prénom">
                      </div>
                    </div>
                  </div>
                  <!-- /row -->

                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="email" name="email" class="form-control" placeholder="Votre adresse email">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <input type="text" name="telephone" class="form-control required" placeholder="Numéro de téléphone">
                      </div>
                    </div>
                  </div>
                  <!-- /row -->
                  <!-- /row -->

                  <div class="row">
                    <div class="col-lg-12">
                      <div class="form-group">
                        <div class="">
                          <select class="required select2" style="width:100%;" name="pays" id='pays' onChange="getRegion(this.value);">
                            <option disabled selected>Selectionner le pays</option>
                            <?php if (isset($getCountryRequest)) : ?>
                              <?php while ($country = mysqli_fetch_assoc($getCountryRequest)) : ?>
                                <option value="<?php echo $country['id']; ?>"><?php echo $country['name']; ?></option>
                              <?php endwhile; ?>
                            <?php endif; ?>
                          </select>
                        </div>
                      </div>
                    </div>


                  </div>
                  <!-- /row -->
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <div class="">
                          <select class="select2" name="region" id="region" onChange="getVille(this.value);">
                            <option disabled selected>veuillez selectionner la région</option>
                          </select>
                        </div>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">

                        <select class="select2" style="width: 100%;" name="ville" id="ville">
                          <option selected disabled>Selectionner la ville/commune </option>

                        </select>

                      </div>
                    </div>

                    <div class="col-md-6">
                      <div class="form-group radio_input required">
                        <label><input type="radio" value="homme" name="genre" class="icheck required">Homme</label>
                        <label><input type="radio" value="femme" name="genre" class="icheck">Femme</label>
                      </div>
                      <!-- <br><br> -->
                    </div>
                  </div>

                  <div class="col-md-6">
                    <div class="form-group radio_input required">
                      <label>Photo</label>
                      <input name="photo" type="file">
                    </div>
                    <!-- <br><br> -->
                  </div>
                </div>

                <div id="" style="margin-left:31%">

                  <button type="submit" name="valider" class="valider btn btn-success btn-sm">SUIVANT</button>

                </div>
                <!-- /row -->
              </div>
              <!-- /step-->
          </div>

          </form>
        </div>
        <!-- /Wizard container -->
      </div>
    </div><!-- /Row -->
    </div><!-- /Form_container -->
  </main>

  <footer id="home" class="clearfix">
    <p>© <?= date('Y') ?> CNSE </p>
    <ul>
      <li><a href="#0" class="animated_link">Accueil</a></li>
      <li><a href="#0" class="animated_link">Nous contacter</a></li>

    </ul>
  </footer>
  <!-- end footer-->

  <div class="cd-overlay-nav">
    <span></span>
  </div>
  <!-- cd-overlay-nav -->

  <div class="cd-overlay-content">
    <span></span>
  </div>
  <!-- cd-overlay-content -->





  <!-- SCRIPTS -->
  <!-- Jquery-->
  <script src="js/jquery-3.2.1.min.js"></script>
  <!-- SELECT 2 -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.7/js/select2.min.js"></script>

  <!--  Toastr -->
  <script src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js" charset="utf-8"></script>
  <!-- Common script -->
  <script src="js/common_scripts_min.js"></script>

  <!-- Menu script -->
  <script src="js/velocity.min.js"></script>
  <script src="js/main.js"></script>
  <!-- Theme script -->
  <script src="js/functions.js"></script>


  <?php if (isset($errors)) : ?>
    <?php if (count($errors) != 0) : ?>
      <?php foreach ($errors as $error) : ?>
        <script type="text/javascript">
          // var message = ;
          toastr["error"]("<?php echo $error; ?>");
          // toastr.error(message);
        </script>
      <?php endforeach; ?>
    <?php endif; ?>
  <?php endif; ?>
  <?php if (isset($alerts)) : ?>
    <?php if (count($alerts) != 0) : ?>
      <?php foreach ($alerts as $alert) : ?>
        <script type="text/javascript">
          // var message = ;
          toastr["warning"]("<?php echo $alert; ?>");
          // toastr.error(message);
        </script>
      <?php endforeach; ?>
    <?php endif; ?>
  <?php endif; ?>
  <script type="text/javascript">
    $(document).ready(function() {
      $('.select2').select2();
    });

    function getRegion(val) {
      $.ajax({
        type: "POST",
        url: "./extras/region.php",
        data: 'country_id=' + val,
        success: function(data) {
          $("#region").html(data);
          getCity();
        }
      });
    }


    function getVille(val) {
      $.ajax({
        type: "POST",
        url: "./extras/ville.php",
        data: 'state_id=' + val,
        success: function(data) {
          $("#ville").html(data);
        }
      });
    }
  </script>


</body>

</html>