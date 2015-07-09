<?php
require_once('db.config.php');
$username='lzhao';
$acct = R::findOne('admin_account', ' uname=?', [$username] );
$acct->authfunds = "1,2,3";
R::store( $acct );

