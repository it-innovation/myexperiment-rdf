<?php
$ontprefixes=array('rdf:','rdfs:','owl:','xsd:','dc:','dcterms:','foaf:','sioc:','ore:','cc:','skos:','snarm:','mebase:','meannot:','mepack:','meexp:','mecontrib:','mevd:','mecomp:','mespec:');
$onturls=array(
       	'http://www.w3.org/1999/02/22-rdf-syntax-ns#',
        'http://www.w3.org/2000/01/rdf-schema#',
       	'http://www.w3.org/2002/07/owl#',
        'http://www.w3.org/2001/XMLSchema#',
       	'http://purl.org/dc/elements/1.1/',
        'http://purl.org/dc/terms/',
       	'http://xmlns.com/foaf/0.1/',
        'http://rdfs.org/sioc/ns#',
        'http://www.openarchives.org/ore/terms/',
       	'http://web.resource.org/cc/',
        'http://www.w3.org/2004/02/skos/core#',
        'http://rdf.myexperiment.org/ontologies/snarm/',
       	'http://rdf.myexperiment.org/ontologies/base/',
        'http://rdf.myexperiment.org/ontologies/annotations/',
       	'http://rdf.myexperiment.org/ontologies/packs/',
        'http://rdf.myexperiment.org/ontologies/experiments/',
       	'http://rdf.myexperiment.org/ontologies/contributions/',
        'http://rdf.myexperiment.org/ontologies/viewings_downloads/',
       	'http://rdf.myexperiment.org/ontologies/components/',
       	'http://rdf.myexperiment.org/ontologies/specific/'
);

function getDatatypes(){
	global $ldpath;
	$dtfile=file($ldpath.'rdfgen/datatypes.txt');
	foreach ($dtfile as $dt){
		$dtbits = explode(" ",$dt);
		$datatypes[trim($dtbits[0])]=trim($dtbits[1]);
	}
	return $datatypes;
}
?>
