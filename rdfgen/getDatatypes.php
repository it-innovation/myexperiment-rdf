#!/usr/bin/php
<?php
	include('include.inc.php');
	include('xmlfunc.inc.php');
        include('datatypes.inc.php');
        $ph=popen($store4execpath."4s-query myexp_public \"PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> PREFIX owl: <http://www.w3.org/2002/07/owl#> PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> select distinct ?prop ?dt where {?prop rdf:type owl:DatatypeProperty . ?prop rdfs:range ?dt}\"",'r');
        $res="";
        while (!feof($ph)) {
                $res.=fgets($ph, 4096);
        }
	$resarr=parseXML($res);
	$fh=fopen($ldpath.'rdfgen/datatypes.txt','w');
	foreach ($resarr[0]['children'][1]['children'] as $num => $rec){
		$prop = str_replace($onturls,$ontprefixes,$rec['children'][0]['tagData']);
                $dt = str_replace('http://www.w3.org/2001/XMLSchema#','',$rec['children'][1]['tagData']);
		fwrite($fh,"$prop $dt\n");
	}
	fclose($fh);
?>

