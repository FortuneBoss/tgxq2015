<?php
require_once ('session.php');
require_once ('load.php');
header("Content-type: application/json; charset=utf-8");
displayAll($g_fundId);
