<?php
require_once 'rb.php';
{
$db_host=getenv('MYSQL_PORT_3306_TCP_ADDR');
$db_port=getenv('MYSQL_PORT_3306_TCP_PORT');
$db_name=getenv('MYSQL_INSTANCE_NAME');
$db_uname=getenv('MYSQL_USERNAME');
$db_upass=getenv('MYSQL_PASSWORD');
/*
var_dump($db_host);
var_dump($db_port);
var_dump($db_name);
var_dump($db_uname);
var_dump($db_upass);
*/
$dsn='mysql:host='.$db_host.';port='.$db_port.';dbname='.$db_name;
//$dsn='mysql:host=localhost;dbname=zerohf';
//$db_uname='zerohf';
//$db_upass='zerohf2015';
//var_dump($dsn);
R::setup($dsn,$db_uname,$db_upass);
}
//R::freeze( TRUE );
