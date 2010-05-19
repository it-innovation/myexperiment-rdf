#!/usr/bin/php
<?php 
	ini_set('include_path','inc/');
	require('genrdf.inc.php');
	$type=$argv[1];
	$id=$argv[2];
	$params=explode("/",$argv[3]);
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
	if ($id) $whereclause=$tables[$type].".id=$id";
	if (entityExists($type,$id)){
		if (stripos($sql[$type],"where") === false){
                   $cursql=$sql[$type]." where ".$whereclause;
		}
		else $cursql=$sql[$type]." and ".$whereclause;
	        $res=mysql_query($cursql);
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
		}
  		$xml.=pagefooter();
		header('Content-type: application/rdf+xml');
		echo $xml;
	}
	else{
 		sc404();
	}
?>
