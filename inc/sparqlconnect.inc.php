<?php
	$user="root";
	$password="";
	$server="localhost";
	$database="sparql_queries";
	mysql_connect($sparql_db['server'],$sparql_db['user'],$sparql_db['password']);
	mysql_select_db($sparql_db['database']);
?>
