<?php

require_once 'db.config.php';
require_once 'util.php';

$item = R::load( 'admin_account', $d["id"] );
$item->uid=1;
$item->uname='lzhao';
$item->upass='tgxq2015';
R::store( $item );

echo ('done');