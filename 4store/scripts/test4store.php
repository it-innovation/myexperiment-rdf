#!/usr/bin/php
<?php
	include('/var/www/html/rdf/inc/4storefunc.inc.php');
	if ($argv[1]=="myexp_public" || $argv[1]=="myexp_private"){
		echo modularizedFullTestSparqlQueryClient($argv[1]);	
		exit;
	}
	elseif ( $argv[1]=="ontologies"){
		echo modularizedFullTestSparqlQueryClient($argv[1]);
		exit;
	}
	echo '-1';
?>
