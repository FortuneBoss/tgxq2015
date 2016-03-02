<?php
require_once('db.config.php');

$error=''; // Variable To Store Error Message
if (isset($_POST['submit'])) {
    if (empty($_POST['username']) || empty($_POST['password'])) {
        $error = "Username or Password is invalid";
    }
    else {
        // Define $username and $password
        $username=$_POST['username'];
        $password=$_POST['password'];
        // SQL query to fetch information of registerd users and finds user match.
        $acct = R::findOne('admin_account', ' uname=? AND upass=?', [$username, $password] );
        if ($acct != NULL) {
			if (trim($acct->authfunds) != "") {
				session_start(); // Starting Session
                $_SESSION['login_user']=$username; // Initializing Session
                $_SESSION['uid']=$acct->uid;
                $authFunds = explode(",", ($acct->authfunds));
				$_SESSION['authFunds']=$authFunds;
                $_SESSION['fundId']=$authFunds[0];
				header("location: nv.php"); // Redirecting To Other Page
			} else {
				$error = "No authorization.";
			}
        } else {
            $error = "Username or Password is invalid";
        }
    }
}
?>
<html>
  <head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8" />
	<title>Login</title>
	<script src="js/jquery.min.js" type="text/javascript"></script>
	<script src="js/jquery-ui/jquery-ui.min.js"></script>
	<link rel="stylesheet" href="css.css">
  </head>
  <body>
	<div id="main">
	  <h1>Login</h1>
	  <div id="login">
		<form action="admin.php" method="post">
		  <label>UserName :</label>
		  <input id="name" name="username" placeholder="username" type="text">
		  <label>Password :</label>
		  <input id="password" name="password" placeholder="******" type="password">
		  <input name="submit" type="submit" value=" Login ">
		  <span><?php echo $error; ?></span>
		</form>
	  </div>
	</div>
  </body>
</html>
