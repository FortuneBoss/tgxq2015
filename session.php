<?php
session_start();// Starting Session
// Storing Session
$g_uname=$_SESSION['login_user'];
$g_uid=$_SESSION['uid'];
if(!isset($g_uid)){
    header('Location: index.php'); // Redirecting To Home Page
}
$g_authFunds=$_SESSION['authFunds'];
$g_fundId=$_SESSION['fundId'];

function check_and_select_fundId($fundId, $authFunds) {
    $ret = false;
    foreach($authFunds as $authFundId) {
        if ($fundId == $authFundId) {
            $_SESSION['fundId'] = $fundId;
            $ret = true;
            break;
        }
    }
    return $ret;
}