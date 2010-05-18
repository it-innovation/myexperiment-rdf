#!/usr/bin/php 
<?php
	include('/var/www/html/rdf/inc/xmlfunc.inc.php');
	if (!$argv[1]) die("No Knowledge Base specified!\n");
	$query="4s-query $argv[1] ".'"select distinct ?g where { graph ?g {?s ?p ?o} }"';
	$ph=popen($query,'r');
	$line=fgets($ph,8192);
	$xml="";
	while (sizeof($line)>0 && !feof($ph)){
		$xml.=$line;
		$line=fgets($ph,8192);
	}	
	fclose($ph);
	if (!$xml) die("Knowledge Base does not exist!\n");
	$pxml=parseXML($xml);
	foreach ($pxml[0]['children'][1]['children'] as $graphs){
		$glist.=$graphs['children'][0]['tagData']."\n";
	}
	echo substr($glist,0,-1);
	
?>

