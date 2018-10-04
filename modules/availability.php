<?php
require_once 'mhead.php';
$adminRole = false;
if (($_SESSION['user']['role'] == "sonadmin") || ($_SESSION['user']['role'] == "hospadmin") || ($_SESSION['user']['role'] == "superadmin")) {
    $adminRole = true;
}
// DELETE EVENT
if (!empty($_GET['del'])) {
    if ($adminRole) {
        $stmts = $conn->prepare("DELETE FROM `events` WHERE `events`.`id` = ?");
        $res = $stmts->execute(array($_GET['del']));
        $stmts->closeCursor();
    }
}
// EDIT EVENT
if (!empty($_POST['editId'])) {
    if ($adminRole) {
      // confirms_needed
        $stmts = $conn->prepare("UPDATE `events` SET `title` = ?,`desc` = ?,`confirms_needed` = ?,`start` = ?,`end` = ?,`startColor` = ?,`endColor` = ?,`user_id` = ?  WHERE `events`.`id` = ?");
        $res = $stmts->execute(array($_POST['editTitle'], $_POST['editDesc'],$_POST['editConfirms'], $_POST['editStart'], $_POST['editEnd'], $_POST['editStartColor'], $_POST['editEndColor'], $_SESSION['user']['id'], $_POST['editId']));
        $stmts->closeCursor();
    }
}
// NEW EVENT
if (!empty($_POST['newTitle'])) {
    if ($adminRole) {
        $stmts = $conn->prepare("INSERT INTO events(`title`,`desc`,`confirms_needed`,`start`,`end`,`startColor`,`endColor`,`user_id`) VALUES(?, ?, ?, ?, ?, ?,?,?)");
        $res = $stmts->execute(array($_POST['newTitle'], $_POST['newDesc'],$_POST['newConfirms'], $_POST['newStart'], $_POST['newEnd'], $_POST['newStartColor'], $_POST['newEndColor'], $_SESSION['user']['id']));

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
        <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
        <link href="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../assets/css/users.css" type="text/css"/>
        <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.css">
        <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css'><link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'><link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'>
        <style class="cp-pen-styles">@import url("https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700&subset=latin-ext");
        </style>
        <link  href="../assets/css/jquery.datetimepicker.css" rel="stylesheet">
        <link rel="apple-touch-icon" sizes="144x144" href="/img/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
        <link rel="manifest" href="/img/site.webmanifest">
        <link rel="mask-icon" href="/img/safari-pinned-tab.svg" color="#5bbad5">
    </head>

    <body class="sidebar-is-reduced">
        <?php include "include/nav.php"; ?>
        <main class="l-main">
            <div class="content-wrapper content-wrapper--with-bg">
                <?php if ((!empty($_GET['mod'])) || (!empty($_GET['show']))) { ?>
                    <a href="availability.php" class="btn btn-success" >Availability-list</a>
                <?php } if ($adminRole) { ?>
                    <a href="availability.php?mod=-1" class="btn btn-primary" >New availability</a>
                    <?php
                }
                // Show single event
                if ((empty($_GET['mod'])) && (!empty($_GET['show']))) {
                    $stmts = $conn->prepare("SELECT * from events WHERE id=? LIMIT 1");
                    $res = $stmts->execute(array($_GET['show']));
                    $event = $stmts->fetch(PDO::FETCH_ASSOC);
                    $stmts->closeCursor();
                    if ($event != false) {
                        if (($_SESSION['user']['role'] == "sonadmin") || ($_SESSION['user']['role'] == "hospadmin") || ($_SESSION['user']['role'] == "superadmin")) {
                            echo "<a href='availability.php?mod=" . $event['id'] . "' class='btn btn-info'>Edit</a><a href='availability.php?del=" . $event['id'] . "' class='btn btn-danger'>Delete</a>";
                        }
                        echo "<h2>" . $event['title'] . "</h2>";
                        echo "<p>" . $event['desc'] . "</p>";
                        $stmts = $conn->prepare("SELECT * from users WHERE id=? LIMIT 1");
                        $res = $stmts->execute(array($event['user_id']));
                        $eventU = $stmts->fetch(PDO::FETCH_ASSOC);
                        $stmts->closeCursor();
                        echo "<p>By: <a href='profile.php?userid=" . $eventU['id'] . "' >" . $eventU['firstname'] . " " . $eventU['lastname'] . " (" . $eventU['email'] . ")</a></p>";
                        echo "<p>Start: " . $event['start'] . "</p>";
                        echo "<p>End: " . $event['end'] . "</p>";

                        $stmts = $conn->prepare("SELECT * from reservations WHERE event_id=?");
                        $stmts->execute(array($event['id']));
                        $res = $stmts->fetchAll(PDO::FETCH_ASSOC);
                        $stmts->closeCursor();
                        $isReserved = false;
                        $reservationCount = 0;
                        if (!empty($res)) {
                            $reservationHtml = "<table class='table'><thead><td>Title</td><td>Desc</td><td>Status</td><td>User</td></thead><tbody>";
                            $confirmCounter = 0;
                            foreach ($res as $reservation) {
                                $reservationHtml .= "<tr><td>" . $reservation['title'] . "</td><td>" . $reservation['desc'] . "</td>";
                                if($reservation['status'] == "confirmed"){
                                      $confirmCounter++;
                                if ($event['confirms_needed']>$confirmCounter) {
                                    $reservationHtml .= "<td class='bg-warning'>".$confirmCounter."/" . $event['confirms_needed'] . " requests confirmed</td>";
                                } else if ($event['confirms_needed']<=$confirmCounter) {
                                    $isReserved = true;
                                    $reservationHtml .= "<td class='bg-success'>".$confirmCounter."/" . $event['confirms_needed'] . " (complete)</td>";
                                } } else if ((($reservation['status'] == "hospconfirmed") || ($reservation['status'] == "sonaconfirmed"))&&($confirmCounter==0)) {
                                    $reservationHtml .= "<td class='bg-warning'>" . $reservation['status'] . "</td>";
                                } else {
                                    $reservationHtml .= "<td>" . $reservation['status'] . "</td>";
                                }

                                $reservationHtml .= "<td><a class='btn btn-primary' href='profile.php?userid='". $reservation['user_id'] . "'>Userprofile</a>'</td></tr>";
                                if ($reservation['status'] == "confirmed") {

                                }
                                $reservationCount++;
                            }
                            $reservationHtml .= "</tbody></table><a href='reservations.php' class='btn btn-primary'>Go to reservations</a>";
                            echo "<p>" . $reservationCount . " reservations</p>";
                            if ($isReserved) {
                                echo "<p>This availability is reserved with approval. No new reservations are possible at this time.</p>";
                            } else {
                                echo '<p><a href="reservations.php?action=new&event_id=' . $event['id'] . '" class="btn btn-info">Make reservation</a></p>';
                            }

                            if (($_SESSION['user']['role'] == "sonadmin") || ($_SESSION['user']['role'] == "hospadmin") || ($_SESSION['user']['role'] == "superadmin")) {
                                echo "<h4>Reservations for this availability</h4>";
                                echo $reservationHtml;
                            }
                        } else {
                            echo "<p>No reservations yet</p>";
                            echo '<p><a href="reservations.php?action=new&event_id=' . $event['id'] . '" class="btn btn-info">Make reservation</a></p>';
                        }
                    } else {
                        echo "<h4>Availability Not found</h4>";
                    }
                }
             // Show list
            if((empty($_GET['mod']))&&(empty($_GET['show']))) {
                $q = $conn->query("SELECT * from events");
             ?>
              <table class='table'><thead><td>Title</td><td>Desc</td><td>Start</td><td>End</td><td>Reservations</td><td>Confirmed</td><td>Actions</td></thead><tbody>
              <?php
              foreach($q as $event){
                $reservationCount = 0;
                $stmts = $conn->prepare("SELECT * from reservations WHERE event_id=?");
                $stmts->execute(array($event['id']));
                $res = $stmts->fetchAll(PDO::FETCH_ASSOC);
                $stmts->closeCursor();
                $isConfirmed="Unconfirmed";
                $confirmed = false;
                if($res!=false){
                  $countedConfirms = 0;
                  foreach($res as $reservation){
                    $reservationCount++;
                    if($reservation['status']=="confirmed"){
                      $countedConfirms++;
                    if($event['confirms_needed']>$countedConfirms) {
                      $isConfirmed="<span class='bg-danger'>".$countedConfirms."/".$event['confirms_needed']." confirms</span>";
                      //$confirmed = true;
                    } else if (($reservation['status']=="confirmed")&&($event['confirms_needed']<=$countedConfirms)) {
                      $isConfirmed="<span class='bg-success'>".$countedConfirms."/".$event['confirms_needed']." confirms (complete)</span>";
                      $confirmed = true;
                    }
                  }
                  if($countedConfirms==0){
                    if((($reservation['status']=="hospconfirmed")||($reservation['status']=="sonaconfirmed"))&&($confirmed==false)) {
                      $isConfirmed=$reservation['status'];
                    }
                  }
                  }
                }
                $f = false;
                $fHtml = "<tr><td>".$event['title']."</td><td>".$event['desc']."</td><td>".$event['start']."</td><td>".$event['end']."</td><td>".$reservationCount."</td><td>".$isConfirmed."</td><td>";
                if(!$confirmed){
                  $f = true;
                  $fHtml .= '<a href="reservations.php?action=new&event_id='.$event['id'].'" class="btn btn-info">Make reservation</a>';
              } else {
                //echo '<a class="btn btn-warning disabled">Reservation not possible</a>';
              }
                $fHtml .= "<a href='availability.php?show=".$event['id']."' class='btn btn-success'>Show</a>";
              if($adminRole){
                $fHtml .="<a href='availability.php?mod=".$event['id']."' class='btn btn-info'>Edit</a><a href='availability.php?del=".$event['id']."' class='btn btn-danger'>Delete</a>";
              }
              $fHtml .= "</td></tr>";
              if(($f)||($adminRole)){
                echo $fHtml;
              }
            }
            ?>
          </table></tbody>
                    <?php
                }
				
// Modify and create
                if ((!empty($_GET['mod'])) && (empty($_GET['show']))) {

                    if ($adminRole) {

//create event
                        if ($_GET['mod'] == "-1") {
                            ?>
                            <script>
                              var dateFormat = 'Y-m-d H:m:s'; //this should be mysql-compatible
                                $(document).ready(function () {

                                    $("#newStartColor").spectrum({
                                        preferredFormat: "hex",
                                        color: "blue"
                                    });
                                    $("#newEndColor").spectrum({
                                        preferredFormat: "hex",
                                        color: "red"
                                    });

                                    jQuery('#newStart').datetimepicker({
                                        timepicker: true,
                                        format: dateFormat
                                    });
                                    jQuery('#newEnd').datetimepicker({
                                        timepicker: true,
                                        format: dateFormat
                                    });
                                });
                            </script>
                            <h4>New Clinical Availability</h4>
                            <form action="availability.php" method="post">
                                <div class="form-group">
                                    <label for="newTitle">Title:</label>
                                    <input type="text" class="form-control" id="newTitle" name="newTitle">
                                </div>
                                <div class="form-group">
                                    <label for="newDesc">Description:</label>
                                    <input type="text" class="form-control" id="newDesc" name="newDesc">
                                </div>
                                <div class="form-group">
                                    <label for="newStart">Start:</label>
                                    <input type="text" class="form-control" id="newStart" name="newStart">
                                </div>

                                <div class="form-group">
                                    <label for="newEnd">End:</label>
                                    <input type="text" class="form-control" id="newEnd" name="newEnd">
                                </div>
								<div class="form-group">
                                    <label for="newConfirms">No of clinical personnel required:</label>
                                    <input type="text" class="form-control" id="newConfirms" name="newConfirms">
                                </div>
                                <div class="form-group">
                                    <label for="newStartColor">Unconfirmed color</label>
                                    <input type="text" class="form-control" id="newStartColor" name="newStartColor">
                                </div>
                                <div class="form-group">
                                    <label for="newEndColor">Confirmed color:</label>
                                    <input type="text" class="form-control" id="newEndColor" name="newEndColor">
                                </div>

                                <button type="submit" class="btn btn-default">Create Availability</button>
                            </form>
                            <?php
                        } else {

                            // Edit event
                            $stmts = $conn->prepare("SELECT * from events WHERE id=? LIMIT 1");
                            $res = $stmts->execute(array($_GET['mod']));
                            $event = $stmts->fetch(PDO::FETCH_ASSOC);
                            $stmts->closeCursor();
                            if ($event != false) {
                                ?>
                                <script>
                                    $(document).ready(function () {

                                        $("#editStartColor").spectrum({
                                            preferredFormat: "hex",
                                            color: "<?php echo $event['startColor']; ?>"
                                        });
                                        $("#editEndColor").spectrum({
                                            preferredFormat: "hex",
                                            color: "<?php echo $event['endColor']; ?>"
                                        });

                                        $('#editStart').datetimepicker({
                                            timepicker: true,
                                            format: dateFormat
                                        });
                                        $('#editEnd').datetimepicker({
                                            timepicker: true,
                                            format: dateFormat
                                        });
                                    });
                                </script>
                                <form action="availability.php" method="post">
                                    <div class="form-group">
                                        <label for="editTitle">Title:</label>
                                        <input type="hidden" class="form-control" id="editId" name="editId" value="<?php echo $event['id']; ?>" >
                                        <input type="text" class="form-control" id="editTitle" name="editTitle" value="<?php echo $event['title']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="editDesc">Description:</label>
                                        <input type="text" class="form-control" id="editDesc" name="editDesc" value="<?php echo $event['desc']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="editStart">Start:</label>
                                        <input type="text" class="form-control" id="editStart" name="editStart" value="<?php echo $event['start']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="editEnd">End:</label>
                                        <input type="text" class="form-control" id="editEnd" name="editEnd" value="<?php echo $event['end']; ?>">
                                    </div>
									<div class="form-group">
                                        <label for="editConfirms">No of clinical personnel required:</label>
                                        <input type="text" class="form-control" id="editConfirms" name="editConfirms" value="<?php echo $event['confirms_needed']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="editStartColor">Unconfirmed color</label>
                                        <input type="text" class="form-control" id="editStartColor" name="editStartColor">
                                    </div>
                                    <div class="form-group">
                                        <label for="editEndColor">Confirmed color:</label>
                                        <input type="text" class="form-control" id="editEndColor" name="editEndColor">
                                    </div>
                                    <button type="submit" class="btn btn-default">Save changes</button>
                                </form>
                                <?php
                            } else {
                                echo "<h2>There's no clinical availability with this id (Error 404)</h2>";
                            }
                        }
                    } else {

                        echo "<h4>403 - You are not permitted to perform this action</h4>";
                    }
                } // end of create and edit events
                ?>
            </div>
        </main>
        <script src='//production-assets.codepen.io/assets/common/stopExecutionOnTimeout-b2a7b3fe212eaa732349046d8416e00a9dec26eb7fd347590fbced3ab38af52e.js'></script><script src='https://use.fontawesome.com/2188c74ac9.js'></script><script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js'></script>
        <script src="../assets/js/users.js"></script>
        <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/spectrum/1.8.0/spectrum.min.js"></script>
        <script src="../assets/js/php-date-formatter.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mousewheel/3.1.13/jquery.mousewheel.min.js"></script>
        <script src="../assets/js/jquery.datetimepicker.js"></script>
    </body>
</html>
