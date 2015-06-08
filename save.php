<?php
require_once 'db.config.php';
require_once 'util.php';

header("Content-type: application/json; charset=utf-8");

$data = $_POST['data'];
if (!is_array($data)) {
    echo '{"code":1,"err":"input error"}';
    exit;
}
$succ = 0; $failed = 0;
foreach($data as $d) {
    $item = R::load( 'nv', $d["id"] );
    if ($item->id==0 || $item->uid != $uid) {
        $failed++;
        continue;
    }
    $colName = $d["col"];
    switch ($colName) {
    case "id":
        // do not change readonly field
        break;
    case "nv_date":
        $item->nv_date = nullOrIntVal($d["val"]);
        break;
    case "net_value":
    case "ref_value":
        $item[$colName] = nullOrFloatVal($d["val"]);
        break;
    default:
        $item[$colName] = $d["val"];
        break;
    }
    R::store( $item );
    $succ++;
}

if ($failed == 0) {
    echo '{"code":0,"err":"success: ' . $succ . ', failed: ' . $failed . '"}';
} else {
    echo '{"code":1,"err":"success: ' . $succ . ', failed: ' . $failed . '"}';
}
