<?php
	ini_set('include_path','../inc/:inc/:.');
	require_once('myexpconnect.inc.php');
	require_once('miscfunc.inc.php');
	require_once('functions.inc.php');
	require_once('data.inc.php');
	require_once('sqlfunc.inc.php');
	require_once('snarmfunc.inc.php');
	require_once('xmlfunc.inc.php');
	require_once('orefunc.inc.php');
		
	function printEntity($row,$type,$format=''){
		global $datauri,$domain,$datatypes,$entityns, $mappings, $ontent;
		$template=$mappings[$type];
//		echo "<!-- $type -->\n";
		$idfield=array_search('url',$template);
		$id=$row[$idfield];
		unset($template[$idfield]);
		if (!is_array($template)) return "";
		$useentity=$entityns[$type];
		$otype=$ontent[$type];
		$fullentity="$useentity:$otype";
		$uri = getEntityURI($type,$id,$row,$format);	
		$stag="  <$fullentity rdf:about=\"$uri\">\n";
		$xml=$stag;
		$xml.=getHomepageAndFormats($uri,$type,$id,$row);
		foreach ($template as $field => $property){
			//$xml.="<!-- $field -->\n";
			switch (substr($property, 0, 1)){
			case '+':
				$propbits=explode("+",$property);
				array_shift($propbits);
				$xml.=printCombinedProperty($field,$propbits,$row);
				break;
			case '&':
				$xml.=printEntityProperty($field,substr($property,1),$row[$field]);
				break;
			case '@':
				if (substr($property, 1, 1)=="-")  $xml.=printFunctionProperty(substr($property,2),$row,"","NoDataURI,$format");
				elseif (substr($property, 1, 1)=="#"){
					if ($domain!="Private"){
					 	$xml.=printFunctionProperty(substr($property,2),$row,"","Hash,$format");
					}
					else{
						$xml.=printFunctionProperty(substr($property,2),$row,"",$format);
					}
				}
				elseif (substr($property, 1, 1)=="&"){
					if (substr($property, 2, 1)=="-") $xml.=printFunctionProperty(substr($property,3),$row,$type,"NoDataURI,$format");
					elseif (substr($property, 2, 1)=="%") $xml.=printFunctionProperty(substr($property,3),$row,$type,"EncapsulatedObject,$format");
					else $xml.=printFunctionProperty(substr($property,2),$row,$type,$format);
				}
				elseif (substr($property, 1, 1)=="%") $xml.=printFunctionProperty(substr($property,2),$row,"","EncapsulatedObject,$format");
				else{
					$xml.=printFunctionProperty(substr($property,1),$row,"",$format);
				}
				break;
			case '!':
				break;
			default:
				if ($datatypes[$property]){
					$xml.=printDatatypeProperty($property,$row[$field]);
				}
				else{
					$xml.=printEntityProperty($field,$property,$row[$field],"NoDataURI");
				}	
				break;
			}	
		}
		$etag="  </$fullentity>\n\n";
		$xml.=$etag;
		if ($format=="ore"){
			$xml.=printAggregatedResources($datauri.$type."/".$row['id']);
			clearAggregatedResources($datauri.$type."/".$row['id']);
			$xml.=printProxies($datauri.$type."/".$row['id']);
			clearProxies($datauri.$type."/".$row['id']);
		}	
		return $xml;
	}
	function getHomepageAndFormats($uri,$type,$id,$entity=''){
		global $homepage, $xmluri, $datauri;
		if ($homepage[$type]) $xml.="    <foaf:homepage rdf:resource=\"${uri}.html\"/>\n";
                $xml.="    <dcterms:hasFormat rdf:resource=\"${uri}.rdf\"/>\n";
		if ($xmluri[$type]){
			if ($entity['workflow_id']) $curxmluri=$datauri.str_replace("!",$entity['version'],str_replace("~",$entity['workflow_id'],$xmluri[$type]));
			else $curxmluri=$datauri.$xmluri[$type].$id;
			
			$xml.="    <dcterms:hasFormat rdf:resource=\"$curxmluri\"/>\n";
		}
		return $xml;
	}

	function printUsage($type,$id,$views,$downloads){
		global $datauri;
		return "  <rdf:Description rdf:about=\"$datauri$type/$id\">
    <mevd:viewed rdf:datatype=\"http://www.w3.org/2001/XMLSchema#nonNegativeInteger\">$views</mevd:viewed>
    <mevd:downloaded rdf:datatype=\"http://www.w3.org/2001/XMLSchema#nonNegativeInteger\">$downloads</mevd:downloaded>
  </rdf:Description>\n\n";
	}	
		
	function printCombinedProperty($field,$property,$row){
		global $datauri, $ontent;
		if ($row[$field]=="Blob") $row[$field]="File";
		if ($row[$field]=="Network") $row[$field]="Group";
		$otype=array_search($row[$field],$ontent);
		if ($otype) $row[$field]=$otype;
		$pbits=explode('|',$property[sizeof($property)-1]);
		if (sizeof($property)>1)$uri=$datauri.$row[$field]."/".$row[$property[0]];
		else $uri=$datauri.$row[$field]."/".$row[$pbits[0]];
		if ($row[$pbits[0]] && preg_match('/workflow_version/',$pbits[0])){
			$uri=$datauri."workflows/".$row[$field]."/versions/".$row[$pbits[0]];
		}
		$nsandp=$pbits[1];
		$line="    <$nsandp rdf:resource=\"$uri\" />\n";
		return $line;
	}
	function printEntityProperty($field,$property,$value,$msg=""){
		global $datauri, $ontent;
		$msgs=explode(",",$msg);
		if ($value){
			$pbits=explode('|',$property);
			if (in_array("NoDataURI",$msgs)){
				$uri=$value;
				$nsandp=$pbits[0];
			}
			else{
				$type=array_search($pbits[0],$ontent);
				$uri=$datauri.$type."/".$value;
				$nsandp=$pbits[1];
			}
			$uri=str_replace("&","&amp;",$uri);
			$line="    <$nsandp rdf:resource=\"$uri\" />\n";
			return $line;
		}
	}
	function printFunctionProperty($property,$row,$entity_type="",$msg=""){
		global $datauri, $datatypes, $aggregateprops;
		$pbits=explode('|',$property);
		$msgs=explode(",",$msg);
		if (in_array('ore',$msgs)){
			$row['format']="ore";
		}
		if (in_array('hide',$msgs) && $pbits[0]=="getOREAggregatedResources") return;
		if ($entity_type) $value=call_user_func($pbits[0],$row,$entity_type);
		elseif (in_array('Hash',$msgs)){
			$value=call_user_func($pbits[0],$row,1);
		}
		else $value=call_user_func($pbits[0],$row);
		if (!$pbits[1]) return $value;
		elseif (in_array("EncapsulatedObject",$msgs)){
			$nsandp=$pbits[1];
			if ($value){
				if ($row['format']=="ore" && in_array($pbits[1],$aggregateprops)) $line="<ore:aggregates rdf:resource=\"$value\"/>\n";
				else $line="    <$nsandp>\n      $value\n    </$nsandp>\n";
			}
			return $line;
		}
		elseif (array_key_exists($pbits[1],$datatypes) && $datatypes[$pbits[1]]!=""){
			return printDatatypeProperty($pbits[1],$value,$fh);
		}
		elseif ($pbits[0]=="getComponentsAsResources" || $pbits=="getWorkflowVersions" ) return $value;
		else{
			if (in_array("NoDataURI",$msgs)) $uri=$value;
			else $uri=$datauri.$value;
			$nsandp=$pbits[1];
			if (in_array($nsandp,$aggregateprops) && $row['format']=='ore') $nsandp="ore:aggregates";
			if ($uri) $line="    <$nsandp rdf:resource=\"$uri\" />\n";
			return $line;
		}
	}	
	function printDatatypeProperty($property,$value){
		global $datatypes;
		$value=xmlentities($value);
		
		if (($datatypes[$property]=="nonNegativeInteger" || $datatypes[$property]=="boolean") && !$value) $value="0";
		elseif ($datatypes[$property]=="dateTime"){
			$value=str_replace(" ","T",$value);
			if ($value) $value.="Z";
		}

		$nsandp=$property;			
		if ($datatypes[$property]!="base64Binary"){
			if ($value || $datatypes[$property]=="boolean" || $datatypes[$property]=="nonNegativeInteger"){
				$line="    <$nsandp"; 
				if ($datatypes[$property]) $line.=" rdf:datatype=\"&xsd;".$datatypes[$property]."\"";
				$line.=">".trim($value)."</$nsandp>\n";
			}
			if (($nsandp=="mevd:viewed" || $nsandp=="mevd:downloaded") && $value==0) $line="";

		}
		else{
			$line="    <$nsandp"; 
			if ($datatypes[$property]) $line.=" rdf:datatype=\"&xsd;".$datatypes[$property]."\"";
			//else echo $property."1";
			$line.=">".trim(base64_encode($value))."</$nsandp>\n";
		}
		return $line;
	}

	function getUsedTagsFromTaggings($wherestat){
		$sql="select tag_id from taggings ".$wherestat;
		$res=mysql_query($sql);
		$tagid=array();
		for ($t=0; $t<mysql_num_rows($res); $t++){
			$tagid[mysql_result($res,$t,'tag_id')]++;
		}
		return array_keys($tagid);
	}

	function varpageheader($namespaces){
        	$header="<?xml version=\"1.0\" encoding=\"UTF-8\" ?>

<!DOCTYPE rdf:RDF [\n";
	        foreach ($namespaces as $ent => $ns){
        	        $header.=" <!ENTITY $ent '$ns'>\n";
	        }
        	$header.="]>\n\n<rdf:RDF ";
	        $ents=array_keys($namespaces);
        	for ($e=0; $e<sizeof($ents); $e++){
                	$header.="xmlns:$ents[$e]\t=\"&$ents[$e];\"\n";
	                if ($e<sizeof($ents)-1) $header.="\t";
        	}
	        $header.=">\n";
	        return $header;
	}

	function pageheader(){
		global $ontopath;
                $namespaces['mebase']=$ontopath.'base/';
                $namespaces['meac']=$ontopath.'attrib_credit/';
                $namespaces['meannot']=$ontopath.'.annotations/';
                $namespaces['mepack']=$ontopath.'packs/';
                $namespaces['meexp']=$ontopath.'experiments/';
                $namespaces['mecontrib']=$ontopath.'contributions/';
                $namespaces['mevd']=$ontopath.'viewings_downloads/';
                $namespaces['mecomp']=$ontopath.'components/';
                $namespaces['mespec']=$ontopath.'specific/';
        	$namespaces['rdf']='http://www.w3.org/1999/02/22-rdf-syntax-ns#';
	        $namespaces['rdfs']='http://www.w3.org/2000/01/rdf-schema#';
		$namespaces['owl']='http://www.w3.org/2002/07/owl#';
        	$namespaces['dc']='http://purl.org/dc/elements/1.1/';
	        $namespaces['dcterms']='http://purl.org/dc/terms/';
	        $namespaces['cc']='http://web.resource.org/cc/';
        	$namespaces['foaf']='http://xmlns.com/foaf/0.1/';
	        $namespaces['sioc']='http://rdfs.org/sioc/ns#';
        	$namespaces['ore']='http://www.openarchives.org/ore/terms/';
		$namespaces['dbpedia']='http://dbpedia.org/ontology/';
        	$namespaces['snarm']=$ontopath.'snarm/';
	        $namespaces['xsd']='http://www.w3.org/2001/XMLSchema#';
        	return varpageheader($namespaces);
	}
	function pagefooter(){
        	return "</rdf:RDF>";
	}
	function rdffiledescription($url){
		return "  <rdf:Description rdf:about=\"$url.rdf\">\n    <foaf:primaryTopic rdf:resource=\"$url\"/>\n  </rdf:Description>\n\n";
	}

?>
