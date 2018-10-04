<?php
  require_once 'mhead.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <title>SCPT</title>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../assets/css/users.css" type="text/css"/>
        <link rel="apple-touch-icon" sizes="144x144" href="/img/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
        <link rel="manifest" href="/img/site.webmanifest">
        <link rel="mask-icon" href="/img/safari-pinned-tab.svg" color="#5bbad5">
        <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
        <meta charset="UTF-8">
        <title>Student Clinical Placement Tool</title>
        <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css'><link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'><link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'>
        <style class="cp-pen-styles">@import url("https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700&subset=latin-ext");
        </style>
        <link  href="../assets/css/jquery.datetimepicker.css" rel="stylesheet">
        <script src="../assets/js/php-date-formatter.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>
        <script src="../assets/js/jquery.datetimepicker.js"></script>
        <script>
            var dateFormat = 'Y-m-d H:m:s'; //this should be mysql-compatible
            $(document).ready(function () {

                jQuery('#inputStart').datetimepicker({
                    timepicker: true,
                    format: dateFormat
                });
                jQuery('#inputEnd').datetimepicker({
                    timepicker: true,
                    format: dateFormat
                });

                jQuery('#inputReservationStart').datetimepicker({
                    timepicker: true,
                    format: dateFormat
                });
                jQuery('#inputReservationEnd').datetimepicker({
                    timepicker: true,
                    format: dateFormat
                });
            });
        </script>
    </head>
    <body class="sidebar-is-reduced">
        <?php include "include/nav.php"; ?>
        <main class="l-main">
            <div class="container">
                <h1>List of reservations by date</h1>
                <p>This filter applies when start-dates and end-dates are selected. The related column here is "created at"</p>
                  <form action="reports.php" method="get">
                <div class="form-group">
                  <label for="inputReservationStart">Newer than:</label>
                 <?php if(!empty($_GET['inputReservationStart'])){ ?>
                  <input type="text" class="form-control" id="inputReservationStart" name="inputReservationStart" value="<?php echo $_GET['inputReservationStart']; ?>">
                <?php } else { ?>
                  <input type="text" class="form-control" id="inputReservationStart" name="inputReservationStart">
                  <?php } ?>
                </div>

                <div class="form-group">
                  <label for="inputReservationEnd">Older than:</label>
                 <?php if(!empty($_GET['inputReservationEnd'])){ ?>
                  <input type="text" class="form-control" id="inputReservationEnd" name="inputReservationEnd" value="<?php echo $_GET['inputReservationEnd']; ?>">
                     <?php } else { ?>
                       <input type="text" class="form-control" id="inputReservationEnd" name="inputReservationEnd">
                     <?php } ?>
                </div>
                <button type="submit" class="btn btn-default">Filter</button>
                <a href="reports.php" class="btn btn-danger">Clear</a>
                </form>
                <?php
                $reservationHtml = "<table class='table'><thead><td>Title</td><td>Desc</td><td>Status</td><td>User-Id</td><td>Availability</td><td>Son-Confirm</td><td>Hosp-Confirm</td><td>Created at</td></thead><tbody>";
                // create the sql-command for the various filter-situations.
                if((!empty($_GET['inputReservationStart']))&&(!empty($_GET['inputReservationEnd']))){
                  $stmts = $conn->prepare("SELECT * from reservations WHERE created_at > ? AND created_at < ?  ORDER BY created_at DESC");
                  $stmts->execute(array($_GET['inputReservationStart'],$_GET['inputReservationEnd']));
                }
               else if((!empty($_GET['inputStart']))&&(empty($_GET['inputReservationEnd']))){
                  $stmts = $conn->prepare("SELECT * from reservations WHERE created_at > ?  ORDER BY created_at DESC");
                  $stmts->execute(array($_GET['inputReservationStart']));
                }
               else if((empty($_GET['inputReservationStart']))&&(!empty($_GET['inputReservationEnd']))){
                  $stmts = $conn->prepare("SELECT * from reservations WHERE created_at < ?  ORDER BY created_at DESC");
                  $stmts->execute(array($_GET['inputReservationEnd']));
                }
                else {
                  $stmts = $conn->prepare("SELECT * from reservations ORDER BY created_at DESC");
                  $stmts->execute();
                }
              //  $stmts = $conn->prepare("SELECT * from reservations ORDER BY created_at DESC");
              //  $stmts->execute();
                $reservations = $stmts->fetchAll(PDO::FETCH_ASSOC);
                $stmts->closeCursor();
                if ($reservations != false) {
                    foreach ($reservations as $reservation) {
                        $stmts = $conn->prepare("SELECT * from users WHERE id=?");
                        $stmts->execute(array($reservation['user_id']));
                        $u = $stmts->fetch(PDO::FETCH_ASSOC);
                        $stmts->closeCursor();

                        $hospConfirm = "Unconfirmed";
                        if (!empty($reservation['hosp_confirm_id'])) {
                            $stmts = $conn->prepare("SELECT * from users WHERE id=?");
                            $stmts->execute(array($reservation['hosp_confirm_id']));
                            $u1 = $stmts->fetch(PDO::FETCH_ASSOC);
                            $stmts->closeCursor();
                            $hospConfirm = "<a href='profile.php?profileid=" . $u1['id'] . "' >" . $u1['firstname'] . " " . $u1['lastname'] . " (" . $u1['email'] . ") @ " . $reservation['hosp_confirm_timestamp'] . "</a>";
                        }

                        $sonConfirm = "Unconfirmed";
                        if (!empty($reservation['sona_confirm_id'])) {
                            $stmts = $conn->prepare("SELECT * from users WHERE id=?");
                            $stmts->execute(array($reservation['sona_confirm_id']));
                            $u2 = $stmts->fetch(PDO::FETCH_ASSOC);
                            $stmts->closeCursor();
                            $sonConfirm = "<a href='profile.php?profileid=" . $u2['id'] . "' >" . $u2['firstname'] . " " . $u2['lastname'] . "</a> @ " . $reservation['sona_confirm_timestamp'];
                        }

                        $reservationHtml .= "<tr><td>" . $reservation['title'] . "</td><td>" . $reservation['desc'] . "</td><td>" . $reservation['status'] . "</td><td>" . $u['firstname'] . " " . $u['lastname'] . "</td><td><a class='btn btn-primary' href='availability.php?show=" . $reservation['event_id'] . "'>Availability</a></td><td>" . $sonConfirm . "</td><td>" . $hospConfirm . "</td><td>" . $reservation['created_at'] . "</td></tr>";
                    }
                    $reservationHtml .= "</tbody></table>";
                    echo $reservationHtml;
                }
                ?>

                <h1>List of availability by date</h1>
              <p>This filter applies when start-dates and end-dates are selected. The related column here is "start"</p>
  <form action="reports.php" method="get">
<div class="form-group">
  <label for="inputStart">Newer than:</label>
 <?php if(!empty($_GET['inputStart'])){ ?>
  <input type="text" class="form-control" id="inputStart" name="inputStart" value="<?php echo $_GET['inputStart']; ?>">
<?php } else { ?>
  <input type="text" class="form-control" id="inputStart" name="inputStart">
  <?php } ?>
</div>

<div class="form-group">
  <label for="inputEnd">Older than:</label>
 <?php if(!empty($_GET['inputEnd'])){ ?>
  <input type="text" class="form-control" id="inputEnd" name="inputEnd" value="<?php echo $_GET['inputEnd']; ?>">
     <?php } else { ?>
       <input type="text" class="form-control" id="inputEnd" name="inputEnd">
     <?php } ?>
</div>
<button type="submit" class="btn btn-default">Filter</button>
<a href="reports.php" class="btn btn-danger">Clear</a>
</form>

                <?php
                $reservationHtml = "<table class='table'><thead><td>Title</td><td>Desc</td><td>Start</td><td>End</td><td>User</td><td>Created at</td></thead><tbody>";

                if((!empty($_GET['inputStart']))&&(!empty($_GET['inputEnd']))){
                  $stmts = $conn->prepare("SELECT * from events WHERE start > ? AND start < ?  ORDER BY created_at DESC");
                  $stmts->execute(array($_GET['inputStart'],$_GET['inputEnd']));
                }
               else if((!empty($_GET['inputStart']))&&(empty($_GET['inputEnd']))){
                  $stmts = $conn->prepare("SELECT * from events WHERE start > ?  ORDER BY created_at DESC");
                  $stmts->execute(array($_GET['inputStart']));
                }
               else if((empty($_GET['inputStart']))&&(!empty($_GET['inputEnd']))){
                  $stmts = $conn->prepare("SELECT * from events WHERE start < ?  ORDER BY created_at DESC");
                  $stmts->execute(array($_GET['inputEnd']));
                }
                else {
                  $stmts = $conn->prepare("SELECT * from events ORDER BY created_at DESC");
                  $stmts->execute();
                }
                $reservations = $stmts->fetchAll(PDO::FETCH_ASSOC);
                $stmts->closeCursor();
                if ($reservations != false) {
                    foreach ($reservations as $reservation) {
						if(!eventReserved($reservation['id'],$conn)){
                        $stmts = $conn->prepare("SELECT * from users WHERE id=?");
                        $stmts->execute(array($reservation['user_id']));
                        $u = $stmts->fetch(PDO::FETCH_ASSOC);
                        $stmts->closeCursor();
                        $reservationHtml .= "<tr><td>" . $reservation['title'] . "</td><td>" . $reservation['desc'] . "</td><td>" . $reservation['start'] . "</td><td>" . $reservation['end'] . "</td><td>" . $u['firstname'] . " " . $u['lastname'] . "</td><td>" . $reservation['created_at'] . "</td></tr>";
						}
                    }
                    $reservationHtml .= "</tbody></table>";
                    echo $reservationHtml;
                }
                ?>
                <h1>Admins</h1>
               <?php
               $stmts = $conn->prepare("SELECT * from users WHERE role NOT IN ('students')");
               //$stmts = $conn->prepare("SELECT * from users");
               $stmts->execute();
               $users = $stmts->fetchAll(PDO::FETCH_ASSOC);
               $stmts->closeCursor();
              $reservationHtml = "<table class='table'><thead><td>Name</td><td>Email</td><td>Role</td></thead><tbody>";
              foreach($users as $u){
                $reservationHtml .= "<tr><td><a href='profile.php?userid=".$u['id']."'>".$u['firstname']." ".$u['lastname']."</a></td><td>".$u['email']."</td><td>".$u['role']."</td>";
                $reservationHtml .= "</tr>";
              }
              $reservationHtml .= "</tbody></table>";
              echo $reservationHtml;
               ?>
            </div>
        </main>
        <script src='//production-assets.codepen.io/assets/common/stopExecutionOnTimeout-b2a7b3fe212eaa732349046d8416e00a9dec26eb7fd347590fbced3ab38af52e.js'></script><script src='https://use.fontawesome.com/2188c74ac9.js'></script><script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js'></script>
        <script src="../assets/js/users.js"></script>
    </body>
</html>