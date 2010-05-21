#!/usr/bin/php
<?php 
	ini_set('include_path','inc/');
	chdir("../rdfgen/");
	require('genrdf.inc.php');
	function setTypeIDandParams($args){
		$type=$args[1];
		$id=$args[2];
		$params=explode("/",$args[3]);
		$noexit=$args[4];
		$wfid='0';
		if ($params[0]){
			if ($params[0]=="versions" && $type="workflows"){
				$type="workflow_versions"; 
				$wvsql="select id from workflow_versions where workflow_id=$id and version=$params[1]";
				$wvres=mysql_query($wvsql);
				$wfid=$id;
				$id=mysql_result($wvres,0,'id');
			}
			elseif ($params[0]=="announcements" && $type=="groups"){
				$type="group_announcements";
				$id=$params[1];
			}	
			elseif (!$noexit){
				exit();
			}
		}
		return array($type,$id,$params,$wfid);
	}
	function getEntityResults($type,$id){
		global $tables, $sql;
		if ($id){
			$whereclause=$tables[$type].".id=$id";
                        if (stripos($sql[$type],"where") === false ){
                           $cursql=$sql[$type]." where ".$whereclause;
                        }
                        else $cursql=$sql[$type]." and ".$whereclause;
                }
                else $cursql=$sql[$type];
                return mysql_query($cursql);
	}
	list($type,$id,$params,$wfid)=setTypeIDandParams($argv);
	$uri="$type/$id";
	if ($params[0]) $uri="/".implode('/'.$params);
//	echo "~".sizeof($params)."~";
	if (entityExists($type,$id)){
	        $res=getEntityResults($type,$id);
		$e=1;
		$xml=pageheader();
		if ($id) $xml.=rdffiledescription(getEntityURI($type,$id));
		if ($params[2]=="dataflows"||$params[2]=="dataflow"){
			array_shift($params);
			$version=array_shift($params);
        		$xml.=extractRDF($id,$wfid,$version,$params);
	        }
		else{
		        for ($e=0; $e<mysql_num_rows($res); $e++){
       			        $xml.=printEntity(mysql_fetch_assoc($res),$type,$format);
	        	}
			if ($e==1){
				$regex=$datauri.'[^"]+';
				preg_match_all("!$regex!",$xml,$matches);
		                $matches=array_unique($matches[0]);
				foreach($matches as $m){
					$m=str_replace($datauri,"",$m);
					$mbits=explode('/',$m);
					if (strpos($m,'.') === false && $sql[$mbits[0]] && $m != $uri){
						$args[1]=array_shift($mbits);
						$args[2]=array_shift($mbits);
						$args[3]=implode('/',$mbits);
						$args[4]=1;
						list($type,$id,$params,$wfid)=setTypeIDandParams($args);
						$res=getEntityResults($type,$id);
						$xml.=printEntity(mysql_fetch_assoc($res),$type,$format);	
					}
				}
			}	
		}
  		$xml.=pagefooter();
		header('Content-type: application/rdf+xml');
		echo $xml;
	}
?>
