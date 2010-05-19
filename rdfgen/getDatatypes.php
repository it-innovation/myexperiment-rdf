#!/usr/bin/php
<?php
	include('inc/xmlfunc.inc.php');
        $ph=popen("/usr/local/bin/4s-query myexp_public \"PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> PREFIX owl: <http://www.w3.org/2002/07/owl#> PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#> select ?prop ?dt where {?prop rdf:type owl:DatatypeProperty . ?prop rdfs:range ?dt}\"",'r');
        $res="";
        while (!feof($ph)) {
                $res.=fgets($ph, 4096);
        }
	$resarr=parseXML($res);
	print_r($resarr);


?>

