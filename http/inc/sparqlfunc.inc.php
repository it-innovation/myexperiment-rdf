<?php
	function getDescribeQuery($entity,$params="",$ndomain=''){
		global $desqs,$datauri, $domain, $desqsvars;
		if (!$ndomain) $ndomain=$domain;
		if ($ndomain=="private") $prefixes=getPrivatePrefixes();
		else $prefixes=getPublicPrefixes(); 
		$ebits=explode("/",$entity);
		if ($ebits[0]=="QuestionRO") $entity="Question/$ebits[1]";
		if ($params){		
			$uri=$datauri.$entity;
			$desq=str_replace("~",$uri,$desqs[$params]);
		}
		else $desq=$desqs[$ebits[0]];
		if ($desqsvars[$ebits[0]]) $vars=$desqsvars[$ebits[0]];
		else $vars="?x";
	       	$ret="$prefixes\nDESCRIBE $vars where { $desq }";
		return $ret;

        }
	function getSPARQLCall($desq,$ndomain=''){
		global $domain,$datauri;
		if (!$ndomain) $ndomain=$domain;
		if ($ndomain=="private") return $datauri."private/sparql?query=".urlencode($desq);
		return $datauri."sparql?query=".urlencode($descq);	
	}
	function orderClassTypes($types){
		$otypes=array();
		$scsize=1;
		$scmax=10;
		while (sizeof($types)>0 && $scsize<=$scmax){
			foreach ($types as $t => $type){
				if (sizeof($type)<=$scsize){
					$fail=0;
					foreach ($type as $sc => $sclass){
						if (!in_array($sclass['sclass'],$otypes) && $sclass['sclass']!=$t){
							$fail=1;
							break;
						} 
					}
					if (!$fail){
						array_unshift($otypes,$t);
						unset($types[$t]);
					}
				}

			}
			$scsize++;
		}
		array_unshift($otypes,'nil');
		return $otypes;
	}
	function reformatDescribeResults($xml){
		global $entities, $inserts, $removeents, $addns, $domain;
		$addns=array();
		$removeents=array();
		$types=array();
		$pxml=parseXML($xml);
		if (is_array($pxml[0]['children'])){
		foreach ($pxml[0]['children'] as $id => $entity){
			for ($k=0; $k<sizeof($entity['children']); $k++){
                                if ($entity['children'][$k]['name']=="rdf:type") $types[$entity['children'][$k]['attrs']['rdf:resource']]=1;
			}
		}
//		print_r($types);
		if (sizeof($types)>0){
			require_once('4storefunc.inc.php');
			$ts=getTriplestoreForDomain($domain);
			$useport=getPortForTriplestore($ts);
			foreach (array_keys($types) as $type) $sparql[$type]="PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#> select distinct ?sclass where {<$type> rdfs:subClassOf ?sclass}";
			$res=sparqlQueryClientMultiple($useport,$sparql,"SELECT","sparql");
			//print_r($res);
			foreach ($res as $r => $ares){
				$tab[$r]=tabulateSparqlResultsAssoc(parseXML($res[$r]));
			}
		//	print_r($tab);
			$types_ordered=orderClassTypes($tab);
		}
		foreach ($pxml[0]['children'] as $id => $entity){
			$type="";
			$firsttype="";
			$curtpos=sizeof($types_ordered);
			for ($k=0; $k<sizeof($entity['children']); $k++){
				if ($entity['children'][$k]['name']=="rdf:type"){
					if (!$firsttype){
						$firsttype=$entity['children'][$k]['attrs']['rdf:resource'];
						$unsetprop=$k;
					}
					//$type=$entity['children'][$k]['attrs']['rdf:resource'];
					$tpos=array_search($entity['children'][$k]['attrs']['rdf:resource'],$types_ordered);
					//print_r($types_ordered);
					if ($tpos>0 && $tpos<$curtpos){
						$curtpos=$tpos;
						$unsetprop=$k;
					}
				}
				
			}
			if ($curtpos<sizeof($types_ordered)) $type=$types_ordered[$curtpos];
			else $type=$firsttype;
			if ($type){
				$pxml[0]['children'][$id]['name']=getTagName($type,$pxml[0]['attrs']);
				unset($pxml[0]['children'][$id]['children'][$unsetprop]);	
			}
			if ($entity['attrs']['rdf:about']) $entities[$id]=$entity['attrs']['rdf:about'];
			elseif ($entity['attrs']['rdf:nodeID']) $entities[$id]=$entity['attrs']['rdf:nodeID'];
		}
		foreach ($addns as $pref => $uri) $pxml[0]['attrs']["xmlns:$pref"]=$uri;
		$pxml[0]=findInserts($pxml[0],$entities);
		$order=getOrder($inserts,array_keys($pxml[0]['children']));
		$pxml[0]=doInserts($pxml[0],$order);
		foreach (array_unique($removeents) as $eid){
			unset($pxml[0]['children'][$eid]);
		}
		}
		return generateXML($pxml);
	}	
	function getTagName($type,$ns){
		global $addns;
		 if (strpos($type,"#")>0){
	                $tbits=explode("#",$type);
                        $typens=$tbits[0]."#";
			$enttype=$tbits[1];
                 }
                 else{
        	        $tbits=explode("/",$type);
                        $typens=str_replace($tbits[sizeof($tbits)-1],"",$type);
			$enttype=$tbits[sizeof($tbits)-1];
                }
                $tnspref=array_search($typens,$ns);
                $tnspref=str_replace("xmlns:",'',$tnspref);
	       if (!$tnspref){
                        require_once('xmlfunc.inc.php');
                        $tnspref=substr(replace_namespace($typens),0,-1);
                        $addns[$tnspref]=$typens;
                }
                if ($tnspref=="xml:base" || $tnspref=="xmlns"|| !$tnspref)  return $enttype;
		return "$tnspref:$enttype";
	}
	function getOrder($inserts,$entids){
		$order=array();
		if (!is_array($inserts)) return $entids;
		foreach($entids as $v){
			if (!in_array($v,array_keys($inserts))) $order[]=$v;
		}
		$j=0;
		while (sizeof($inserts)>0){
			foreach($inserts as $i => $ins){
				$add=1;
				foreach($ins as $i2){
					if (!in_array($i2,$order)){
						$add=0;
						break;
					}
				}
				if ($add){
					$order[]=$i;
					unset($inserts[$i]);
				}
			}
			$j++;
		}
		return $order;
	}	

		
	function findInserts($pxml){
		global $entities, $inserts, $removeents;
		foreach ($pxml['children'] as $k => $kid){
			if (isset($kid['attrs']['rdf:resource'])) $resource=$kid['attrs']['rdf:resource'];
			elseif (isset($kid['attrs']['rdf:nodeID'])) $resource=$kid['attrs']['rdf:nodeID'];
			else $resource="nil";
			$id=array_search($resource,$entities);
			if ($entities[$id]==$resource){
				$pxml['children'][$k]['insert']=$id;
				$removeents[]=$id;
				if (isset($pxml['attrs']['rdf:about'])) $entity=$pxml['attrs']['rdf:about'];
                                elseif (isset($pxml['attrs']['rdf:nodeID'])) $entity=$pxml['attrs']['rdf:nodeID'];
                                else $entity="nil";
				$eid=array_search($entity,$entities);
				if ($entities[$eid]==$entity){
					$inserts[$eid][]=$id;
				}
			}
		//	if ($kid['children']) $pxml['children'][$k]=findInserts($kid,$entities);
		}
		return $pxml;
	}
	function doInserts($pxml,$order){
		foreach ($order as $i){
			foreach ($pxml['children'][$i]['children'] as $k => $kid){
				if ($kid['insert']){
					if (preg_match('/^A[0-9]+$/',$pxml['children'][$kid['insert']]['attrs']['rdf:nodeID'])){
						unset($pxml['children'][$kid['insert']]['attrs']['rdf:nodeID']);
						unset($pxml['children'][$i]['children'][$k]['attrs']['rdf:nodeID']);
					}
					$pxml['children'][$i]['children'][$k]['children'][]=$pxml['children'][$kid['insert']];
					unset($pxml['children'][$i]['children'][$k]['insert']);
					
				}
			}
		}
		return $pxml;
	}
	
		
?>
