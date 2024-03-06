<?php
include('fix_mysql.inc.php');

$db = mysql_connect($db_host[$serv], $db_user[$serv], $db_pass[$serv]) or ("Error connecting to MySQL database.");
mysql_select_db($db_name[$serv],$db);
//$SQL_Characters="SET NAMES utf8";
//$charset=mysql_query($SQL_Characters);
?>