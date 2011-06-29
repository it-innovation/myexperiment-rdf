#!/usr/bin/php
<?php 
//	error_reporting(E_ALL);
	include('include.inc.php');
	require('genrdf.inc.php');
	function setTypeIDandParams($args,$noexit=0){
		global $nesting;
		if (isset($args[2])){
			$type=$args[1];
			$id=$args[2];
		}
		else exit("Not enough arguments!\n");
		if (isset($args[3])) $params=explode("/",$args[3]);
		else $params = array();
		$wfid='0';
		if (isset($params[0]) and strlen($params[0])>0){
			if (sizeof($params)>1 && isset($nesting[$params[sizeof($params)-2]])){
				$type=$params[sizeof($params)-2];
				$id=$params[sizeof($params)-1];
				$params=array();
         		}
			elseif ($params[0]=="versions" && $type=="workflows"){
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
                        elseif ($params[0]=="relationships" && $type=="packs"){
                                $type="pack_relationships";
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
	if (sizeof($argv)>4) exit("Too many arguments!\n");
	list($type,$id,$params,$wfid)=setTypeIDandParams($argv);
	if (entityExists($type,$id)){
	        $res=getEntityResults($type,$id);
		$uri=getEntityURI($type,$id,mysql_fetch_assoc($res));
		$res=getEntityResults($type,$id);
		$e=1;
		$xml=pageheader();
		if ($id) $xml.=rdffiledescription($uri);
		for ($e=0; $e<mysql_num_rows($res); $e++){
       			$xml.=printEntity(mysql_fetch_assoc($res),$type);
	       	}
	 	if ($e==1){
			$regex=$datauri.'[^<>]+>';
			preg_match_all("!$regex!",$xml,$matches);
			foreach ($matches as $m => $match){
				$matches[$m]=str_replace(array(" ","\n","\t"),array("","",""),$match);
			}
		        $matches=array_unique($matches[0]);
			foreach($matches as $m){
				if (strpos($m,'/>')>0){
					$mtmp=explode('"',$m);
					$m=str_replace($datauri,"",$mtmp[0]);
					$mhbits=explode('#',$m);
					if (isset($mhbits[1])) $posthash=$mhbits[1];
					else $posthash="";
					$mbits=explode('/',$mhbits[0]);
					if (in_array("previews",$mbits) && (in_array("full",$mbits)||in_array("medium",$mbits)||in_array("thumb",$mbits)||in_array("svg",$mbits))) continue;
					if (strpos($m,'.') === false && $mbits[0] && $sql[$mbits[0]] && $datauri.$m != $uri){
						if ($posthash){
							$wfid=$mbits[1];
                                               	        $version=$mbits[3];
                                                       	$id=getWorkflowVersion($wfid,$version);
                              	                        $xml.=extractRDF($id,$wfid,$version,$posthash)."\n";
						}
						else{
							$args[1]=array_shift($mbits);
							$args[2]=array_shift($mbits);
							$args[3]=implode('/',$mbits);
							$args[4]=1;
							list($type,$id,$params,$wfid)=setTypeIDandParams($args,true);
							if (isset($type)){
								$res=getEntityResults($type,$id);	
								$xml.=printEntity(mysql_fetch_assoc($res),$type);	
							}
						}
					}
				}
			}
		}
  		$xml.=pagefooter();
		header('Content-type: application/rdf+xml');
		echo $xml;
	}
?>
