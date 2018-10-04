<?php
require_once "head.php";
$whatsDone = "";
// if session is set direct the user to the index page.
if (isset($_SESSION['user'])) {
    if (empty($_SESSION['user']['resetmd5'])) {
        header("Location: modules/dashboard.php");
    }
    exit;
} else {


    // Steps to be undertaken after the forgot-password link is clicked.
    if ((!empty($_GET['forgot'])) && (!empty($_GET['email']))) {

        $stmt = $conn->prepare("SELECT * FROM users WHERE email= ?");
        /* execute query */
        $stmt->execute(array($_GET['email']));

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        $stmt->closeCursor();

        if (!empty($result)) {
            $resetMd5 = md5(random_bytes(99));
            $stmts = $conn->prepare("UPDATE `users` SET `resetmd5` = ? WHERE `users`.`email` = ?");
            //$stmts->bind_param("sss", $uname, $email, $password);
            $res = $stmts->execute(array($resetMd5, $_GET['email'])); //get result
            $stmts->closeCursor();

            // Remove the link or hash out for production!
            //echo "<a href='" . $webRoot . "login.php?resetmd5=" . $resetMd5 . "&email=" . $_GET['email'] . "'>DEBUG-LINK</a>";
            // Set content-type for sending emails through HTML
            $headers = "MIME-Version: 1.0" . "\r\n";
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            // More headers
            $headers .= 'From: <superadmin@scpt.gwiddle.co.uk>' . '\r\n';
            //$headers .= 'Cc: superadmin@scpt.gwiddle.co.uk' . '\r\n'; // CC not needed here
            // https://www.w3schools.com/php/func_mail_mail.asp
            $whatsDone = "PLEASE CHECK YOUR MAIL TO RESET YOUR PASSWORD";
      mail($_GET['email'], "Reset your password", "Hello ".$result['firstname']." ".$result['lastname']." \r\nYou have requested a password-reset on SCPT's Web Portal. Please enter your new password by clicking on the following link: " . $webRoot . "login.php?resetmd5=" . $resetMd5 . "&email=" . $_GET['email']."\r\n Best Regards, the SCPT-Team");
        } else {
            $whatsDone = "NO SUCH USER!";
        }
    }
}

// EMAIL-VERIFICATION STEPS
if ((!empty($_GET['setmd5check'])) && (!empty($_GET['setemail'])) && (!empty($_GET['setregister']))) {

    $stmt = $conn->prepare("SELECT * FROM users WHERE email= ?");
    $stmt->execute(array($_GET['setemail']));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    if (!empty($result)) {
        if ($result['resetmd5'] == $_GET['setmd5check']) {
            $stmts = $conn->prepare("UPDATE `users` SET `resetmd5` = '' WHERE `users`.`email` = ?");
            $res = $stmts->execute(array($_GET['setemail']));
            $whatsDone = "Email-verification done";
            $stmts->closeCursor();
        } else {
            $whatsDone = "Email-verification failed (already done?)";
        }
    }
}

// END OF EMAIL-VERIFICATION STEPS
// EMAIL-RESET STEPS
if ((!empty($_POST['setmd5check'])) && (!empty($_POST['setmail'])) && (!empty($_POST['setpass']))) {

    $stmt = $conn->prepare("SELECT * FROM users WHERE email= ?");
    $stmt->execute(array($_POST['setmail']));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    if (!empty($result)) {
        if ($result['resetmd5'] == $_POST['setmd5check']) {

            $password = password_hash($_POST['setpass'], PASSWORD_BCRYPT, ['cost' => 12,]);
            $stmts = $conn->prepare("UPDATE `users` SET `password` = ?,`resetmd5` = '' WHERE `users`.`email` = ?");
            $res = $stmts->execute(array($password, $_POST['setmail']));
            $whatsDone = "Password reseted, please login";
            $stmts->closeCursor();
        } else {
            $whatsDone = "Reset password failed (invalid MD5)";
        }
    }
}
// EMAIL-RESET END
// check the email-link and allow for password reset
// EMAIL-RESET FORM
if ((!empty($_GET['resetmd5'])) && (!empty($_GET['email']))) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email= ?");
    $stmt->execute(array($_GET['email']));
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    if ($result['resetmd5'] == $_GET['resetmd5']) {
        ?>
        <html>
            <head>
                <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
                <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
                <title>Set password</title>
                <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
                <link rel="stylesheet" href="assets/css/style.css" />
				<link rel="apple-touch-icon" sizes="144x144" href="/img/apple-touch-icon.png">
                <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
                <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
                <link rel="manifest" href="/img/site.webmanifest">
                <link rel="mask-icon" href="/img/safari-pinned-tab.svg" color="#5bbad5">
                <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
                <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
            </head>
            <style type="text/css">
                h2.login {
                    color:#FFFFFF;
                    text-align: center;
                }
            </style>
            <body class="login">
                <div class="loginBox">
                    <img src="/img/user.png" class="user">
                    <center><h2 class="login">Set your new password</h2></center>

                    <form method="post" id="loginform" autocomplete="off">

                        <div class="col-md-12">
                            <?php
                            if (!empty($whatsDone)) {

                                echo "<div class='form-group'><div class=\"alert alert-info\"><h4>" . $whatsDone . "</h4></div></div>";
                            }
                            ?>
                            <?php
                            if (isset($errMSG)) {
                                ?>
                                <div class="form-group">
                                    <div class="alert alert-danger">
                                        <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                                    </div>
                                </div>
                                <?php
                            }
                            ?>

                            <div class="form-group">
                                <div class="input-group">
                                    <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                                    <input type="hidden" name="setmd5check" value="<?php echo $_GET['resetmd5']; ?>" class="form-control" required/>
                                    <input type="hidden" name="setmail" value="<?php echo $_GET['email']; ?>" class="form-control" required/>
                                    <input type="password" name="setpass" class="form-control" placeholder="Password" required/>
                                </div>
                            </div>

                            <div class="form-group">
                                <hr/>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-block btn-primary" name="btn-login">change password</button>
                            </div>

                            <div class="form-group">
                                <hr/>
                            </div>


                            <div class="form-group">
                                <a href="<?php echo $webRoot; ?>index.php" type="button" class="btn btn-block btn-success"
                                   name="btn-login">Home</a>
                            </div>

                        </div>

                    </form>

                </div>
            </body></html>
        <?php
        //todo: redirect or show password-change-stuff

        exit;
    } else {

        //  echo "you are not allowed to change any password.";
    }
}
// EMAIL-RESET FORM END
// no need for send that data, simply check the existing one
//if (isset($_POST['btn-login'])) {
// LOGIN
if ((!empty($_POST['email'])) && (!empty($_POST['pass']))) {
    $email = $_POST['email'];
    $upass = $_POST['pass'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE email= ?");
    /* execute query */
    $stmt->execute(array($email));

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    if (empty($result['resetmd5'])) {
        if (password_verify($_POST['pass'], $result['password'])) {
            $_SESSION['user'] = $result; // save all data from user in the session
            header("Location: modules/dashboard.php");
        } /* elseif ($count == 1) {
          $errMSG = "Bad password";
          } */
        else
            $errMSG = "Wrong credits";
    } else {
        $whatsDone = "E-MAIL NOT CONFIRMED YET";
    }
}
// LOGIN END
?>

<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
</head>
<style type="text/css">
    h2.login {
        color:#FFFFFF;
        text-align: center;
    }
</style>
<body class="login">

    <div class="loginBox">
        <img src="/img/user.png" class="user">
        <h2 class="login">Login to SCPT:</h2>

        <form method="post" id="loginform" autocomplete="off">

            <div class="col-md-12">
                <?php
                if (!empty($whatsDone)) {

                    echo "<div class='form-group'><div class=\"alert alert-info\"><h4>" . $whatsDone . "</h4></div></div>";
                }
                ?>
                <?php
                if (isset($errMSG)) {
                    ?>
                    <div class="form-group">
                        <div class="alert alert-danger">
                            <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                        </div>
                    </div>
                    <?php
                }
                ?>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                        <input type="email" name="email" id="email" class="form-control" placeholder="Email" required/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                        <input type="password" name="pass" class="form-control" placeholder="Password" required/>
                    </div>
                </div>
                <div class="form-group">
                    <hr/>
                </div>
                <div class="form-group">
                    <button type="submit" class="btn btn-block btn-primary" name="btn-login">Login</button>
                </div>
                <div class="form-group">
                    <hr/>
                </div>
                <div class="form-group">
                    <a href="<?php echo $webRoot; ?>register.php" type="button" class="btn btn-block btn-danger"
                       name="btn-login">Register</a>
                </div>
                <div class="form-group">
                    <a type="button" id="forgot" class="btn btn-block btn-info"
                       name="btn-login">Forgot password</a>
                </div>
                <div class="form-group">
                    <a href="<?php echo $webRoot; ?>index.php" type="button" class="btn btn-block btn-success"
                       name="btn-login">Home</a>
                </div>
            </div>
        </form>
    </div>
    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function () {
            $("#forgot").on("click", function () {
                document.location = "<?php echo $webRoot; ?>login.php?forgot=1&email=" + $("#email").val();
                console.log("<?php echo $webRoot; ?>login.php?forgot=1&email=" + $("#email").val());
            });

        });
    </script>
</body>
</html>