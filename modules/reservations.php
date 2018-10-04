<?php
require_once 'mhead.php';
// DELETE EVENT
if (!empty($_GET['del'])) {

    $allowed = false;
    if (($_SESSION['user']['role'] == "sonadmin") || ($_SESSION['user']['role'] == "hospadmin") || ($_SESSION['user']['role'] == "superadmin")) {
        $allowed = true;
    }
    if ($_SESSION['user']['role'] == "students") {
        $stmts = $conn->prepare("SELECT * from reservations WHERE id=? LIMIT 1");
        $res = $stmts->execute(array($_GET['del']));
        $event = $stmts->fetch(PDO::FETCH_ASSOC);
        $stmts->closeCursor();
        if ($event['status'] == "pending") {
            $allowed = true;
        }
    }

    if ($allowed) {
        $stmts = $conn->prepare("DELETE FROM `reservations` WHERE `id` = ?");
        $res = $stmts->execute(array($_GET['del']));
        $stmts->closeCursor();
    }
}

// EDIT EVENT
if (!empty($_POST['editId'])) {
    $allowed = false;
    if (($_SESSION['user']['role'] == "sonadmin") || ($_SESSION['user']['role'] == "hospadmin") || ($_SESSION['user']['role'] == "superadmin")) {
        $allowed = true;
    }
    if ($_SESSION['user']['role'] == "students") {
        $stmts = $conn->prepare("SELECT * from reservations WHERE id=? LIMIT 1");
        $res = $stmts->execute(array($_POST['editId']));
        $event = $stmts->fetch(PDO::FETCH_ASSOC);
        $stmts->closeCursor();
        if ($event['status'] == "pending") {
            $allowed = true;
        }
    }

    if ($allowed) {
        $stmts = $conn->prepare("UPDATE `reservations` SET `title` = ?,`desc` = ?  WHERE `id` = ?");
        $res = $stmts->execute(array($_POST['editTitle'], $_POST['editDesc'], $_POST['editId']));
        $stmts->closeCursor();
    }
}

// CONFIRM EVENT
if (!empty($_GET['confirmId'])) {
    if (($_SESSION['user']['role'] == "sonadmin") || ($_SESSION['user']['role'] == "hospadmin") || ($_SESSION['user']['role'] == "superadmin")) {

        $stmts = $conn->prepare("SELECT * from reservations WHERE id=? LIMIT 1");
        $res = $stmts->execute(array($_GET['confirmId']));
        $event = $stmts->fetch(PDO::FETCH_ASSOC);
        $stmts->closeCursor();
        $status = "pending";
        if ($event['status'] == "pending") {
            if (($_SESSION['user']['role'] == "sonadmin")) {
                $status = "sonaconfirmed";
            } else if (($_SESSION['user']['role'] == "hospadmin")) {
                $status = "hospconfirmed";
            } else if (($_SESSION['user']['role'] == "superadmin")) {
                $status = "confirmed";
            }
        } else {
            if (($_SESSION['user']['role'] == "sonadmin") && ($event['status'] == "hospconfirmed")) {
                $status = "confirmed";
            } else if (($_SESSION['user']['role'] == "hospadmin") && ($event['status'] == "sonaconfirmed")) {
                $status = "confirmed";
            } else {
                $status = $event['status'];
            }
        }
        if ($_SESSION['user']['role'] == "sonadmin") {
            $stmts = $conn->prepare("UPDATE `reservations` SET `status` = ?,`sona_confirm_id` = ?,`sona_confirm_timestamp` = current_timestamp  WHERE `id` = ?");
            $res = $stmts->execute(array($status, $_SESSION['user']['id'], $_GET['confirmId']));
        } else if ($_SESSION['user']['role'] == "hospadmin") {
            $stmts = $conn->prepare("UPDATE `reservations` SET `status` = ?,`hosp_confirm_id` = ?,`hosp_confirm_timestamp` = current_timestamp  WHERE `id` = ?");
            $res = $stmts->execute(array($status, $_SESSION['user']['id'], $_GET['confirmId']));
        } else if ($_SESSION['user']['role'] == "superadmin") {
            $stmts = $conn->prepare("UPDATE `reservations` SET `status` = ?,`hosp_confirm_id` = ?,`hosp_confirm_timestamp` = current_timestamp,`sona_confirm_id` = ?,`sona_confirm_timestamp` = current_timestamp  WHERE `id` = ?");
            $res = $stmts->execute(array($status, $_SESSION['user']['id'], $_SESSION['user']['id'], $_GET['confirmId']));
        }
        $stmts->closeCursor();
    }
}

// NEW EVENT
if (!empty($_POST['newTitle'])) {
    if (!empty($_SESSION['user'])) {
        $stmts = $conn->prepare("INSERT INTO reservations(`title`,`desc`,`user_id`,`event_id`) VALUES(?, ?, ?, ?)");
        $res = $stmts->execute(array($_POST['newTitle'], $_POST['newDesc'], $_SESSION['user']['id'], $_POST['newEventId']));
        
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
                <?php if (!empty($_GET['action'])) { ?>
                    <a href="reservations.php" class="btn btn-success" >Reservation-list</a>
                <?php } ?>
                <a href="availability.php" class="btn btn-primary" >Event-list</a>
                <?php
                if (empty($_GET['action'])) {
                    if (($_SESSION['user']['role'] == "students")) {

                        $stmts = $conn->prepare("SELECT * from reservations WHERE user_id=?");
                        $stmts->execute(array($_SESSION['user']['id']));
                        $res = $stmts->fetchAll(PDO::FETCH_ASSOC);
                        $stmts->closeCursor();
                    } else {
                        $res = $conn->query("SELECT * from reservations");
                    }
                    ?>
                    <table class='table'><thead><td>Title</td><td>Desc</td><td>Event</td><td>Status</td><td>Actions</td></thead><tbody>
                            <?php
                            // CREATE RESERVATION-LIST
                            foreach ($res as $reservation) {
                                // START ROW
                                echo "<tr><td>" . $reservation['title'] . "</td><td>" . $reservation['desc'] . "</td><td><a href='availability.php?show=" . $reservation['event_id'] . "' >Event</a></td><td>" . $reservation['status'] . "</td><td>";

                                // CHECK PERMISSIONS TO EDIT
                                $allowed = false;
                                $adminAllowed = false;
                                if (($_SESSION['user']['role'] == "sonadmin") || ($_SESSION['user']['role'] == "hospadmin") || ($_SESSION['user']['role'] == "superadmin")) {
                                    $allowed = true;
                                    $adminAllowed = true;
                                }
                                if (($reservation['status'] == "pending") && ($reservation['user_id'] == $_SESSION['user']['id'])) {
                                    $allowed = true;
                                }
                                // END OF CHECK, OUTPUT ONLY USEABLE BUTTONS
                                if ($allowed) {
                                    echo "<a href='reservations.php?action=edit&mod=" . $reservation['id'] . "' class='btn btn-info'>Edit</a><a href='reservations.php?del=" . $reservation['id'] . "' class='btn btn-danger'>Delete</a>";
                                    if (($adminAllowed) && ($reservation['status'] != "confirmed")) {
                                        echo "<a href='reservations.php?confirmId=" . $reservation['id'] . "' class='btn btn-warning'>Confirm</a>";
                                    }
                                }
                                echo "</td></tr>"; // <-- END OF ROW
                            }
                            ?>
                    </table></tbody>

                    <?php
                }
                if (!empty($_GET['action'])) {
                    if (($_GET['action'] == "new") && (!empty($_GET['event_id']))) {
                        $stmts = $conn->prepare("SELECT * from events WHERE id=? LIMIT 1");
                        $res = $stmts->execute(array($_GET['event_id']));
                        $event = $stmts->fetch(PDO::FETCH_ASSOC);
                        $stmts->closeCursor();

                        if (!empty($event)) {
                            ?>
                            <h4>Create a reservation for <a href="availability.php?show=<?php echo $event['id']; ?>" ><?php echo $event['title']; ?></a></h4>
                            <form action="reservations.php" method="post">
                                <div class="form-group">
                                    <label for="newTitle">Title:</label>
                                    <input type="text" class="form-control" id="newTitle" name="newTitle">
                                </div>
                                <div class="form-group">
                                    <label for="newDesc">Description:</label>
                                    <input type="hidden" class="form-control" id="newEventId" name="newEventId" value="<?php echo $event['id']; ?>">
                                    <input type="text" class="form-control" id="newDesc" name="newDesc">
                                </div>
                                <button type="submit" class="btn btn-default">Create reservation</button>
                            </form>

                            <?php
                        }
                    } else if (($_GET['action'] == "edit") && (!empty($_GET['mod']))) {
                        $stmts = $conn->prepare("SELECT * from reservations WHERE id=? LIMIT 1");
                        $res = $stmts->execute(array($_GET['mod']));
                        $event = $stmts->fetch(PDO::FETCH_ASSOC);
                        $stmts->closeCursor();

                        if (!empty($event)) {
                            ?>
                            <h4>Edit reservation <a href="availability.php?show=<?php echo $event['event_id']; ?>" >(Event)</a></h4>
                            <form action="reservations.php" method="post">
                                <div class="form-group">
                                    <label for="editTitle">Title:</label>
                                    <input type="hidden"  id="editId" name="editId" value="<?php echo($event['id']); ?>">
                                    <input type="text" class="form-control" id="editTitle" name="editTitle" value="<?php echo($event['title']); ?>">
                                </div>
                                <div class="form-group">
                                    <label for="newDesc">Description:</label>
                                    <input type="text" class="form-control" id="editDesc" name="editDesc" value="<?php echo($event['desc']); ?>">
                                </div>
                                <button type="submit" class="btn btn-default">Create reservation</button>
                            </form>

                            <?php
                        }
                    }
                }
                ?>
            </div>
        </main>
	        <footer class="py-5 bg-black">
            <div class="container">
                <p class="m-0 text-center text-white small">Copyright &copy; SCPT 2018</p>
            </div>
            <!-- /.container -->
        </footer>
        <script src='//production-assets.codepen.io/assets/common/stopExecutionOnTimeout-b2a7b3fe212eaa732349046d8416e00a9dec26eb7fd347590fbced3ab38af52e.js'></script><script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script><script src='https://use.fontawesome.com/2188c74ac9.js'></script><script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js'></script>
        <script src="../assets/js/users.js"></script>
    </body>
</html>
