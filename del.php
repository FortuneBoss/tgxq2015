<?php
require_once 'db.config.php';
require_once 'session.php';
require_once 'util.php';
header("Content-type: application/json; charset=utf-8");

$data = $_POST['data'];
$ids = '0';
foreach($data as $id) {
    $ids .= ',' . $id;
}

$items = R::find('nv', ' fund_id=? and id in (' . $ids . ')', [$fundId]);
$deleted = [];
foreach($items as $item) {
    $deleted[] = $item->id;
}
R::trashAll($items);
echo '{"code":0,"err":"","data":[';
$isFirst = true;
foreach($deleted as $id) {
    if ($isFirst) {
        $isFirst = false;
    } else {
        echo ',';
    }
    echo $id;
}
echo ']}';


