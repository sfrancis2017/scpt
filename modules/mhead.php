<?php
session_write_close();
session_start();
ob_start();
require_once '../dbconnect.php';

// Check if generaly authenticated and redurect to login if not
if(empty($_SESSION['user'])){
  header("Location: ../login.php");
  die();
}
function eventReserved($eventId,$conn){
  $stmts = $conn->prepare("SELECT * from reservations WHERE event_id=?");
  $stmts->execute(array($eventId));
  $u = $stmts->fetchAll(PDO::FETCH_ASSOC);
  $stmts->closeCursor();
  foreach($u as $reservation){
    if($reservation['status']=="confirmed"){
      return true;
    }
  }
  return false;
}

?>
