<?php
require_once 'mhead.php';
if (!empty($_SESSION['user'])) {
    if (($_SESSION['user']['role'] != "admin") && ($_SESSION['user']['role'] != "superadmin")) {
        if (!empty($_SESSION['user']['resetmd5'])) {
            echo "<h1> USER NEED TO BE CONFIRM ITS EMAIL FIRST!</h1>";
            exit;
        }
        echo "<h1> USER DOES NOT HAVE ENOUGH RIGHTS TO VIEW THIS PAGE</h1>";
    }
} else {
    echo "<h1> USER IS NOT AUTHENTICATED</h1>";
    exit;
}
  if(!empty($_GET['del'])){
    if($_SESSION['user']['role']=="superadmin"){
      $stmts = $conn->prepare("DELETE FROM `events` WHERE `events`.`user_id` = ?");
      $res = $stmts->execute(array($_GET['del']));
      $stmts->closeCursor();
      $stmts = $conn->prepare("DELETE FROM `reservations` WHERE `reservations`.`user_id` = ?");
      $res = $stmts->execute(array($_GET['del']));
      $stmts->closeCursor();
      $stmts = $conn->prepare("DELETE FROM `users` WHERE `users`.`id` = ?");
      $res = $stmts->execute(array($_GET['del']));
      $stmts->closeCursor();
    }
  }
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>SCPT</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../assets/css/users.css" type="text/css"/>
        <link rel="apple-touch-icon" sizes="144x144" href="/img/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
        <link rel="manifest" href="/img/site.webmanifest">
        <link rel="mask-icon" href="/img/safari-pinned-tab.svg" color="#5bbad5">
        <script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
        <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
        <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css'><link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'><link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'>
        <style class="cp-pen-styles">@import url("https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700&subset=latin-ext");
        </style>
    </head>
    <body class="sidebar-is-reduced">
        <?php include "include/nav.php"; ?>
        <main class="l-main">
            <div class="content-wrapper content-wrapper--with-bg">
              <h1> All Users</h1>
             <?php
             // $stmts = $conn->prepare("SELECT * from users WHERE role NOT IN ('students')");
             $stmts = $conn->prepare("SELECT * from users");
             $stmts->execute();
             $users = $stmts->fetchAll(PDO::FETCH_ASSOC);
             $stmts->closeCursor();
            $reservationHtml = "<table class='table'><thead><td>Name</td><td>Email</td><td>Role</td><td>Actions</td></thead><tbody>";
            foreach($users as $u){
              $reservationHtml .= "<tr><td><a href='profile.php?userid=".$u['id']."'>".$u['firstname']." ".$u['lastname']."</a></td><td>".$u['email']."</td><td>".$u['role']."</td><td>";
              if($_SESSION['user']['role']=="superadmin"){
              $reservationHtml .= "<a class='btn btn-danger' href='settings.php?del=".$u['id']."'>Delete</a>";
            }
              $reservationHtml .= "</td></tr>";
            }
            $reservationHtml .= "</tbody></table>";
            echo $reservationHtml;
             ?>
			 <h1> Students </h1>
             <?php
             // $stmts = $conn->prepare("SELECT * from users WHERE role NOT IN ('students')");
             $stmts = $conn->prepare("SELECT * from users WHERE role NOT IN ('sonadmin','hospadmin','superadmin')");
             $stmts->execute();
             $users = $stmts->fetchAll(PDO::FETCH_ASSOC);
             $stmts->closeCursor();
            $reservationHtml = "<table class='table'><thead><td>Name</td><td>Email</td><td>Role</td><td>Actions</td></thead><tbody>";
            foreach($users as $u){
              $reservationHtml .= "<tr><td><a href='profile.php?userid=".$u['id']."'>".$u['firstname']." ".$u['lastname']."</a></td><td>".$u['email']."</td><td>".$u['role']."</td><td>";
              if($_SESSION['user']['role']=="superadmin"){
              $reservationHtml .= "<a class='btn btn-danger' href='settings.php?del=".$u['id']."'>Delete</a>";
            }
              $reservationHtml .= "</td></tr>";
            }
            $reservationHtml .= "</tbody></table>";
            echo $reservationHtml;
             ?>
			<h1> Admins </h1>
             <?php
             // $stmts = $conn->prepare("SELECT * from users WHERE role NOT IN ('students')");
             $stmts = $conn->prepare("SELECT * from users WHERE role NOT IN ('students')");
             $stmts->execute();
             $users = $stmts->fetchAll(PDO::FETCH_ASSOC);
             $stmts->closeCursor();
            $reservationHtml = "<table class='table'><thead><td>Name</td><td>Email</td><td>Role</td><td>Actions</td></thead><tbody>";
            foreach($users as $u){
              $reservationHtml .= "<tr><td><a href='profile.php?userid=".$u['id']."'>".$u['firstname']." ".$u['lastname']."</a></td><td>".$u['email']."</td><td>".$u['role']."</td><td>";
              if($_SESSION['user']['role']=="superadmin"){
              $reservationHtml .= "<a class='btn btn-danger' href='settings.php?del=".$u['id']."'>Delete</a>";
            }
              $reservationHtml .= "</td></tr>";
            }
            $reservationHtml .= "</tbody></table>";
            echo $reservationHtml;
             ?>
            </div>
        </main>
        <script src='//production-assets.codepen.io/assets/common/stopExecutionOnTimeout-b2a7b3fe212eaa732349046d8416e00a9dec26eb7fd347590fbced3ab38af52e.js'></script><script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script><script src='https://use.fontawesome.com/2188c74ac9.js'></script><script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js'></script>
        <script src="../assets/js/users.js"></script>
    </body>
</html>
