<?php
	//Current Working Direcetory
        $cwd="/var/www/html/rdf/";
        //Setup include_path
        ini_set('include_path',ini_get('include_path').":$cwd/inc/:");
	//The URI for RDF data
	$datauri="http://rdf.myexperiment.org/";
	//The URI for RDF data in the guide"
        $guidedatauri="http://rdf.myexperiment.org/";
	//The URI path for the ontologies
	$ontopath="http://rdf.myexperiment.org/ontologies/";
        //The filesystem path for RDF data
	$datapath="/var/data/";
	//The myExperiment instance you are egnerating RDF for
        $myexp_inst="http://www.myexperiment.org/";
	//The salt to encrypt hidden values
        $salt="changeme";
?>
