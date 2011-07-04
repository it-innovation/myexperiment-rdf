<?php

//Query 1: Property Domain Class-Property Relations
$queries[1]="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
select distinct ?class ?property where { GRAPH <$remoteont> { ?class rdf:type rdfs:Class . ?property rdfs:domain ?class . FILTER( REGEX(str(?class),'^$filteront'))}}";

//Query 2: Class Property Restictions Class-Property Relations
/*$queries[2]="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
select distinct ?class ?property where {?class rdfs:subClassOf ?sclass . ?sclass rdfs:subClassOf ?a. ?a rdf:type owl:Restriction. ?a owl:onProperty ?property . FILTER( REGEX(STR(?sclass),'^$filteront') && REGEX(STR(?class),'^$filteront'))}";*/

//Query 3: Label and Comment for Classes
$queries[3]="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
select distinct ?class ?label ?comment where { GRAPH <$remoteont> { ?class rdf:type rdfs:Class . OPTIONAL{?class rdfs:label ?label} . OPTIONAL{?class rdfs:comment ?comment} . FILTER(REGEX(str(?class),'^$filteront'))} }";

//Query 4: Superclasses for Classes
$queries[4]="PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
select distinct ?class ?superclass where { GRAPH <$remoteont> { ?class rdfs:subClassOf ?superclass . FILTER(?superclass!=?class && REGEX(str(?class),'^$filteront) && REGEX(str(?superclass),'^$filteront))}}";

//Query 5: Label and Comment for Properties
$queries[5]="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
select distinct ?property ?type ?label ?comment ?range where { GRAPH <$remoteont> { ?property rdf:type ?type . ?property rdf:type rdf:Property . OPTIONAL{?property rdfs:label ?label}  . OPTIONAL{?property rdfs:comment ?comment } . OPTIONAL{?property rdfs:range ?range } FILTER(REGEX(STR(?type),'^http://www.w3.org/1999/02/22-rdf-syntax-ns#Property$') && REGEX(str(?property),'^$filteront'))}}";

//Query 6: Equivalent Classes
/*$queries[6]="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
select distinct ?myclass ?exclass where {{?myclass rdf:type rdfs:Class} UNION {?myclass rdf:type owl:Class}  . ?myclass owl:equivalentClass ?exclass . FILTER( !REGEX(STR(?exclass),'^$filteront') && REGEX(STR(?myclass),'^$filteront'))}";*/

//Query 7: Equivalent Properties
/*$queries[7]="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
select distinct ?myprop ?exprop where { {?myprop rdf:type owl:DatatypeProperty} UNION {?myprop rdf:type owl:ObjectProperty} UNION {?myprop rdf:type rdf:Property}  . ?myprop owl:equivalentProperty ?exprop . FILTER( !REGEX(STR(?exprop),'^$filteront') && REGEX(STR(?myprop),'^$filteront'))}";*/

//Query 8: SubClass Classes
$queries[8]="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
select distinct ?myclass ?exclass where { GRAPH <$remoteont> { ?myclass rdf:type rdfs:Class . ?myclass rdfs:subClassOf ?exclass . FILTER( !REGEX(STR(?exclass),'^$filteront') && !REGEX(STR(?exclass),'^http://www.w3.org/2000/01/rdf-schema#Resource') && REGEX(str(?myclass),'^$filteront'))}}";

//Query 9: SubProperty Properties
$queries[9]="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
select distinct ?myprop ?exprop where { GRAPH <$remoteont> { ?myprop rdf:type rdf:Property . ?myprop rdfs:isDefinedBy <$filteront> . ?myprop rdfs:subPropertyOf ?exprop . FILTER( !REGEX(STR(?exprop),'^$filteront') && REGEX(str(?myprop),'^$filteront')) }}";

$filteront2=str_replace("#","",$filteront);
//Query 10: Imported Ontologies
/*$queries[10]="PREFIX owl: <http://www.w3.org/2002/07/owl#>
select distinct ?import_ont where { {<$filteront> owl:imports ?import_ont} union {<$url> owl:imports ?import_ont} union {<$filteront2>  owl:imports ?import_ont}}";*/
//echo htmlentities($queries[10]);

//Query 11: Ontology Information
/*$queries[11]="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
select distinct ?schema ?prop ?val ?label where {?schema rdf:type rdf:Description . ?schema ?prop ?val . OPTIONAL { ?val rdfs:label ?label } . FILTER( REGEX(STR(?schema),'^$filteront2$') || REGEX(STR(?schema),'^$url$') || REGEX(STR(?schema),'^$filteront$')) }";*/

//Query 12: Instances of Classes
$queries[12]="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
select distinct ?class ?instance where {?class rdf:type rdfs:Class . ?instance rdf:type ?class . FILTER(REGEX(STR(?instance),'^$filteront.+') && REGEX(str(?class),'^$filteront')) } }";

//Query 13: Listed Domains and Ranges
/*$queries[13]="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
select distinct ?property ?dorr where { {?property rdfs:domain ?tmp} UNION {?property rdfs:range ?tmp} . ?tmp owl:unionOf ?list . ?property ?dorr ?tmp . FILTER(REGEX(STR(?property),'^$filteront'))}";*/


?>
