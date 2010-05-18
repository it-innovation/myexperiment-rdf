<?php
	$domain='protected';
	ini_set('include_path','inc/:.');
	include('genxml.inc.php');
	$uri=$datauri.$_GET['entity'];
	$desq=str_replace("~",$uri,$desqs[$_GET['params']]);
	$sparql="$prefixes\nDESCRIBE ?x where { $desq }";
//	echo htmlentities($sparql);
	$sparql_url=$datauri."sparql?query=".urlencode($sparql);
	$lines=file($sparql_url);
	header('Content-type: application/rdf+xml');
	foreach($lines as $line){
		echo $line;
	}
?>
