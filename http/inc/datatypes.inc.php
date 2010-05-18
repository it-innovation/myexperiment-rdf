<?php
function getExternalDatatypeProperties(){
	$datatypes['dc:title']="string";
        $datatypes['dc:description']="string";
        $datatypes['dc:created']="dateTime";
        $datatypes['dc:modified']="dateTime";
        $datatypes['dc:type']="string";
        $datatypes['dcterms:title']="string";
        $datatypes['dcterms:description']="string";
        $datatypes['dcterms:created']="dateTime";
        $datatypes['dcterms:modified']="dateTime";
        $datatypes['dcterms:type']="string";
	$datatypes['dcterms:format']="string";
	$datatypes['dcterms:identifier']="string";
        $datatypes['foaf:based-near']="string";
        $datatypes['foaf:name']="string";
	$datatypes['foaf:homepage']="anyURI";
	$datatypes['foaf:mbox']="anyURI";
	$datatypes['sioc:avatar']="anyURI";
        $datatypes['sioc:name']="string";
	return $datatypes;
}
function getInternalDatatypeProperties(){
	$dtfiles=array("mebase.txt", "meac.txt","meannot.txt","mepack.txt","meexp.txt","mecontrib.txt","mevd.txt","mespec.txt","snarm.txt");
	return getDatatypesFromFile($dtfiles);
}
function getDatatypesFromFile($files){
	global $cwd;
	$datatypes=array();
	foreach ($files as $file){
		$lines=file($cwd."/datatypes/$file");
		foreach ($lines as $line){
			$kv=explode(" ",str_replace("\n","",$line));
			$datatypes[$kv[0]]=$kv[1];
		}
	}
	return $datatypes;
}	
function getDatatypeProperties($ontology,$filename,$ns,$ts){
	global $guidedatauri;
	require_once('xmlfunc.inc.php');
	$query="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
	PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
	select distinct ?property ?datatype where {?property rdfs:isDefinedBy <$ontology> . ?property rdf:type <http://www.w3.org/2002/07/owl#DatatypeProperty> . ?property rdfs:range ?datatype}";
	$query=rawurlencode($query);
	if ($ts=="private") $url="$guidedatauri$ts/sparql?query=$query";
	else $url="$guidedatauri/sparql?query=$query";
	$fh=fopen($url,"r");
	$results="";
	while ($line = fgets($fh, 4096)) {
		$results.=$line;
	}
	fclose($fh);
	$parsetree=parseXML($results);
	$results=$parsetree[0]['children'][1]['children'];
	$datatypes=array();
	for ($r=0; $r<sizeof($results); $r++){
		$result=$results[$r]['children'];
		$property=$result[0]['children'][0]['tagData'];
		$datatype=$result[1]['children'][0]['tagData'];
		$lastchar=substr($ontology,-1);
		$propbits=explode($lastchar,$property);
		$dtbits=explode("#",$datatype);
		$datatypes[$ns.$propbits[sizeof($propbits)-1]]=$dtbits[sizeof($dtbits)-1];
		echo $propbits[sizeof($propbits)-1]." -> ".$dtbits[sizeof($dtbits)-1];
	}
	$fh=fopen($cwd."datatypes/$filename","w");
	foreach ($datatypes as $key => $value){
		fwrite($fh,"$key $value\n");
	}
	fwrite($fh,"");
	fclose($fh);
}
		
?>
