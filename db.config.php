<?php
require_once 'rb.php';
{
$db_host=getenv('MYSQL_PORT_3306_TCP_ADDR');
$db_name=getenv('MYSQL_INSTANCE_NAME');
$db_uname=getenv('MYSQL_USERNAME');
$db_upass=getenv('MYSQL_PASSWORD');
R::setup('mysql:host='.$db_host.';dbname='.$db_name,$db_uname,$db_upass);
}
//R::freeze( TRUE );
