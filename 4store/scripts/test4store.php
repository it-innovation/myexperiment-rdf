#!/usr/bin/php
<?php
	include('include.inc.php');
	include('4storefunc.inc.php');
	if ($argv[1]==$triplestore){
		echo modularizedFullTestSparqlQueryClient($argv[1]);	
		exit;
	}
	elseif ( $argv[1]=="ontologies"){
		echo ontologiesFullTestSparqlQueryClient($argv[1]);
		exit;
	}
	echo '-1';
?>
