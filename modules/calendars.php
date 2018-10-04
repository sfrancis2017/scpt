<?php
require_once 'mhead.php';
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <title>SCPT</title>
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <link href="https://netdna.bootstrapcdn.com/bootstrap/3.0.0/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="../assets/css/users.css" type="text/css"/>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.css" type="text/css"/>
        <link rel="apple-touch-icon" sizes="144x144" href="/img/apple-touch-icon.png">
        <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
        <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
        <link rel="manifest" href="/img/site.webmanifest">
        <link rel="mask-icon" href="/img/safari-pinned-tab.svg" color="#5bbad5">
        <style>
            body {
                margin-bottom: 40px;
                margin-top: 40px;
                text-align: center;
                font-size: 14px;
                font-family: 'Roboto', sans-serif;
                background:url(https://www.digiphotohub.com/wp-content/uploads/2015/09/bigstock-Abstract-Blurred-Background-Of-92820527.jpg);
            }

            #wrap {
                width: 1100px;
                margin: 0 auto;
            }

            #external-events {
                float: left;
                width: 150px;
                padding: 0 10px;
                text-align: left;
            }

            #external-events h4 {
                font-size: 16px;
                margin-top: 0;
                padding-top: 1em;
            }

            .external-event { /* try to mimick the look of a real event */
                margin: 10px 0;
                padding: 2px 4px;
                background: #3366CC;
                color: #fff;
                font-size: .85em;
                cursor: pointer;
            }

            #external-events p {
                margin: 1.5em 0;
                font-size: 11px;
                color: #666;
            }

            #external-events p input {
                margin: 0;
                vertical-align: middle;
            }

            #calendar {
                /* 		float: right; */
                margin: 0 auto;
                width: 900px;
                background-color: #FFFFFF;
                border-radius: 6px;
                box-shadow: 0 1px 2px #C3C3C3;
                -webkit-box-shadow: 0px 0px 21px 2px rgba(0,0,0,0.18);
                -moz-box-shadow: 0px 0px 21px 2px rgba(0,0,0,0.18);
                box-shadow: 0px 0px 21px 2px rgba(0,0,0,0.18);
            }
        </style>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.2/moment.js"></script>
        <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.1/jquery.min.js'></script>
        <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
        <link href="https://fonts.googleapis.com/css?family=Roboto:100,100i,300,300i,400,400i,500,500i,700,700i,900,900i" rel="stylesheet">
        <script src="http://netdna.bootstrapcdn.com/bootstrap/3.0.0/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/fullcalendar/3.9.0/fullcalendar.min.js"></script>
        <link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css'><link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/css/bootstrap.min.css'><link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css'>
        <style class="cp-pen-styles">@import url("https://fonts.googleapis.com/css?family=Open+Sans:300,400,400i,600,700&subset=latin-ext");
        </style>
    </head>
    <body class="sidebar-is-reduced">
        <?php include "include/nav.php"; ?>
        <main class="l-main">
            <script>
                var date = new Date();
                var d = date.getDate();
                var m = date.getMonth();
                var y = date.getFullYear();
                var calData = [<?php
        $q = $conn->query("SELECT * from events");
        foreach ($q as $event) {
            $resConfirmed = false;
            echo "{";
            $stmts = $conn->prepare("SELECT * from reservations WHERE event_id=?");
            $stmts->execute(array($event['id']));
            $res = $stmts->fetchAll(PDO::FETCH_ASSOC);
            $stmts->closeCursor();
            $titleContent = $event['title'];
            $reservationCount = 0;
            $halfConfirmed = false;
            $confirmCount = 0;
            if (!empty($res)) {
                foreach ($res as $reservation) {
                  if ($reservation['status'] == "confirmed"){
                    $confirmCount++;
                    if ($event['confirms_needed']>$confirmCount) {
                        //  $titleContent .= " (reserved)";
                        // endColor should maybe renamed to confirmedColor
                      //  echo 'color:\'' . $event['endColor'] . '\',';
                        $halfConfirmed = true;
                    } else if ($event['confirms_needed']<=$confirmCount) {
                      $resConfirmed = true;
                      $halfConfirmed = false;
                    }
                  }
                  if($confirmCount==0){
                    if(($reservation['status']=="hospconfirmed")||($reservation['status']=="sonaconfirmed")) {
                      $halfConfirmed = true;
                    }
                  }
                    $reservationCount++;
                }
            }
            $titleContent .= " (" . $confirmCount . "/".$event['confirms_needed']." confirms)";
            if(($halfConfirmed == false)&&($resConfirmed)){
              echo 'color:\'' . $event['endColor'] . '\',';
            }
            if(($halfConfirmed)&&($resConfirmed == false)){
              echo "color:'yellow',";
              echo "textColor:'black',";
            }
            if(($halfConfirmed == false)&&($resConfirmed == false)){
                echo 'color:\'' . $event['startColor'] . '\',';
            }
            echo 'title:\'' . $titleContent . '\',';
            echo 'start: new Date(\'' . $event['start'] . '\'.replace(/-/g, "/")),';
            echo 'end: new Date(\'' . $event['end'] . '\'.replace(/-/g, "/")),';
            echo 'url:\'availability.php?show=' . $event['id'] . '\',';
            echo "},";
        }
        ?>];

                /*[

                 url: 'https://ccp.cloudaccess.net/aff.php?aff=5188',
                 {
                 title: 'All Day Event',
                 start: new Date(y, m, 1)
                 },
                 {
                 id: 999,
                 title: 'Repeating Event',
                 start: new Date(y, m, d-3, 16, 0),
                 allDay: false,
                 className: 'info'
                 },
                 {
                 id: 999,
                 title: 'Repeating Event',
                 start: new Date(y, m, d+4, 16, 0),
                 allDay: false,
                 className: 'info'
                 },
                 {
                 title: 'Meeting',
                 start: new Date(y, m, d, 10, 30),
                 allDay: false,
                 className: 'important'
                 },
                 {
                 title: 'Lunch',
                 start: new Date(y, m, d, 12, 0),
                 end: new Date(y, m, d, 14, 0),
                 allDay: false,
                 className: 'important',
                 color: 'yellow',
                 textColor: 'black'
                 },
                 {
                 title: 'Birthday Party',
                 start: new Date(y, m, d+1, 19, 0),
                 end: new Date(y, m, d+1, 22, 30),
                 allDay: false,
                 className: 'danger'
                 },
                 {
                 title: 'Click for Google',
                 start: new Date(y, m, 28),
                 end: new Date(y, m, 29),
                 url: 'https://ccp.cloudaccess.net/aff.php?aff=5188',
                 className: 'success'
                 }
                 ];*/
                jQuery(document).ready(function ($) {
                    $('#calendar').fullCalendar({
                        header: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'month,agendaWeek,agendaDay'
                        },
                        defaultDate: date,
                        timezone: "Asia/Ho_Chi_Minh",
                        defaultView: 'month',
                        //editable: true,
                        selectable: true,
                        events: calData,
                        eventRender: function (event, element) {
                            //element.append('<span data-id="'+ event.id+'" class="deleteEvent" onclick="alert("asds");">x</span>');
                        },
                        eventResize: function (event, delta, revertFunc) {

                            $.ajax({
                                url: 'addEvent.php/?type=resize&start=' + event.start.format() + '&end=' + event.end.format() + '&id=' + event.id,
                            })
                                    .done(function () {
                                        console.log("success");
                                        $('#calendar').fullCalendar('refetchEvents');
                                    });

                        },
                        eventDrop: function (event, delta, revertFunc) {

                            $.ajax({
                                url: 'addEvent.php/?type=resize&start=' + event.start.format() + '&end=' + event.end.format() + '&id=' + event.id,
                            })
                                    .done(function () {
                                        console.log("success");
                                        $('#calendar').fullCalendar('refetchEvents');
                                    });

                        }
                    });

                    // Delete Event
                    $('.deleteEvent').click(function (event) {
                        var eventId = $(this).attr('data-id');
                        alert(eventId);
                    });

                    $('#formAdd').submit(function (event) {
                        event.preventDefault();
                        var data = $(this).serialize();
                        $.ajax({
                            url: $(this).attr('action'),
                            type: 'GET',
                            dataType: 'json',
                            data: data
                        })
                                .done(function () {
                                    console.log("success");
                                    $('#calendar').fullCalendar('refetchEvents');
                                    $('#myModal').modal('hide');
                                });
                    });
                });
            </script>
            <div id='wrap'>
                <div id='calendar'></div>
                <div style='clear:both'></div>
            </div>
        </main>
        <script src='//production-assets.codepen.io/assets/common/stopExecutionOnTimeout-b2a7b3fe212eaa732349046d8416e00a9dec26eb7fd347590fbced3ab38af52e.js'></script><script src='https://use.fontawesome.com/2188c74ac9.js'></script><script src='https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.7/js/bootstrap.min.js'></script>
        <script src="../assets/js/users.js"></script>
    </body>
</html>
