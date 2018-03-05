<?php

$mysql_hostname = "localhost";
$mysql_user = "root";
$mysql_password = "111111";
$mysql_database = "maplogin";

$db_conn = mysqli_connect($mysql_hostname, $mysql_user, $mysql_password, $mysql_database);
if (!$db_conn) {
  $error = mysqli_connect_error();
  $errno = mysqli_connect_errno();
  print "$errno: $error\n";
  exit();
 }


?>
