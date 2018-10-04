<?php
require_once 'mhead.php';
$adminRole = false;
if (($_SESSION['user']['role'] == "sonadmin") || ($_SESSION['user']['role'] == "hospadmin") || ($_SESSION['user']['role'] == "superadmin")) {
    $adminRole = true;
}
if (!empty($_SESSION['user'])) {
    if (!empty($_SESSION['user']['resetmd5'])) {
        echo "<h1> USER NEEDS TO BE CONFIRMED FIRST - CHECK EMAIL!</h1>";
        exit;
    }
}
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
        </style></head>
    <body class="sidebar-is-reduced">
        <?php include "include/nav.php"; ?>
        <main class="l-main">
            <div class="content-wrapper content-wrapper--with-bg">
                <div class="container">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="dash-box dash-box-color-1">
                                <div class="dash-box-icon">
                                    <i class="glyphicon glyphicon-cloud"></i>
                                </div>
                                <div class="dash-box-body">
                                    <span class="dash-box-count">
                                        <?php
                                        if($adminRole){
                                          $stmts = $conn->prepare("SELECT COUNT(id) as rowCount from reservations");
                                          $stmts->execute(array());
                                        } else {
                                          $stmts = $conn->prepare("SELECT COUNT(id) as rowCount from reservations WHERE user_id=?");
                                          $stmts->execute(array($_SESSION['user']['id']));
                                        }
                                        $res = $stmts->fetch(PDO::FETCH_ASSOC);
                                        $stmts->closeCursor();
                                        echo $res['rowCount'];
                                        ?>
                                    </span>
                                    <span class="dash-box-title">No of Reservations</span>
                                </div>
                                <div class="dash-box-action">
                                    <button onclick="location.href = 'https://scpt.gwiddle.co.uk/modules/reservations.php'" type="button">More Info</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="dash-box dash-box-color-2">
                                <div class="dash-box-icon">
                                    <i class="glyphicon glyphicon-download"></i>
                                </div>
                                <div class="dash-box-body">
                                    <span class="dash-box-count"><?php
                                        $stmts = $conn->prepare("SELECT * from events");
                                        $stmts->execute(array());
                                        $res = $stmts->fetchAll(PDO::FETCH_ASSOC);
                                        $stmts->closeCursor();
                                        $unconfirmedEvents = 0;
                                        foreach($res as $event){
                                          if(!eventReserved($event['id'],$conn)){
                                            $unconfirmedEvents++;
                                          }
                                        }
                                        echo $unconfirmedEvents;
                                        ?></span>
                                    <span class="dash-box-title">Space Available</span>
                                </div>
                                <div class="dash-box-action">
                                    <button onclick="location.href = 'https://scpt.gwiddle.co.uk/modules/availability.php'" type="button">More Info</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="dash-box dash-box-color-3">
                                <div class="dash-box-icon">
                                    <i class="glyphicon glyphicon-alert"></i>
                                </div>
                                <div class="dash-box-body">
                                    <span class="dash-box-count">            <?php
                                    if($adminRole){
                                        $stmts = $conn->prepare("SELECT COUNT(id) as rowCount from reservations WHERE status='confirmed'");
                                        $stmts->execute(array());
                                      } else {
                                        $stmts = $conn->prepare("SELECT COUNT(id) as rowCount from reservations WHERE status='confirmed' AND user_id=?");
                                        $stmts->execute(array($_SESSION['user']['id']));
                                      }
                                        $res = $stmts->fetch(PDO::FETCH_ASSOC);
                                        $stmts->closeCursor();
                                        echo $res['rowCount'];
                                        ?></span>
                                    <span class="dash-box-title">Approved Clinical Schedules</span>
                                </div>
                                <div class="dash-box-action">
                                    <button onclick="location.href = 'https://scpt.gwiddle.co.uk/modules/reservations.php'" type="button">More Info</button>
                                </div>
                            </div>
                        </div>
        </main>
            <script src='//production-assets.codepen.io/assets/common/stopExecutionOnTimeout-b2a7b3fe212eaa732349046d8416e00a9dec26eb7fd347590fbced3ab38af52e.js'></script><script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script><script src='https://use.fontawesome.com/2188c74ac9.js'></script><script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js'></script>
            <script src="../assets/js/users.js"></script>
    </body>
</html>
