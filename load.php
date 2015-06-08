<?php
require_once 'db.config.php';
require_once 'util.php';

function formatDate($val) {
   if ($val > 0) {
       return $val;
   }
   return "null";
}

function formatString($val) {
    if ($val != NULL && strlen(trim($val)) > 0) {
        return '"' . trim($val) . '"';
    }
    return "null";
}

function formatNumber($val) {
   if ($val != NULL) {
       return $val;
   }
   return "null";
}

function display($items, $selected=NULL) {
    echo '{"code":0,"err":"","data":[';
    $isFirst = true;
    foreach($items as $item) {
        if ($isFirst) {
            $isFirst = false;
        } else {
            echo ',';
        }
        echo '[';
        echo (($selected==NULL || !in_array($item->id, $selected))?'false':'true') . ',';
        echo $item->id . ',';
        echo formatDate($item->nv_date) . ',';
        echo formatNumber($item->net_value) . ',';
        echo formatNumber($item->ref_value);
        echo ']';
    }
    echo ']}';
}

function displayAll($fundId, $selected=NULL) {
    $items = R::find('nv',' fund_id=?',[$fundId]);
    display($items, $selected);
}

function displayIds($fundId, $ids,$selected=NULL) {
    $idsStr = '0';
    foreach($ids as $id) {
        $idsStr .= ',' . $id;
    }
    $items = R::find('nv', ' fund_id=? and id in (' . $idsStr . ') ', [$fundId]);
    display($items,$selected);
}

function display4Nv($items, $selected=NULL) {
    echo '{"code":0,"err":"","data":[';
    $isFirst = true;
    foreach($items as $item) {
        if ($isFirst) {
            $isFirst = false;
        } else {
            echo ',';
        }
        echo '[';
        echo formatDate($item->nv_date) . ',';
        echo formatNumber($item->net_value) . ',';
        echo formatNumber($item->ref_value);
        echo ']';
    }
    echo ']}';
}

function display4NvAll($fundId, $selected=NULL) {
    $items = R::find('nv',' fund_id=?',[$fundId]);
    display4Nv($items, $selected);
}
