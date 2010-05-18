<?php
	ini_set('include_path','/var/www/html/rdf/inc');
	include('xmlfunc.inc.php');
	include('ontconnect.inc.php');
	$sql="select * from ontologies where id=$argv[1]";
	$res=mysql_query($sql);
	$ont=mysql_fetch_assoc($res);
	$fh=fopen("/var/data/ontologies/remoteont/$argv[1]_$ont[name].owl",'r');
	$xml="";
	while (!feof($fh)){
		$data=fgets($fh,8096);
		$xml.=$data;	
		if (preg_match("!<\/rdf:RDF>!",$data)) break;
	}
	$pxml=parseXML($xml,$ont['namespace']);
	$etypes=array("owl:Class","owl:ObjectProperty","owl:DatatypeProperty","rdfs:Class","rdf:Property");
	foreach ($pxml[0]['children'] as $entno => $ent){
		if (in_array($ent['name'],$etypes)){
			$nodefinedby=1;
			if (is_array($ent['children'])){
				foreach ($ent['children'] as $pno => $prop){
					if ($prop['name']=="rdfs:isDefinedBy"){
						$nodefinedby=0;
						break;
					}
				}
			}
			if ($nodefinedby){
				$pxml[0]['children'][$entno]['children'][]=array('name'=>'rdfs:isDefinedBy','attrs'=>array('rdf:resource'=>$ont['namespace']));
			}
		}
	}
//	print_r($pxml);
	$nxml=generateXML($pxml);
	echo $nxml;
?>
