<?php
function parseXML($xmldata,$localns=""){
	global $x2ans;
	if (!$xmldata) return array();
	$x2ans=array();
	$doc=new DOMDocument();
	$doc->substituteEntities = true;
        $doc->loadXML($xmldata);
	$xmldataex=$doc->saveXML();
	$reader = new XMLReader();
	$reader->XML($xmldataex);
	$array['version']=$doc->xmlVersion;
        $array['encoding']=$doc->xmlEncoding;
	while ($reader->next()){
		$domnode[0]=$reader->expand();
	}
	foreach($domnode as $dn) $array[]=xml2array($dn,true);
	if ($localns){
		if (!$array[0]['attrs']['xmlns']) $array[0]['attrs']['xmlns']=$localns;
		if (!$array[0]['attrs']['xml:base']) $array[0]['attrs']['xml:base']=$localns;
	}
	return $array;
}
function generateXML($array){
	if (!$array['encoding']) $array['encoding']="UTF-8";
	if (!$array['version']) $array['version']="1.0";
	$xml="<?xml version=\"$array[version]\" encoding=\"$array[encoding]\" ?>\n\n";
	unset($array['encoding']);
	unset($array['version']);
	foreach($array as $arr)$xml.=array2xml($arr);
	return $xml;
}

function xml2array($domnode,$topelement=false){
	global $x2ans;
	$nodearray = array();
	if (!$topelement){
		$domnode = $domnode->firstChild;
	}
     	while(!is_null($domnode)){
		$currentnode = $domnode->nodeName;
		if($currentnode!="#comment"){
		$cnbits=explode(":",$currentnode);
		if (sizeof($cnbits)>1){
			if (!$x2ans["xmlns:$cnbits[0]"]){
                        	$x2ans["xmlns:$cnbits[0]"]=$domnode->lookupNamespaceURI($cnbits[0]);
                        }
		}
		$elementarray=array();
		switch ($domnode->nodeType){
         	  case XML_TEXT_NODE:
          		if(!(trim($domnode->nodeValue) == "")) $nodeind['tagData'] = $domnode->nodeValue;
        		break;
		  case XML_ELEMENT_NODE:
          		if($domnode->hasAttributes()){
            			$attributes = $domnode->attributes;
				foreach($attributes as $index => $domobj){
					if ($domobj->prefix){
						$prefix=$domobj->prefix;
						if (!$x2ans["xmlns:$prefix"]){
							$x2ans["xmlns:$prefix"]=$domnode->lookupNamespaceURI($prefix);
						}
						$pname="$prefix:".$domobj->name;
					}
					else{
						$pname=$domobj->name;
					}
              				$elementarray[$pname] = $domobj->value;
             			}
           		}
         		break;
		}
		if($domnode->hasChildNodes()){
		        // $nodearray[$currentnode][] = xml2array($domnode);
			//$tmparr=xml2array($domnode);
			//$tmparr['name']=$currentnode;
			$nodeind['name']=$currentnode;
			if(isset($elementarray)){
          			// $nodearray[$currentnode][$currnodeindex]['attrs'] = $elementarray;
	  			$nodeind['attrs']=$elementarray;
          		}
			$nodeind['name']=$currentnode;
			if (is_object($domnode->firstChild) && !is_object($domnode->firstChild->nextSibling) && $domnode->firstChild->nodeValue) $nodeind['tagData']=$domnode->firstChild->nodeValue;
			else $nodeind['children']=xml2array($domnode);

        	}
       		else{
         		if(isset($elementarray) && $domnode->nodeType != XML_TEXT_NODE){
				$nodeind['name']=$currentnode;
				$nodeind['attrs']=$elementarray;
        	  	}
        	}
		if (isset($nodeind)){
			$nodearray[]=$nodeind;
			$nodeind=null;
		}
		}
	     	$domnode = $domnode->nextSibling;
      	}
	if($topelement){
		if (!isset($x2ans['xmlns:rdfs'])) $x2ans['xmlns:rdfs']="http://www.w3.org/2000/01/rdf-schema#";
		
		$nodearray[0]['attrs']=$x2ans;
		return $nodearray[0];
	}
    	return $nodearray;
}

function array2xml($array){
	if ($array['name']) $xml="<$array[name]";
	if (is_array($array['attrs'])){
		foreach ($array['attrs'] as $attr => $val){
			$xml.=" $attr=\"$val\"";
		}
	}
	if ($array['tagData']){
		$xml.=">".xmlentities($array['tagData'])."</$array[name]>";
	}
	elseif (sizeof($array['children'])>0){
		if ($array['name']) $xml.=">\n";
		foreach( $array['children'] as $k => $v){
			$subxml=explode("\n",array2xml($v));
			if (sizeof($subxml)==1 && !preg_match("/>$/",$subxml[0])){
				$xml=rtrim($xml);
				$xml.=$subxml[0];
			}
			else{
				foreach ($subxml as $line){
					$xml.="  ".$line."\n";
				}
			}
		}
		if ($array['name']) $xml.="</$array[name]>";
	}
	else $xml.="/>";
	return $xml;
}

$uris = array("","http://purl.org/dc/elements/1.1/", "http://purl.org/dc/terms/","http://rdfs.org/sioc/ns#","http://web.resource.org/cc/","http://xmlns.com/foaf/0.1/", $ontopath."base/", $ontopath."attrib_credit/",$ontopath."annotations/",$ontopath."packs/",$ontopath."experiments/",$ontopath."snarm/",$ontopath."viewings_downloads/",$ontopath."contributions/",$ontopath."specific/",$ontopath."testing_specific/","http://www.openarchives.org/ore/terms/",$ontopath."wordnet/",$ontopath."questions/","http://www.w3.org/1999/02/22-rdf-syntax-ns#","http://www.w3.org/2000/01/rdf-schema#","http://www.w3.org/2002/07/owl#","http://www.w3.org/2001/XMLSchema#","http://xmlns.com/wot/0.1/",$ontopath."components/","http://www.w3.org/2004/02/skos/core#",$datauri);
$xmlpents = array("","dc:", "dcterms:","sioc:","cc:","foaf:","mebase:","meac:","meannot:","mepack:","meexp:","snarm:","mevd:","mecontrib:","mespec:","mespec:","ore:","mewn:","meq:","rdf:","rdfs:","owl:","xsd:","wot:","mecomp:","skos:","medata:");

function formatXML($parsedxml,$level=0){
	$formattedxml="";
	for ($i=0; $i<sizeof($parsedxml); $i++){
		for ($s=0; $s<$level; $s++) $formattedxml.="  ";
		$formattedxml.="&lt;".$parsedxml[$i]['name'];
		$attrkeys=array_keys($parsedxml[$i]['attrs']);
		for ($j=0; $j<sizeof($attrkeys); $j++){
			$formattedxml.=" ".$attrkeys[$j]."=\"".$parsedxml[$i]['attrs'][$attrkeys[$j]]."\"";
		}
		if (sizeof($parsedxml[$i]['children'])>0){
			$formattedxml.="&gt;\n";
			$formattedxml.=formatXML($parsedxml[$i]['children'],$level+1);
			for ($s=0; $s<$level; $s++) $formattedxml.="  ";
			$formattedxml.="&lt;/".$parsedxml[$i]['name']."&gt;\n";
		}
		elseif ($parsedxml[$i]['tagData']) $formattedxml.="&gt;".$parsedxml[$i]['tagData']."&lt;/".$parsedxml[$i]['name']."&gt;\n";
		else $formattedxml.=" /&gt;\n";
	}
	return $formattedxml;
}
/*function tabulateSparqlResults($parsedxml){
	$vars=$parsedxml[0]['children'][0]['children'];
	for ($v=0; $v<sizeof($vars); $v++){
		$table['vars'][$v]=$vars[$v]['attrs']['NAME'];
	}
	$recs=$parsedxml[0]['children'][1]['children'];
	for ($r=0; $r<sizeof($recs); $r++){
		for ($v=0; $v<sizeof($vars); $v++){
			$varname=$recs[$r]['children'][$v]['attrs']['NAME'];
			$varnum=array_search($varname,$table['vars']);
			$table[$r][$varnum]=$recs[$r]['children'][$v]['children'][0]['tagData'];
		}
	}
	return $table;
}*/
function tabulateSparqlResults($parsedxml){
  	$vars=$parsedxml[0]['children'][0]['children'];
        for ($v=0; $v<sizeof($vars); $v++){
                $table['vars'][$v]=$vars[$v]['attrs']['name'];
        }
        $recs=$parsedxml[0]['children'][1]['children'];
        for ($r=0; $r<sizeof($recs); $r++){
                for ($v=0; $v<sizeof($vars); $v++){
                        $varname=$recs[$r]['children'][$v]['attrs']['name'];
			if ($varname){
	                	$varnum=array_search($varname,$table['vars']);
                        	$table[$r][$varnum]=$recs[$r]['children'][$v]['tagData'];
			}
                }
        }
        return $table;
}
function tabulateJenaSparqlResults($parsedxml){
        $vars=$parsedxml[0]['children'][0]['children'];
        for ($v=0; $v<sizeof($vars); $v++){
                $table['vars'][$v]=$vars[$v]['attrs']['name'];
        }
        $recs=$parsedxml[0]['children'][1]['children'];
        for ($r=0; $r<sizeof($recs); $r++){
                for ($v=0; $v<sizeof($vars); $v++){
                        $varname=$recs[$r]['children'][$v]['attrs']['name'];
                        if ($varname){
                                $varnum=array_search($varname,$table['vars']);
                                $table[$r][$varnum]=$recs[$r]['children'][$v]['children'][0]['tagData'];
                        }
                }
        }
        return $table;
}


function convertTableToCSV($tab){
	foreach ($tab as $rname => $row){	
		$csvline="";
		for ($i=0; $i<sizeof($row); $i++){
			$csvline.='"'.str_replace('"','"""',$row[$i]).'",';
		}
		$csv.=substr($csvline,0,-1)."\n";
	}
	return $csv;
}
function convertTableToCSVMatrix($tab){
	if (sizeof($tab['vars'])!=2) return "ERROR: Query must select exactly two variables to draw a CSV matrix";
	$tabnn=array_slice($tab,1);
	$uniqx=array();
	$uniqy=array();
	foreach ($tabnn as $row){
		if (!in_array($row[0],$uniqx))$uniqx[]=$row[0];
		if (!in_array($row[1],$uniqy))$uniqy[]=$row[1];
	}
	sort($uniqx);
	sort($uniqy);
	foreach ($tabnn as $row){
		$matrix[$row[0]][$row[1]]++;
	}
	$csvmatrix=" ,";
	for ($y=0; $y<sizeof($uniqy); $y++){
		$csvmatrix.='"'.str_replace('"','"""',$uniqy[$y]).'"';
		if ($y<sizeof($uniqy)-1) $csvmatrix.=',';
	}
	$csvmatrix.="\n";
	for ($x=0; $x<sizeof($uniqx); $x++){
		$csvmatrix.='"'.str_replace('"','"""',$uniqx[$x]).'",';
		for ($y=0; $y<sizeof($uniqy); $y++){
			if ($matrix[$uniqx[$x]][$uniqy[$y]]) $csvmatrix.=$matrix[$uniqx[$x]][$uniqy[$y]];
			else $csvmatrix.=' ';
			if ($y<sizeof($uniqy)-1) $csvmatrix.=',';
		}
		$csvmatrix.="\n";
	}
	return $csvmatrix;
}


function drawSparqlResultsTable($table){
	$tablehtml="<table class=\"listing\">\n  <tr>";
	for ($v=0; $v<sizeof($table['vars']); $v++){
		$tablehtml.="<th>".$table['vars'][$v]."</th>";
	}
	$shade=" class=\"shade\"";
	$tablehtml.="</tr>\n";
	for ($r=0; $r<sizeof($table)-1; $r++){
		$tablehtml.="  <tr>";
		for($v=0; $v<sizeof($table['vars']); $v++){
			$tablehtml.="<td$shade>".$table[$r][$v]."</td>";
		}
		$tablehtml.="</tr>\n";
		if (!$shade) $shade=" class=\"shade\"";
		else $shade="";
	}
	$tablehtml.="</table>\n";
	return $tablehtml;
}
function xmlentities($data){
	$find=array("&#xD;","&","<",">","'",'"',);
	$replace=array(" ","&amp;","&lt;","&gt;","&apos;","&quot;");
	$replaced=str_replace($find, $replace, $data);
	for( $c=0; $c<strlen($replaced); $c++){
		$char=substr($replaced,$c,1);
		if ((ord($char)<32 || ord($char)>126) && (!(ord($char)>8 && ord($char)<14))) $replaced=substr_replace($replaced,'?',$c,1);
	}
	return $replaced;
}

function array_multiunique($arr){
	$jarr=array();
	foreach ($arr as $k => $v){
		$jarr[$k]=json_encode($v);
	}
	$ujarr=array_unique($jarr);
	$uarr=array();
	foreach ($ujarr as $k => $v){
		$uarr[$k]=object_2_array(json_decode($v));
	}
	return $uarr;
}
function object_2_array($result){
    $array = array();
    foreach ($result as $key=>$value){
        if (is_object($value)){
            $array[$key]=object_2_array($value);
        }
        if (is_array($value)){
            $array[$key]=object_2_array($value);
        }
        else{
            $array[$key]=$value;
        }
    }
    return $array;
} 

function replace_namespaces($arr,$hasfilter=""){
	global $uris, $xmlpents;
	$luris=$uris;
        $lents=$xmlpents;
	$pos=array_search($hasfilter,$luris);
	if ($pos>0){
		$lents[$pos]="";
	}
	else{
		$luris[]=$hasfilter;
		$lents[]="";
	}
//	print_r($luris);
//	print_r($lents);
	foreach ($arr as $res => $vals){
		foreach ($vals as $k => $v){
			$arr[$res][$k]=str_replace($luris, $lents, $v);
		}			 
	}
	return $arr;
}
function replace_namespace($uri,$hasfilter=""){
	global $uris, $xmlpents;
        $luris=$uris;
        $lents=$xmlpents;
        $pos=array_search($hasfilter,$luris);
        if ($pos>0){
                $lents[$pos]="";
        }
        else{
                $luris[]=$hasfilter;
                $lents[]="";
        }
	return str_replace($luris, $lents, $uri);
}
function reinstate_namespace($ent,$nspath=""){
	global $uris,$xmlpents;
	$luris=$uris;
        $lents=$xmlpents;
	$ebits=explode(":",$ent);
	if (sizeof($ebits)>1) return str_replace($lents,$luris,$ent);	
	return $nspath.$ent;
}
	
function myexp_namespace($ent){
	$ebits=explode(":",$ent);
	$namespaces=array("","snarm","mebase","meac","meannot","mecontrib","mepack","meexp","mevd","mespec","mecomp");
	return in_array($ebits[0],$namespaces);
}
function current_namespace($ns){
	global $filterns;
	return $filterns==$ns;
}
function tabulateSparqlResultsAssoc($parsedxml){
	$vararray=$parsedxml[0]['children'][0]['children'];
        for ($v=0; $v<sizeof($vararray); $v++){
                $vars[$v]=$vararray[$v]['attrs']['name'];
        }
	$table=array();
        $recs=$parsedxml[0]['children'][1]['children'];
        for ($r=0; $r<sizeof($recs); $r++){
                for ($v=0; $v<sizeof($vars); $v++){
			$bname=$recs[$r]['children'][$v]['attrs']['name'];
                        $table[$r][$bname]=$recs[$r]['children'][$v]['tagData'];
                }
        }
        return $table;
}
function tabulateSparqlResultsJenaAssoc($parsedxml){
        $vararray=$parsedxml[0]['children'][0]['children'];
        for ($v=0; $v<sizeof($vararray); $v++){
                $vars[$v]=$vararray[$v]['attrs']['name'];
        }
        $table=array();
        $recs=$parsedxml[0]['children'][1]['children'];
        for ($r=0; $r<sizeof($recs); $r++){
                for ($v=0; $v<sizeof($vars); $v++){
                        $bname=$recs[$r]['children'][$v]['attrs']['name'];
                        $table[$r][$bname]=$recs[$r]['children'][$v]['children'][0]['tagData'];
                }
        }
        return $table;
}

function tabulateSparqlResultsAsColumns($parsedxml){
	$vararray=$parsedxml[0]['children'][0]['children'];
        for ($v=0; $v<sizeof($vararray); $v++){
                $vars[$v]=$vararray[$v]['attrs']['name'];
        }
        $table=array();
        $recs=$parsedxml[0]['children'][1]['children'];
        for ($r=0; $r<sizeof($recs); $r++){
                for ($v=0; $v<sizeof($vars); $v++){
                        $bname=$recs[$r]['children'][$v]['attrs']['name'];
                        $table[$bname][$r]=$recs[$r]['children'][$v]['tagData'];
                }
        }
        return $table;
}
function tabulateSparqlResultsJenaAsColumns($parsedxml){
        $vararray=$parsedxml[0]['children'][0]['children'];
        for ($v=0; $v<sizeof($vararray); $v++){
                $vars[$v]=$vararray[$v]['attrs']['name'];
        }
        $table=array();
        $recs=$parsedxml[0]['children'][1]['children'];
        for ($r=0; $r<sizeof($recs); $r++){
                for ($v=0; $v<sizeof($vars); $v++){
                        $bname=$recs[$r]['children'][$v]['attrs']['name'];
                        $table[$bname][$r]=$recs[$r]['children'][$v]['children'][0]['tagData'];
                }
        }
        return $table;
}
function generateDataflowMappings($dataflows){
	$dfmap=array();
	foreach($dataflows as $dfuri => $df) $dfmap[$df['id']]=$dfuri;
	return $dfmap;
}
function generateDataflows($dataflows,$ent_uri){
	$dtstr=array('dcterms:title','dcterms:description','dcterms:identifier','mecomp:processor-type','mecomp:processor-script','mecomp:service-name','mecomp:authority-name','mecomp:service-category','mecomp:example-value');
	$dturi=array('mecomp:processor-uri');
	$rdf="";
	$dfmap=array();
	$components="";
	foreach($dataflows as $dfuri => $dataflow){
		$rdf.="  <mecomp:Dataflow rdf:about=\"$dfuri\">\n";
		if (isset($dataflow['id'])){
			if (sizeof($dfmap)==0) $dfmap=generateDataflowMappings($dataflows);
			$rdf.="    <dcterms:identifier rdf:datatype=\"&xsd;string\">$dataflow[id]</dcterms:identifier>\n";
			unset($dataflow['id']);
		}	
	        foreach ($dataflow as $cnum => $comp){
		        $comptype=$comp['type'];
                	$rdf.="    <mecomp:has-component>\n      <mecomp:$comptype rdf:about=\"".$dfuri."/components/$cnum\">\n";
	                foreach ($comp['props'] as $prop){
                		if (in_array($prop['type'],$dtstr)){
	                	       $rdf.="        <".$prop['type']." rdf:datatype=\"&xsd;string\">".xmlentities($prop['value'])."</".$prop['type'].">\n";
                        	}
				elseif (in_array($prop['type'],$dturi)){
					$rdf.="        <".$prop['type']." rdf:resource=\"".xmlentities($prop['value'])."\"/>\n";
				}
				elseif ($prop['type']=="mecomp:executes-dataflow"){
					if (substr($prop['value'],0,7)!="http://") $prop['value']=$dfmap[$prop['value']];
					$rdf.="        <".$prop['type']." rdf:resource=\"$prop[value]\"/>\n";
				}
                       		else $rdf.="        <".$prop['type']." rdf:resource=\"$dfuri/components/".urlencode($prop['value'])."\"/>\n";
                	}
                	$rdf.="        <mecomp:belongs-to-workflow rdf:resource=\"$ent_uri\"/>\n      </mecomp:$comptype>\n    </mecomp:has-component>\n";          
        	}
		$rdf.="  </mecomp:Dataflow>\n\n";
	}
        return $rdf;
}

function tabulateDataflowComponents($parsedxml,$ent_uri,$nested=0){
	global $dfs;
	$d=2;
	if ($nested) $allcomponents=$parsedxml;
	else{
		$allcomponents=$parsedxml[0]['children'][0]['children'];
		$dfs=array();
	}
	if ($allcomponents[0]['name']=="dataflows"){
		foreach ($allcomponents[0]['children'] as $dataflow){
			if (is_array($dataflow)){
				if ($dataflow['attrs']['role']=="top") $id=1;
				else{
					$id=$d;
					$d++;
				}
				$dfs[$ent_uri."#dataflows/$id"]=processDataflowComponents($dataflow['children'],$ent_uri."#dataflows/$d/",$nested);
				$dfs[$ent_uri."#dataflows/$id"]['id']=$dataflow['attrs']['id'];
			//	echo $ent_uri."#dataflows/$id = ".sizeof($dfs[$ent_uri."#dataflows/$id"])."\n";
			}
		}
	}
	else{
		if (is_array($allcomponents)){
			if (strpos($ent_uri,'dataflow') > 0) $dfs[$ent_uri."/dataflow"]=processDataflowComponents($allcomponents,$ent_uri."/dataflow/",$nested);
			else $dfs[$ent_uri."#dataflow"]=processDataflowComponents($allcomponents,$ent_uri."#dataflow/",$nested);
		}
//		echo $ent_uri."/dataflow = ".sizeof($dfs[$ent_uri."/dataflow"])."\n";

	}	
	if (!$nested){
		if ($allcomponents[0]['name']!="dataflows") $dfs=array_reverse($dfs);
		return $dfs;
	}
}
function processDataflowComponents($allcomponents,$ent_uri,$nested=0){
	$components=array();
        $ptmappings=array("wsdl"=>"WSDLProcessor","arbitrarywsdl"=>"WSDLProcessor","soaplabwsdl"=>"WSDLProcessor","biomobywsdl"=>"WSDLProcessor","beanshell"=>"BeanshellProcessor","workflow"=>"DataflowProcessor");
        $c=1;
	foreach ($allcomponents as $typedcomponents){
		if (!isset($typedcomponents['children']) || !is_array($typedcomponents['children'])) $typedcomponents['children']=array();
		foreach ($typedcomponents['children'] as $comp){
			$props=array();
                       	$ctype=ucfirst(strtolower($comp['name']));
			if ($ctype=="Datalink") $ctype="Link";
			$classtype=$ctype;
			if ($ctype=="Source"||$ctype=="Sink"||$ctype=="Processor"){
				if ($ctype=="Processor") $classtype="OtherProcessor";
				foreach ($comp['children'] as $property){
					switch ($property['name']){
					  case 'name':
						$props[]=array('type'=>'dcterms:title','value'=>$property['tagData']);
						break;
					  case 'description':
						$props[]=array('type'=>'dcterms:description','value'=>$property['tagData']);
						break;
					  case 'type':
						if (isset($ptmappings[$property['tagData']])) $classtype=$ptmappings[$property['tagData']];
						else $classtype="OtherProcessor";
						$props[]=array('type'=>'mecomp:processor-type','value'=>$property['tagData']);
						break;
					  case 'examples':
						if (isset($property['children']) && is_array($property['children'])){
							foreach ($property['children'] as $example){
								if (isset($example['tagData'])) $props[]=array('type'=>'mecomp:example-value','value'=>$example['tagData']);
							}
						}
						break;
					  case 'script':
						if (isset($property['tagData'])) $props[]=array('type'=>'mecomp:processor-script','value'=>$property['tagData']);
						else $props[]=array('type'=>'mecomp:processor-script');
						break;
					  case 'model':
						tabulateDataflowComponents($property['children'],$ent_uri."components/$c",$nested+1);
						$props[]=array('type'=>'mecomp:executes-dataflow','value'=>$ent_uri."components/$c/dataflow");
                                                break;
					  case 'dataflow-id':
						$props[]=array('type'=>'mecomp:executes-dataflow','value'=>$property['tagData']);
						break;
					  case 'endpoint':
					  case 'wsdl':
						$props[]=array('type'=>'mecomp:processor-uri','value'=>$property['tagData']);
                                                break;
					  case 'service-name':
					  case 'wsdl-operation':
                                               $props[]=array('type'=>'mecomp:service-name','value'=>$property['tagData']);
                                                break;
					  case 'biomoby-authority-name':
						if (isset($property['tagData'])) $props[]=array('type'=>'mecomp:authority-name','value'=>$property['tagData']);
                                                break;
					  case 'biomoby-service-category':
					}	
				}
				$complist[$ctype][$c]=$comp['children'][0]['tagData'];
			}
			elseif ($ctype=="Link"){
				//Input
				$inputprops=array();	
				if (sizeof($comp['children'][0]['children'])==2){
 					$inputprops[]=array('type'=>'dcterms:title','value'=>$comp['children'][0]['children'][1]['tagData']);
					$cval=array_search($comp['children'][0]['children'][0]['tagData'],$complist['Processor']);
				}
				else if (isset($complist['Sink'])) $cval=array_search($comp['children'][0]['children'][0]['tagData'],$complist['Sink']);
				else $cval=array_search($comp['children'][0]['children'][0]['tagData'],$complist['Processor']);
				$inputprops[]=array('type'=>'mecomp:for-component','value'=>$cval);
				$components[$c++]=array('type'=>'Input','props'=>$inputprops);
				//Output
				$outputprops=array();
				if (sizeof($comp['children'][1]['children'])==2){
					$outputprops[]=array('type'=>'dcterms:title','value'=>$comp['children'][1]['children'][1]['tagData']);
					$cval=array_search($comp['children'][1]['children'][0]['tagData'],$complist['Processor']);
				}
				else $cval=array_search($comp['children'][1]['children'][0]['tagData'],$complist['Source']);
				$outputprops[]=array('type'=>'mecomp:for-component','value'=>$cval);
				$components[$c++]=array('type'=>'Output','props'=>$outputprops);
				$ino=$c-2;
				$ono=$c-1;
				$props[]=array('type'=>'mecomp:to-input','value'=>$ino);
				$props[]=array('type'=>'mecomp:from-output','value'=>$ono);
			}
			if ($ctype=="Coordination"){
				foreach ($comp['children'] as $property){
					if ($property['name']=="controller") $controller=array_search($property['tagData'],$complist['Processor']);
					elseif ($property['name']=="target") $target=array_search($property['tagData'],$complist['Processor']);
				}
				$components[$target]['props'][]=array('type'=>'mecomp:waits-on','value'=>$controller);
			}
			else{
				$components[$c]=array('type'=>$classtype,'props'=>$props);
				$c++;
			}
		}
	}
	return $components;		
}
function extractRDF($id,$wfid,$version,$posthash){
	global $datapath,$datauri;
	//print_r($params);
	$uri=$datauri."workflows/$wfid/versions/$version#$posthash";
//	echo $uri."\n";
        $filename=$datapath."dataflows/rdf/$id";
//	echo $filename."\n";
	//echo "$filename = $uri\n";
        $lines=file($filename);
        $l=0;
        while ((strpos($lines[$l],"rdf:about=\"$uri\"") === FALSE) && ($l<sizeof($lines))){
                $l++;
        }
	$rdf="";
        $ebits=explode(" ",str_replace(array("<",">"),"",trim($lines[$l])));
        while ((strpos($lines[$l],'</'.$ebits[0].'>') === FALSE) && ($l<sizeof($lines))){
                $rdf.=$lines[$l];
                $l++;
        }
        $rdf.=$lines[$l];
       	return $rdf;
}


function setKey($arr,$key){
	$assocarr=array();
	foreach($arr as $rec => $data){
		$assocarr[$data[$key]]=$data;
	}
	return $assocarr;
}
function queryFailed($res){
	if (preg_match("/^Query Failed:/",$res)) return true;
	return false;
}
function processRDFDescription($filename,$ontnames){
	$props=array();
	if (file_exists($filename)){
		$fh=fopen($filename,'r');
		$xmlstr="";
		while (!feof($fh)){
			$xmlstr.=fgets($fh,8192);
		}
		fclose($fh);
		$xml=parseXML($xmlstr);
		$props=getOntologyProperties($xml[0]['children'][0],$ontnames);
	}
	else echo "$filename does not exists";
	return $props;
}
function processOWLInfo($filename,$ontnames){
	 $props=array();
        if (file_exists($filename)){
                $fh=fopen($filename,'r');
                $xmlstr="";
                while (!feof($fh)){
                        $xmlstr.=fgets($fh,8192);
                }
                fclose($fh);
                $xml=parseXML($xmlstr);
		if (is_array($xml[0]['children'])){
			foreach($xml[0]['children'] as $entno => $ent){
				if ($ent['name']=="owl:Ontology"){
					$props=getOntologyProperties($ent,$ontnames);
					break;
				}
			}
		}
        }
        else echo "$filename does not exists";
        return $props;	
}

function getOntologyProperties($ontent,$ontnames){
	global $ontimports;
	$props=array();
	if ((!$ontent['attrs']['rdf:about'] || in_array($ontent['attrs']['rdf:about'],$ontnames)) && sizeof($ontent['children'])>0){
		foreach ($ontent['children'] as $prop){
        		if ($prop['name']=="owl:imports"){
				$ontimports[]=$prop['attrs']['rdf:resource'];
			}
			else{
				if ($prop['tagData']) $val=$prop['tagData'];
	        	        else{
        	         		if ($prop['children']){
                        	        	$val="";
                                        	foreach($prop['children'] as $spn => $subprop){
                                	        	if ($subprop['tagData']) $val.=$subprop['name']." = ".$subprop['tagData'].", ";
                                                	else  $val.=$subprop['name']." = <a href=\"".$subprop['attrs']['rdf:resource']."\">".$subprop['attrs']['rdf:resource']."</a>, ";
                                        	}
                                	}
                                	else $val="<a href=\"".$prop['attrs']['rdf:resource']."\">".$prop['attrs']['rdf:resource']."</a>";
                        	}
                        	$props[]=array('prop'=>$prop['name'],'val'=>$val);
			}
                 }
        }
	return $props;
}
?>
