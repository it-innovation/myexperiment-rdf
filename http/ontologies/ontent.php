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
                 if (preg_match('/rdf:resource="([^"]+)"/',$lines[$l],$matches)){
                        if ($matches[1] && !preg_match('@(&[a-z]+;|http://)@',$matches[1])){
                                $lines[$l]=str_replace($matches[1],$ontology.$matches[1],$lines[$l]);
                        }
                }
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
