<?php
require_once ('load.php');
header("Content-type: application/json; charset=utf-8");
$fundId = $_GET["fundId"];
display4NvAll($fundId);
