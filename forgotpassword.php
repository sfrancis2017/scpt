<?php
require_once "head.php";
// if session is set direct to index
if (isset($_SESSION['user'])) {
    if (($_SESSION['user']['role'] == "students") || ($_SESSION['user']['role'] == "superadmin")) {
        header("Location: modules/dashboard.php");
    } else if ($_SESSION['user']['role'] == "admin") {
        header("Location: modules/settings.php");
    } else {
        // if not students or admins
        header("Location: modules/profile.php");
    }
    exit;
} else {

    // STEPS TO DO AFTER THE "forgot password" OPTION IS SELECTED BY USER
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

            // Remove for production!
            //echo "<a href='" . $webRoot . "login.php?resetmd5=" . $resetMd5 . "&email=" . $_GET['email'] . "'>Click here</a>";

            mail($_GET['email'], "Reset your password", "<h1>Want to reset the password?</h1><p><a href='" . $webRoot . "login.php?resetmd5=" . $resetMd5 . "&email=" . $_GET['email'] . "'>Click here</a></p>");
        } else {
            echo "<h1>NO SUCH USER!</h1>";
            exit;
        }
    }
}

// check the email-link and allow for reset of password
if ((!empty($_GET['resetmd5'])) && (!empty($_GET['email']))) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email= ?");
    /* execute query */
    $stmt->execute(array($_GET['email']));

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();

    if ($result['resetmd5'] == $_GET['resetmd5']) {

//put the form here!
        //use include/require_once, put with echo, whatever... 
        //a form like here
        //todo: redirect or show password-change-stuff
        echo "<h1>Reset password ALLOWED ;)</h1>";
        exit;
    } else {

        echo "You are not authorized to change password.";
    }
}

if ((!empty($_POST['email'])) && (!empty($_POST['pass']))) {
    $email = $_POST['email'];
    $upass = $_POST['pass'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE email= ?");
    /* execute query */
    $stmt->execute(array($email));

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    if (password_verify($_POST['pass'], $result['password'])) {
        $_SESSION['user'] = $result; // save all data from user in the session
        if ($_SESSION['user']['role'] == "students") {
            header("Location: modules/dashboard.php");
        } else if ($_SESSION['user']['role'] == "superadmin") {
            // if admin
            header("Location: settings.php");
        } else {
            // if admin
            header("Location: user.php");
        }
    } /* elseif ($count == 1) {
      $errMSG = "Bad password";
      } */
    else
        $errMSG = "Wrong credits";
}
?>
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Login</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="assets/css/style.css" />
	<link rel="apple-touch-icon" sizes="144x144" href="/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
    <link rel="manifest" href="/img/site.webmanifest">
    <link rel="mask-icon" href="/img/safari-pinned-tab.svg" color="#5bbad5">
</head>
<body class="login">
    <div class="form-gap"></div>
    <div class="container">
        <div class="row">
            <div class="col-md-4 col-md-offset-4">
                <div class="panel panel-default">
                    <div class="panel-body">
                        <div class="text-center">
                            <h3><i class="fa fa-lock fa-4x"></i></h3>
                            <h2 class="text-center">Forgot Password?</h2>
                            <p>You can reset your password here.</p>
                            <div class="panel-body">
                                <form id="register-form" role="form" autocomplete="off" class="form" method="post">
                                    <div class="form-group">
                                        <div class="input-group">
                                            <span class="input-group-addon"><i class="glyphicon glyphicon-envelope color-blue"></i></span>
                                            <input id="email" name="email" placeholder="email address" class="form-control"  type="email">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input name="recover-submit" class="btn btn-lg btn-primary btn-block" value="Reset Password" type="submit">
                                    </div>
                                    <input type="hidden" class="hide" name="token" id="token" value=""> 
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script>
    $(document).ready(function () {
        $("#forgot").on("click", function () {
            document.location = "<?php echo $webRoot; ?>login.php?forgot=1&email=" + $("#email").val();
            console.log("<?php echo $webRoot; ?>login.php?forgot=1&email=" + $("#email").val());
        });
    });
</script>
<script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
</body>
</html>

