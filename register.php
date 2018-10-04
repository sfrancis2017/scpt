<?php
require_once "head.php";
$whatsDone = "";
if (!empty($_SESSION['user'])) {
    if (!empty($_SESSION['user']['resetmd5'])) {
        header("Location: index.php");
    }
}

if (isset($_POST['signup'])) {

    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $uname = trim($_POST['uname']); // get posted data and remove whitespace
    $email = trim($_POST['email']);



    $stmt = $conn->prepare("SELECT * FROM users WHERE email= ?");
    /* execute query */
    $stmt->execute(array($email));

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    $stmt->closeCursor();
    //var_dump($result);
    if (empty($result)) {
        $resetMd5 = md5(random_bytes(99));
        $password = password_hash($_POST['password'], PASSWORD_BCRYPT, ['cost' => 12,]);
        // $stmts = $conn->prepare("UPDATE `users` SET `resetmd5` = ? WHERE `users`.`email` = ?");
        $stmts = $conn->prepare("INSERT INTO users(firstname,lastname,username,email,resetmd5,password) VALUES(?, ?, ?, ?, ?, ?)");
        //$stmts->bind_param("sss", $uname, $email, $password);
        $res = $stmts->execute(array($firstname, $lastname, $uname, $email, $resetMd5, $password));
        $stmts->closeCursor();


        // Remove for production!
        //echo "<a href='" . $webRoot . "login.php?setmd5check=" . $resetMd5 . "&setemail=" . $email . "&setregister=1'>DEBUG-LINK WHICH IS IN EMAIL TOO</a>";

        // https://www.w3schools.com/php/func_mail_mail.asp
        $whatsDone = "You should have a email. Don't forget to check the spam-folder!";
       mail($email, "Please confirm your email", "Hello ".$firstname." ". $lastname." \r\n Welcome to SCPT \r\n Please visit this link to confirm and complete your registration: ". $webRoot . "login.php?setmd5check=" . $resetMd5 . "&setemail=" . $email . "&setregister=1 \r\n Thanks, the SCPT-Team");
      } else {
        $whatsDone = "USER ALREADY EXISTS!";
    }
}
?>
<!DOCTYPE html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>Registration</title>
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" type="text/css"/>
    <link rel="stylesheet" href="assets/css/style.css" type="text/css"/>
	<link rel="apple-touch-icon" sizes="144x144" href="/img/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/img/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/img/favicon-16x16.png">
    <link rel="manifest" href="/img/site.webmanifest">
    <link rel="mask-icon" href="/img/safari-pinned-tab.svg" color="#5bbad5">
</head>
<body class="register">

    <div class="regBox">
        <img src="/img/user.png" class="user">
        <center><h2 class="register">Register with SCPT</h2></center>
        
        <form method="post" autocomplete="off">

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
                        <div class="alert alert-<?php echo ($errTyp == "success") ? "success" : $errTyp; ?>">
                            <span class="glyphicon glyphicon-info-sign"></span> <?php echo $errMSG; ?>
                        </div>
                    </div>
                    <?php
                }
                ?>

                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                        <input type="text" name="firstname" class="form-control" placeholder="Enter firstname" required/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                        <input type="text" name="lastname" class="form-control" placeholder="Enter lastname" required/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-user"></span></span>
                        <input type="text" name="uname" class="form-control" placeholder="Enter Username" required/>
                    </div>
                </div>

                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-envelope"></span></span>
                        <input type="email" name="email" class="form-control" placeholder="Enter Email" required/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="input-group">
                        <span class="input-group-addon"><span class="glyphicon glyphicon-lock"></span></span>
                        <input type="password" name="password" class="form-control" placeholder="Enter password" required/>
                    </div>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" id="PP" value="This"><a href="privacypolicy.php">Privacy Policy</a></label>
                </div>
                <div class="checkbox">
                    <label><input type="checkbox" id="TOS" value="This"><a href="termsandconditions.php">Terms of service</a></label>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn    btn-block btn-danger" name="signup" id="reg">Register</button>
                </div>

                <div class="form-group">
                    <hr/>
                </div>

                <div class="form-group">
                    <a href="<?php echo $webRoot; ?>login.php" type="button" class="btn btn-block btn-success" name="btn-login">Login</a>
                </div>
                <div class="form-group">
                    <a href="<?php echo $webRoot; ?>index.php" type="button" class="btn btn-block btn-info"
                       name="btn-login">Home</a>
                </div>

            </div>

        </form>
    </div>

    <script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="assets/js/tos.js"></script>

</body>
</html>
