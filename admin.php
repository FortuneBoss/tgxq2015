<?php
include('login.php'); // Includes Login Script

if(isset($_SESSION['login_user'])){
   header("location: nv.php");
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
		<form action="" method="post">
		  <label>UserName :</label>
		  <input id="name" name="username" placeholder="username" type="text">
		  <label>Password :</label>
		  <input id="password" name="password" placeholder="*******" type="password">
		  <input name="submit" type="submit" value=" Login ">
		  <span><?php echo $error; ?></span>
		</form>
	  </div>
	</div>
  </body>
</html>
