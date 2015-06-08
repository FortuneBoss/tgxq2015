<?php
session_start();// Starting Session
// Storing Session
$uname=$_SESSION['login_user'];
$uid=$_SESSION['uid'];
if(!isset($uid)){
    header('Location: index.php'); // Redirecting To Home Page
}
$fundId=1;
