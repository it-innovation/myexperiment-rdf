<?php
        $domain='protected';
        include('../include.inc.php');
        include('genrdf.inc.php');
        $ontology="$ontopath$_GET[ontology]/";
        $lines = file($ontology);
        $l=0;
        while (strpos($lines[$l],'rdf:about="'.$_GET['entity'].'"')==0){
                $l++;   
        }
        $entity=str_replace($_GET['entity'],$ontology.$_GET['entity'],$lines[$l]);
        $l++;
        while (strlen(trim($lines[$l]))>0){
                $entity.=$lines[$l];
                $l++;
        }
        header('Content-type: application/rdf+xml');
        echo pageheader();
        echo $entity;
        echo pagefooter(); 
?> 
