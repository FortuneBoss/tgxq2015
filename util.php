<?php
function nullOrIntVal($str) {
    $str = trim($str);
    if (empty($str)) {
        return NULL;
    }
    return intval($str);
}

function nullOrFloatVal($str) {
    $str = trim($str);
    if (empty($str)) {
        return NULL;
    }
    return floatval($str);
}
