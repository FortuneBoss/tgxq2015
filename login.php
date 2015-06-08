<?php
require_once('db.config.php');

session_start(); // Starting Session
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
            $_SESSION['login_user']=$username; // Initializing Session
            $_SESSION['uid']=$acct->uid;
            header("location: nv.php"); // Redirecting To Other Page
        } else {
            $error = "Username or Password is invalid";
        }
    }
}
?>
