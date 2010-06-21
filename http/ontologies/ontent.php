<?php
        $domain='protected';
        include('../include.inc.php');
        include('genrdf.inc.php');
        $ontology="$ontopath$_GET[ontology]/";
        $lines = file($ontology);
        $l=0;
	$match = 'rdf:about="'.$_GET['entity'].'"';
        while (strpos($lines[$l],$match)==0 and $l<sizeof($lines)){
                $l++;   
        }
        $entity=str_replace($_GET['entity'],$ontology.$_GET['entity'],$lines[$l]);
        $l++;
        while (strlen(trim($lines[$l]))>0 and $l<sizeof($lines)){
                $entity.=$lines[$l];
                $l++;
        }
	if ($entity){
  		header('Content-type: application/rdf+xml');
        	echo pageheader();
        	echo $entity;
        	echo pagefooter(); 
	}
	else header("HTTP/1.1 404 Not Found");
?> 
