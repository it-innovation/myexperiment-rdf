#!/usr/bin/php
<?php
	include('include.inc.php');
	include('4storefunc.inc.php');
	if ($argv[1]==$triplestore){
		if (modularizedFullTestSparqlQueryClient($argv[1])) exit(0);
	}
	elseif( $argv[1]=="ontologies"){
		if (ontologiesFullTestSparqlQueryClient($argv[1])) exit(0);
	}
	exit(1);
?>
