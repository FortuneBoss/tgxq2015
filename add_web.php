<?php
require_once('session.php');
require_once('load.php');
require_once('db.config.php');
header("Content-type: application/json; charset=utf-8");

function add($fundId) {
    $item = R::dispense('nv');
    $item->fund_id = $fundId;
    $item->nv_date = date('Ymd');
    $item->net_value = 0;
    $item->ref_value = 0;
    $id = R::store($item);       //Create or Update
    $result[] = $item;
    return $result;
}

$result = add($g_fundId);
display($result);
