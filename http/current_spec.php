<?php
include('include.inc.php');
require_once('xmlfunc.inc.php');
require_once('4storefunc.inc.php');
//$reasonedont = "file://${datapath}${triplestore}/${triplestore}_reasoned.owl";


//Query 1: Property Domain Class-Property Relations
$query[1]="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
select ?class ?property where { ?class rdf:type owl:Class . ?property rdfs:domain ?class . FILTER( REGEX(STR(?class),'^$ontopath'))}";

//Query 2: Class Property Restictions Class-Property Relations
$query[2]="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
select ?class ?property where { ?class rdfs:subClassOf ?sclass . ?sclass rdf:type owl:Restriction . ?sclass owl:onProperty ?property . FILTER( REGEX(STR(?class),'^$ontopath'))}";

//Query 3: Label and Comment for Classes
 $query[3]="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
select ?class ?label ?comment where { ?class rdf:type owl:Class . ?class rdfs:label ?label . ?class rdfs:comment ?comment . FILTER( REGEX(STR(?class),'^$ontopath'))}";

//Query 4: Superclasses for Classes
$query[4]="PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
select ?class ?superclass where { ?class rdfs:subClassOf ?superclass . FILTER(?superclass!=?class && REGEX(STR(?class),'^$ontopath') && REGEX(STR(?superclass),'^$ontopath'))}";

//Query 5: Label and Comment for Properties
$query[5]="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
select ?property ?label ?comment ?property_type where { ?property rdfs:label ?label . ?property rdfs:comment ?comment . ?property rdf:type ?property_type . { ?property rdf:type <http://www.w3.org/2002/07/owl#ObjectProperty> } union { ?property rdf:type <http://www.w3.org/2002/07/owl#DatatypeProperty> } . FILTER(REGEX(STR(?property),'^$ontopath'))}";

//Query 6: Equivalent Classes
$query[6]="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
select distinct ?myclass ?exclass where { ?myclass rdf:type owl:Class . ?myclass owl:equivalentClass ?exclass . FILTER( !REGEX(STR(?exclass),'^$ontopath') && REGEX(STR(?myclass),'^$ontopath'))}";

//Query 7: Equivalent Properties
$query[7]="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
select distinct ?myprop ?exprop where {{?myprop rdf:type owl:DatatypeProperty} UNION {?myprop rdf:type owl:ObjectProperty} . ?myprop owl:equivalentProperty ?exprop . FILTER( !REGEX(STR(?exprop),'^$ontopath') && REGEX(STR(?myprop),'^$ontopath'))}";

//Query 8: SubClass Classes
$query[8]="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
select distinct ?myclass ?exclass where {  ?myclass rdf:type owl:Class . ?myclass rdfs:subClassOf ?exclass . FILTER( !REGEX(STR(?exclass),'^$ontopath') && !REGEX(STR(?exclass),'^http://www.w3.org/2000/01/rdf-schema#Resource') && REGEX(STR(?myclass),'^$ontopath'))}";

//Query 9: SubProperty Properties
$query[9]="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
select distinct ?myprop ?exprop where {{?myprop rdf:type owl:DatatypeProperty} UNION {?myprop rdf:type owl:ObjectProperty} . ?myprop rdfs:subPropertyOf ?exprop . FILTER( !REGEX(STR(?exprop),'^$ontopath') && REGEX(STR(?myprop),'^$ontopath'))}";

$res=sparqlQueryClientMultiple($triplestore,$query,100000,300,true);

$tableres1=array();
if (queryFailed($res[1])){
	$errs[]="Property Domain Class-Property Relations Query Failed";
        $tableres=array();
}
else $tableres1=tabulateSparqlResultsAssoc(parseXML($res[1]));


$tableres2=array();
if (queryFailed($res[2])){
        $errs[]="Class Property Restictions Class-Property Relations Query Failed";
        $tableres2=array();
}
else $tableres2=tabulateSparqlResultsAssoc(parseXML($res[2]));
$classprops=array_multiunique(array_merge($tableres1,$tableres2));

if (queryFailed($res[3])){
        $errs[]="Label and Comment for Classes Query Failed";
        $classes=array();
}
else{
        $classes=tabulateSparqlResultsAssoc(parseXML($res[3]));
        $classes=setKey($classes,'class');
}

if (queryFailed($res[4])){
        $errs[]="Superclasses for Classes Query Failed";
        $tableres4=array();
}
else $tableres4=tabulateSparqlResultsAssoc(parseXML($res[4]));
foreach ($tableres4 as $sclass){
	$classes[$sclass['class']]['subclassof'][]=$sclass['superclass'];
}

if (queryFailed($res[5])){
        $errs[]="Label and Comment for Properties Failed";
        $properties=array();
}
else{
        $properties=tabulateSparqlResultsAssoc(parseXML($res[5]));
        $properties=setkey($properties,'property');
}
foreach ($classprops as $classprop){
	//print_r($classprop);
        $classes[$classprop['class']]['property'][]=$classprop['property'];
        if (myexp_namespace(replace_namespace($classprop['property']))) $properties[$classprop['property']]['inclass'][]=$classprop['class'];
}

ksort($classes);
ksort($properties);


if (queryFailed($res[6])){
        $errs[]="Equivalent Classes Query Failed";
        $tab6=array();
}
else $tab6=tabulateSparqlResultsAssoc(parseXML($res[6]));


if (queryFailed($res[7])){
        $errs[]="Equivalent Properties Query Failed";
        $tab7=array();
}
else $tab7=tabulateSparqlResultsAssoc(parseXML($res[7]));


if (queryFailed($res[8])){
        $errs[]="SubClass Classes Query Failed";
        $tab8=array();
}
$tab8=tabulateSparqlResultsAssoc(parseXML($res[8]));


if (queryFailed($res[9])){
	$errs[]="SubProperty Properties Query Failed";
        $tab9=array();
}
$tab9=tabulateSparqlResultsAssoc(parseXML($res[9]));

$pagetitle="Ontology Specification";
$ignoreloc=1;
include('header.inc.php');
if (sizeof($errs)>0){
        echo "    <!-- Errors -->\n";
        echo "    <div class=\"red\">\n";
        echo "      <h3>Errors:</h3>\n";
        foreach ($errs as $err){
	        echo "      <p>$err</p>\n";
        }
        echo "    </div>\n    <br/>\n";
}
else echo "    <!-- Correct -->\n";

//Print Class Listing
echo "  <div class=\"purple\">\n";
$c=0;
echo "    <h3>Classes</h3>\n";
$oldns="";
$text="  \n";
foreach ($classes as $cname => $class){
	$cbits=explode("/",$cname);
	$curns=$cbits[sizeof($cbits)-2];
	$caname=replace_namespace($cname);
	if ($curns!=$oldns){
		echo substr($text,0,-3);
		$text="";
		if ($oldns) echo "    </p>\n";
		$text.="    <h4>".ucwords(str_replace("_"," ",$curns))."</h4>\n    <p>\n";
	}
	else{
		echo $text;
		$text="";
	}
	$text.="      <a href=\"#".$caname."\">".$caname."</a>, \n";
	$c++;
	$oldns=$curns;
}
echo substr($text,0,-3);
echo "\n    </p>\n  </div>\n  <br/>\n";

//Print Properties Listing
echo "  <div class=\"purple\">\n";
$p=0;
echo "    <h3>Properties</h3>\n    <p>\n";
$text="";
$oldns="";
foreach ($properties as $pname => $property){
        $pbits=explode("/",$pname);
	$curns=$pbits[sizeof($pbits)-2];
	$paname=replace_namespace($pname);
	if ($curns!=$oldns){
                echo substr($text,0,-3);
                $text="";
                if ($oldns) echo "    </p>\n";
                $text.="    <h4>".ucwords(str_replace("_"," ",$curns))."</h4>\n    <p>\n";
        }
	else{
                echo $text;
                $text="";
        }
	$text.="      <a href=\"#".$paname."\">".$paname."</a>, \n";
        $p++;
	$oldns=$curns;
}
echo substr($text,0,-3);
echo "\n    </p>\n  </div>\n  <br/>\n";

//Borrowed Classes/Properties Mappings
echo "  <div class=\"purple\">\n";
echo "    <h3>Borrowed Classes and Properties</h3>\n";
echo "    <h4>Equivalent Classes</h4>\n    <p>\n";
foreach ($tab6 as $eqclass){
	$myclass=replace_namespace($eqclass['myclass']);
        echo "<a href=\"#$myclass\">$myclass</a> - ".replace_namespace($eqclass['exclass'])."<br/>\n";
}
if (sizeof($tab6)==0) echo "none";
echo "    </p>\n      <h4>Equivalent Properties</h4>\n    <p>\n";
foreach ($tab7 as $eqprop){
	$myprop=replace_namespace($eqprop['myprop']);
        echo "<a href=\"#$myprop\">$myprop</a> - ".replace_namespace($eqprop['exprop'])."<br/>\n";
}
if (sizeof($tab7)==0) echo "none";
echo "    </p>\n      <h4>Subclasses of</h4>\n    <p>\n";
foreach ($tab8 as $subclass){
	$myclass=replace_namespace($subclass['myclass']);
        echo "<a href=\"#$myclass\">$myclass</a> - ".replace_namespace($subclass['exclass'])."<br/>\n";
}
if (sizeof($tab8)==0) echo "none";
echo "    </p>\n      <h4>Subproperties of</h4>\n    <p>\n";
foreach ($tab9 as $subprop){
	$myprop=replace_namespace($subprop['myprop']);
        echo "<a href=\"#$myprop\">$myprop</a> - ".replace_namespace($subprop['exprop'])."<br/>\n";
}
if (sizeof($tab9)==0) echo "none";
echo "    </p>\n  </div>\n  <br/>\n";

//Individual Classes
echo "  <h2>Classes</h2>\n";
foreach ($classes as $cname => $class){
	$cbits=explode("/",$cname);
	$caname=replace_namespace($cname);
	echo "  <div class=\"yellow\">\n";
	echo "  <a name=\"".$caname."\"/>\n    <h3>".$cname."</h3>\n    <p><b>Label:</b> ".$class['label']."\n      <br/>\n      <b>Comment:</b> ".$class['comment']."\n      <br/>\n      <b>Subclass of:</b>\n";
	$sc=0;
	if ($class['subclassof']){
		foreach ($class['subclassof'] as $subclassof){
			$scaname=replace_namespace($subclassof);
			echo "        <a href=\"#".$scaname."\">".$scaname."</a>";
			if ($sc<sizeof($class['subclassof'])-1) echo ",\n";
			$sc++;
		}
	}
	echo "\n      <br/>\n      <b>Properties:</b>\n";
	$p=0;
	if ($class['property']){
		foreach ($class['property'] as $property){
			$pname=replace_namespace($property);
			if (strpos($property,":")>0 && !myexp_namespace($pname)) echo "        ".$pname;
	                else echo "        <a href=\"#$pname\">".$pname."</a>";
        	        if ($p<sizeof($class['property'])-1) echo ",\n";
                	$p++;
		}
	}
	echo "\n    </p>\n";
	echo "  </div>\n  <br/>\n";
}

//Individual Properties
echo "<h2>Properties</h2>\n";
foreach ($properties as $pname => $property){
	$pbits=explode("/",$pname);
	$paname=replace_namespace($pname);
	$ptbits=explode("#",$property['property_type']);
 	echo "  <div class=\"green\">\n";
	echo "    <a name=\"".$paname."\"/>\n    <h3>".$pname."</h3>\n    ";
	echo "    <p>\n      <b>Type:</b> ".$ptbits[1]."<br/>\n      <b>Label:</b> ".$property['label']."</br/>\n      <b>Comment:</b> ".$property['comment']."\n      <br/>      <b>Used in classes:</b>\n";
	$c=0;
	foreach ($property['inclass'] as $class){
		$caname=replace_namespace($class);
                echo "        <a href=\"#".$caname."\">".$caname."</a>";
                if ($c<sizeof($property['inclass'])-1) echo ",\n";
                $c++;
        }
	echo "\n    </p>\n  </div>\n  <br/>\n";
}

include('footer.inc.php');
?>

