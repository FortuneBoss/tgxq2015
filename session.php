<?php
// Storing Session
$g_uname=$_COOKIE['login_user'];
$g_uid=$_COOKIE['uid'];
if(!isset($g_uid)){
    header('Location: index.php'); // Redirecting To Home Page
}
$g_authFunds=$_COOKIE['authFunds'];
$g_fundId=$_COOKIE['fundId'];

function check_and_select_fundId($fundId, $authFunds) {
    $ret = false;
    foreach($authFunds as $authFundId) {
        if ($fundId == $authFundId) {
            $_COOKIE['fundId'] = $fundId;
            $ret = true;
            break;
        }
    }
    return $ret;
}
