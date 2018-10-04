<?php require_once("head.php"); ?>
<!doctype html>
<html lang="en">
    <head>
        <!-- Required meta tags -->
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <!-- Custom fonts for this template -->
        <link href="https://fonts.googleapis.com/css?family=Catamaran:100,200,300,400,500,600,700,800,900" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Lato:100,100i,300,300i,400,400i,700,700i,900,900i" rel="stylesheet">
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
        <!-- Bootstrap CSS -->
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" integrity="sha384-WskhaSGFgHYWDcbwN70/dfYBj47jz9qbsMId/iRN3ewGhXQFZCSftd1LZCfmhktB" crossorigin="anonymous">
        <!-- Custom styles for this template -->
        <link href="assets/css/clinical-min.css" rel="stylesheet">
        <link href="assets/css/style.css" rel="stylesheet">
        <link rel="apple-touch-icon" sizes="144x144" href="/img/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
        <link rel="manifest" href="/img/site.webmanifest">
        <link rel="mask-icon" href="/img/safari-pinned-tab.svg" color="#5bbad5">
        <title>Welcome to SCPT</title>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-dark navbar-custom fixed-top">
            <div class="container">
                <img src="/img/scpt.png">
                <a class="navbar-brand" href="/img/scpt.png"></a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarResponsive">
                    <ul class="navbar-nav ml-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $webRoot; ?>register.php">Sign Up</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?php echo $webRoot; ?>login.php">Log In</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

        <header class="clinical text-center text-white">
            <div class="clinical-content">
                <div class="container">
                    <h1 class="clinical-heading mb-0">Clinical Placement</h1>
                    <h2 style="color:#0000CD" class="clinical-subheading mb-0">for students and employers</h2>
                    <p> You are currently not signed in <a href="<?php echo $webRoot; ?>login.php">Login</a>
                        Not registered yet? <a href="<?php echo $webRoot; ?>register.php">Sign Up</a> </p>
                    <a href="<?php echo $webRoot; ?>register.php" class="btn btn-primary btn-xl rounded-pill mt-5">Register</a>
                </div>
            </div>
        </header>
	<section id="videoembed" class="videoembed">
       <div class="container">
         <div class="row">
           <div class="col-lg-12 text-center">
             <div class="section-heading">
				         <style>
                            h2 {
                                   color:#0960ee;
                                 }
                        </style>
                        <center><b><h2>SCPT - Intro Video</h2></b></center>
                        <hr>
				        <div class="embed-responsive embed-responsive-16by9">
                        <iframe class="embed-responsive-item" width="560" height="315" src="https://www.youtube.com/embed/as_7z7A-v8Y?rel=0" gesture="media"  allow="encrypted-media" allowfullscreen frameborder="0" allowfullscreen></iframe>
					    </div>
             </div>
           </div>
         </div>
       </div>
   </section>
		<section>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 order-lg-2">
                        <div class="p-5">
                            <img class="img-fluid rounded-circle" src="img/01.jpg" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-1">
                        <div class="p-5">
                            <h2 class="display-4">About SCPT</h2>
                            <p>Student Clinical Placement Tool helps connect students with clinical schedule posted by employers. It brings them together by making it easier for employers to post their schedules as well as allows students to pick up free slots on the posted schedule based on their preference to do clinical hours</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="p-5">
                            <img class="img-fluid rounded-circle" src="img/02.jpg" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="p-5">
                            <h2 class="display-4">Employers</h2>
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section>
            <div class="container">
                <div class="row align-items-center">
                    <div class="col-lg-6 order-lg-2">
                        <div class="p-5">
                            <img class="img-fluid rounded-circle" src="img/03.jpg" alt="">
                        </div>
                    </div>
                    <div class="col-lg-6 order-lg-1">
                        <div class="p-5">
                            <h2 class="display-4">Students</h2>
                            <p></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>


        <!-- Footer -->
        <footer class="py-5 bg-black">
            <div class="container">
                <p class="m-0 text-center text-white small">Copyright &copy; SCPT 2018</p>
            </div>
            <!-- /.container -->
        </footer>
        <!-- Optional JavaScript -->
        <!-- jQuery first, then Popper.js, then Bootstrap JS -->
        <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js" integrity="sha384-smHYKdLADwkXOn1EmN1qk/HfnUcbVRZyYmZ4qpPea6sjB/pTJ0euyQp0Mk8ck+5T" crossorigin="anonymous"></script>
</body>
</html>
