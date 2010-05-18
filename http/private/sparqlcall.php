<?php
	$domain='private';
	ini_set('include_path','../inc/:.');
	include('genxml.inc.php');
	include('sparqlfunc.inc.php');
	$uri=$datauri.$_GET['entity'];
	$desq=$desqs[$_GET['params']];
	$desq=str_replace("~",$uri,$desq);
	$sparql="$prefixes\nDESCRIBE ?x where { $desq }";
	$sparql_url=$datauri."private/sparql?query=".urlencode($sparql);
	$lines=file($sparql_url);
	echo $sparql;
*
	header('Content-type: application/rdf+xml');
	$xml="";
	foreach($lines as $line){
		$xml.=$line;
//		echo $line;
	}
	$fxml=reformatDescribeResults($xml);
	echo $fxml;
?>
