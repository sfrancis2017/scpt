<?php
require_once 'mhead.php';
if (empty($_GET['userid'])) {
    header("Location: profile.php?userid=" . $_SESSION['user']['id']);
    die();
}
// Application library ( with Lib class )
//require __DIR__ . '/resource/library.php';
//$app = new DemoLib();
//$user = $app->UserDetails($_SESSION['user_id']); // get user details
?>
require_once 'mhead.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>SCPT</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../assets/css/users.css" type="text/css"/>
        <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
        <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css'><link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'><link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'>
		<link rel="apple-touch-icon" sizes="144x144" href="/img/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
        <link rel="manifest" href="/img/site.webmanifest">
        <link rel="mask-icon" href="/img/safari-pinned-tab.svg" color="#5bbad5">
        <style class="cp-pen-styles">@import url("https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700&subset=latin-ext");
        </style>
    </head>
    <body class="sidebar-is-reduced">
        <?php
        include "include/nav.php";

        if (!empty($_GET['userid'])) {
            $stmts = $conn->prepare("SELECT * from users WHERE id = ?");
            $stmts->execute(array($_GET['userid']));
            $user = $stmts->fetch(PDO::FETCH_ASSOC);
            $stmts->closeCursor();
        }
        ?>
        <main class="l-main">
            <div class="content-wrapper content-wrapper--with-bg">
                <div class="container">
                    <?php if ($user != false) { ?>
                        <div class="row">
                            <div class="panel panel-default">
                                <div class="panel-heading">  <h4 >User Profile</h4></div>
                                <div class="panel-body">
                                    <div class="col-md-4 col-xs-12 col-sm-6 col-lg-4">
                                       <?php
                                       // Profile picture is a flat file.. need to work on permanent solution.
                                       $profilePic = "https://x1.xingassets.com/assets/frontend_minified/img/users/nobody_m.original.jpg";
                                       if(file_exists(dirname(__FILE__)."/../img/avatars/".$user['id'].".png")){
                                         $profilePic = "../img/avatars/".$user['id'].".png";
                                       } else if(file_exists(dirname(__FILE__)."/../img/avatars/".$user['id'].".jpg")){
                                          $profilePic = "../img/avatars/".$user['id'].".jpg";
                                      } else if(file_exists(dirname(__FILE__)."/../img/avatars/".$user['id'].".jpeg")){
                                         $profilePic = "../img/avatars/".$user['id'].".jpeg";
                                     } else if(file_exists(dirname(__FILE__)."/../img/avatars/".$user['id'].".gif")){
                                        $profilePic = "../img/avatars/".$user['id'].".gif";
                                    }
                                        ?>
                                        <img alt="User Pic" src="<?php echo $profilePic; ?>" id="profile-image1" class="img-circle img-responsive">
                                    </div>
                                    <div class="col-md-8 col-xs-12 col-sm-6 col-lg-8" >
                                        <div class="container" >
                                            <h2>User Name: <?php echo $user['username'] ?></h2>
                                            <p> <b> Role: <?php echo $user['role'] ?> </b></p>
                                        </div>
                                        <hr>
                                        <ul class="container details" >
                                            <li><p><span class="glyphicon glyphicon-user one" style="width:50px;"></span><?php echo $user['firstname'] ?> <?php echo $user['lastname'] ?></p></li>
                                            <li><p><span class="glyphicon glyphicon-envelope one" style="width:50px;"></span><?php echo $user['email']; ?></p></li>
                                        </ul>
                                        <hr>
                                        <div class="col-sm-5 col-xs-6 tital " >Date of Joining: <?php echo $user['created'] ?></div>
                                    </div>
                                </div>
                            </div>
                        <?php } else { ?>
                            <h4>This user profile does not exist</h4>

                        <?php } ?>
                    </div>

                </div>
            </div>
        </main>
        <script src='//production-assets.codepen.io/assets/common/stopExecutionOnTimeout-b2a7b3fe212eaa732349046d8416e00a9dec26eb7fd347590fbced3ab38af52e.js'></script><script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script><script src='https://use.fontawesome.com/2188c74ac9.js'></script><script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js'></script>
        <script src="../assets/js/users.js"></script>
    </body>
</html>
