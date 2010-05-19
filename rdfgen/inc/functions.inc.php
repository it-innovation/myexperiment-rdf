<?php

function getRequester($mship){
	if (!$mship['user_established_at']) return "groups/".$mship['network_id'];
	elseif(!$mship['network_established_at']) return "users/".$mship['user_id'];
	else{
		$utime=strtotime($mship['user_established_at']);
		$ntime=strtotime($mship['network_established_at']);
		if ($utime<=$ntime) return "users/".$mship['user_id'];
		else return "groups/".$mship['network_id'];
	}
	return "";
}
function getRequesterTime($mship){
	
	if (!$mship['user_established_at']) return $mship['network_established_at'];
	elseif(!$mship['network_established_at']) return $mship['user_established_at'];
	else{
		$utime=strtotime($mship['user_established_at']);
		$ntime=strtotime($mship['network_established_at']);
		if ($utime<=$ntime) return $mship['user_established_at'];
		else return $mship['network_established_at'];
	}
	return "";
}

function getAccepter($mship){
	global $datauri;
	if (!$mship['user_established_at']){
		if ($mship['user_id']) return $datauri."users/".$mship['user_id'];
		return "";
	}
	elseif(!$mship['network_established_at']) return "groups/".$mship['network_id'];
	else{
		$utime=strtotime($mship['user_established_at']);
		$ntime=strtotime($mship['network_established_at']);
		if ($utime<=$ntime) return $datauri."groups/".$mship['network_id'];
		else return $datauri."users/".$mship['user_id'];	
	}
	return "";
}

function getAccepterTime($mship){
	if ($mship['network_established_at'] && $mship['user_established_at']){
		$utime=strtotime($mship['user_established_at']);
		$ntime=strtotime($mship['network_established_at']);
		if ($utime>$ntime) return $mship['user_established_at'];
		else return $mship['network_established_at'];
	}
	return "";
}

function getUser($action,$hash=0){
	global $salt, $data;
	if ($action['user_id'] && $action['user_id']!="AnonymousUser"){
		if ($hash) return "users/".md5($salt.$action['user_id']);
	 	return "users/".$action['user_id'];
	}
	return "ontologies/specific/AnonymousUser";
}
function getThumbnail($workflow){
	$url=getUrl($workflow,'thumb');
	addAggregatedResource("  <rdf:Description rdf:about=\"$url\">\n    <dcterms:format rdf:datatype=\"&xsd;string\">image/x-png</dcterms:format>\n    <dcterms:title rdf:datatype=\"&xsd;string\">Thumbnail of $workflow[title]</dcterms:title>\n  </rdf:Description>\n",workflowOrVersion($workflow),$url,$workflow['format']);
	return $url;
}
function getThumbnailBig($workflow){
	$url=getUrl($workflow,'medium');
	addAggregatedResource("  <rdf:Description rdf:about=\"$url\">\n    <dcterms:format rdf:datatype=\"&xsd;string\">image/x-png</dcterms:format>\n    <dcterms:title rdf:datatype=\"&xsd;string\">Big Thumbnail of $workflow[title]</dcterms:title>\n  </rdf:Description>\n",workflowOrVersion($workflow),$url,$workflow['format']);
	return $url;
}
function getPreview($workflow){
        $url=getUrl($workflow);
        addAggregatedResource("  <rdf:Description rdf:about=\"$url\">\n    <dcterms:format rdf:datatype=\"&xsd;string\">image/x-png</dcterms:format>\n    <dcterms:title rdf:datatype=\"&xsd;string\">Preview of $workflow[title]</dcterms:title>\n  </rdf:Description>\n",workflowOrVersion($workflow),$url,$workflow['format']);
	return $url;
}
function getSVG($workflow){	
	$url=getUrl($workflow,'','svg');
	addAggregatedResource("  <rdf:Description rdf:about=\"$url\">\n    <dcterms:format rdf:datatype=\"&xsd;string\">image/svg+xml</dcterms:format>\n    <dcterms:title rdf:datatype=\"&xsd;string\">SVG of $workflow[title]</dcterms:title>\n  </rdf:Description>\n",workflowOrVersion($workflow),$url,$workflow['format']);
	return $url;
}
function workflowOrVersion($entity){
	global $datauri;
	if (isset($entity['current_version'])) $type="Workflow";
        else $type="WorkflowVersion";
	return $datauri.$type."/".$entity['id'];
}
function getUrl($workflow,$type="",$format="image"){
 	global $myexp_inst;
	if ($type) $type.="/";
        if ($workflow['workflow_id']){
                return $myexp_inst."workflow/version/$format/".$workflow['id']."/".$type.urlencode($workflow[$format]);
        }
        return $myexp_inst."workflow/$format/".$workflow['id']."/".$type.urlencode($workflow[$format]);
}
function getWorkflowDownloadUrl($workflow){
	global $myexp_inst;
	if ($workflow['workflow_id']){
                $url=$myexp_inst."workflows/".$workflow['workflow_id']."/download/".urlencode($workflow['unique_name']).".".$workflow['file_ext']."?version=".$workflow['version'];
		$table="workflow_versions";
		$id=$workflow['workflow_id'];
        }
        else{
		$url=$myexp_inst."workflows/".$workflow['id']."/download/".urlencode($workflow['unique_name']).".".$workflow['file_ext'];
		$table="workflows";
		$id= $workflow['id'];
	}
//	print_r($workflow);
	$ctsql="select mime_type from content_types where id=$workflow[content_type_id]";
//	echo "<!-- $ctsql -->\n";
	$ctres=mysql_query($ctsql);
//	print_r(mysql_fetch_assoc($ctres));
	
	addAggregatedResource("  <rdf:Description rdf:about=\"$url\">\n    <dcterms:format rdf:datatype=\"&xsd;string\">".mysql_result($ctres,0,'mime_type')."</dcterms:format>\n    <dcterms:title rdf:datatype=\"&xsd;string\">Workflow File for $workflow[title]</dcterms:title>\n  </rdf:Description>\n",workflowOrVersion($workflow),$url,$workflow['format']);
	return $url;
	
}
function getFileDownloadUrl($file){
	global $myexp_inst;
	return $myexp_inst."blobs/".$file['id']."/download/".urlencode($file['local_name']);
}
	
function getLicense($contrib){
	global $licenses;
	return $licenses[$contrib['license']];
}
function getCurrentWorkflowVersion($workflow){
	global $myexp_inst, $mappings, $datauri, $sql;
/*	$sql="select id from workflow_versions where workflow_id=".$workflow['id']." and version=".$workflow['current_version'];
	str_replace(array('~','?'),array('0','0'),$sql);
	$res=mysql_query($sql);
	$urlpart="WorkflowVersion/".mysql_result($res,0,'id');*/
        $wvsql=$sql['workflow_versions']. " and workflow_id=$workflow[id] and version=$workflow[current_version]";
        $wvsql=str_replace(array('~','?'),array('0','0'),$wvsql);
        $res=mysql_query($wvsql);
       	$row=mysql_fetch_assoc($res);
	$aggregates="workflows/$workflow[id]/versions/$workflow[current_version]";
        addAggregatedResource(printEntity($row,"workflow_versions"),workflowOrVersion($workflow),$datauri."workflows/$workflow[id]/versions/$workflow[current_version]",$workflow['format']);
        return $aggregates;
}
function isCurrentVersion($workflowversion){
	$sql="select workflows.id from workflows inner join workflow_versions on workflows.current_version=workflow_versions.version and workflows.id=workflow_versions.workflow_id where workflow_versions.id=$workflowversion[id]";
//	echo $sql;
	$res=mysql_query($sql);
	return mysql_num_rows($res);
}
function getWorkflowVersions($workflow){
	global $myexp_inst, $mappings, $datauri, $sql;
	$wvsql=$sql['workflow_versions']. " and workflow_id=$workflow[id] and version!=$workflow[current_version]";
//	str_replace(array('~','?'),array('0','0'),$sql);
//	echo $wvsql;
	$res=mysql_query($wvsql);
	$aggregates="";
	for ($i=0; $i<mysql_num_rows($res); $i++){
		$row=mysql_fetch_assoc($res);
//		print_r($row);
		addAggregatedResource(printEntity($row,"workflow_versions"),workflowOrVersion($workflow),$datauri."workflows/".$row['workflow_id']."/versions/".$row['version'],$workflow['format']);
		if ($workflow['format']=="ore") $aggregates.="    <ore:aggregates rdf:resource=\"".$datauri."workflows/".$row['workflow_id']."/versions/".$row['version']."\"/>\n";
		else $aggregates.="    <mebase:has-version rdf:resource=\"".$datauri."workflows/".$row['workflow_id']."/versions/".$row['version']."\"/>\n";
	}
	return $aggregates;	
}
function foafPictureURL($pic_id){
	return "http://www.myexperiment.org/pictures/show/$pic_id?size=160x160.png";
}
function pictureURL($user){
       return "http://www.myexperiment.org/pictures/show/".$user['avatar_id']."?size=160x160.png";
}
function validateEmail($email){
       if (!preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/", $email)) return 0;
       return 1;
}

function mailto_foaf($email){
	if (validateEmail($email)) return "mailto:".$email;
}

function mailto($user){
	global $userid, $domain;
	if (validateEmail($user['email']) && ($userid==$user['id'] || $domain=="private")) return "mailto:".$user['email'];
}
function mailto_hash($user){
	 global $userid, $domain;
        if (validateEmail($user['email']) && ($userid==$user['id'] || $domain=="private")) return "mailto:".$user['email'];
	return "mailto:".md5($user['email'])."@hidden.org";
}
function mailto_unconfirmed($user){
	global $userid, $domain;
	if (validateEmail($user['unconfirmed_email']) && ($userid==$user['id'] || $domain=="private")) return "mailto:".$user['unconfirmed_email'];
}
function mailto_profile($user){
	if (validateEmail($user['profile_email'])) return "mailto:".$user['profile_email'];
}
function openid_url($user){
	global $userid, $domain;
	if ($userid==$user['id'] || $domain=="private") return $user['openid_url'];
}
function getResidence($user){
	$residence="<!-- residence -->\n";
	if($user['location_city'])$residence.="<dbpedia:residence rdf:resource=\"http://dbpedia.org/resource/".str_replace(" ","_",$user['location_city'])."\"/>\n";
	if($user['location_country'])$residence.="<dbpedia:residence rdf:resource=\"http://dbpedia.org/resource/".str_replace(" ","_",$user['location_country'])."\"/>\n";
	return $residence;
}
function request_token($request){
	global $domain;
	if ($domain=="private") return $request['token'];
}
function username($user){
	global $userid, $domain;
	if ($userid==$user['id'] || $domain=="private") return $user['username'];
}	
function isPartOfURI($entry){
	return  "Pack/".$entry['pack_id'];
}
function getVersionID($entity){
	if ($entity['contributable_type']=="Workflow") $sql="select id from workflow_versions where workflow_id=$entity[contributable_id] and version=$entity[contributable_version]";
	if ($sql){
		$res=mysql_query($sql);
		return mysql_result($res,0,'id');
	}
}
	
function getHumanStartPage($entity,$type){
	global $myexp_inst, $hspent, $datauri;	
	$gtype=$hspent[$type]."/";
	if ($type=="WorkflowVersion"){
		$gtype="workflows/".$entity['workflow_id']."?version=";
		$entity['id']=$entity['version'];
	}
	elseif ($type=="Job") $gtype="experiments/".$entity['experiment_id']."/jobs/";
	elseif ($type=="GroupAnnouncement") $gtype="groups/".$entity['network_id']."/announcements/";
	elseif ($type=="Review") $gtype=$hspent[$entity['reviewable_type']]."/".$entity['reviewable_id']."/reviews/";
	$url=$myexp_inst.$gtype.$entity['id'];
	addAggregatedResource("    <rdf:Description rdf:about=\"$url\">\n      <dcterms:format rdf:datatype=\"&xsd;string\">text/xhtml+xml</dcterms:format>\n      <dcterms:title rdf:datatype=\"&xsd;string\">Human Start Page for $entity[title]</dcterms:title>\n    </rdf:Description>\n",$datauri."$type/$entity[id]",$url,$entity['format']);
	return "";
	return $url;
	
}
function getFilename($entity,$type){
	if ($type=="Workflow"||$type="WorkflowVersion") return $entity['unique_name'].".".$entity['file_ext'];
	return '';
}
function getOutput($entity){
	global $datauri;
	if ($entity['outputs_uri']){		
		$url=$datauri."Job/$entity[id]/Output";
		$xml="      <meexp:Data rdf:about=\"$url\">\n";
		if ($entity['outputs_uri']) $xml.= "        <mebase:uri rdf:datatype=\"&xsd;anyURI\">$entity[outputs_uri]</mebase:uri>\n";

		$xml.="      </meexp:Data>";
		if ($entity['format']=="ore"){
			addAggregatedResource($xml,$datauri."Job/$entity[id]",$url,$entity['format']);
			$xml=$url;
		}
	}
	return $xml;
}
function getInput($entity){
        global $datauri;
	if ($entity['inputs_uri'] || $entity['inputs_data']){
		$url=$datauri."Job/$entity[id]/Input";
		$xml="<meexp:Data rdf:about=\"$url\">\n";
		if ($entity['inputs_uri']) $xml.= "        <mebase:uri rdf:datatype=\"&xsd;anyURI\">$entity[inputs_uri]</mebase:uri>\n";
		if ($entity['inputs_data']) $xml.= "        <mebase:text rdf:datatype=\"&xsd;string\">$entity[inputs_data]</mebase:text>\n";
		$xml.="      </meexp:Data>";
		if ($entity['format']=="ore"){
                        addAggregatedResource($xml,$datauri."Job/$entity[id]",$url,$entity['format']);
                        $xml=$url;
                }

	}
        return $xml;
}
function getRunnable($entity){
	global $datauri, $mappings, $sql;
	if ($entity['runnable_version']){
		$cursql="select id from workflow_versions where workflow_id=$entity[runnable_id] and version=$entity[runnable_version]";
		$res=mysql_query($cursql) or $return=1;
		if ($return || mysql_num_rows($res)==0) return "";
		$id=mysql_result($res,0,'id');
		$type="WorkflowVersion";
		$idf="workflow_versions.id";
	}
	else{
		$id=$entity['runnable_id'];
		$type=$entity['runnable_type'];
		$idf="workflows.id";
	}
	$runnable="$type/$id";
	
	$cursql="$sql[$type] and $idf=$id";
	//print_r($entity);
//	echo "<!-- $type $cursql -->";
	$res=mysql_query($cursql) or $return=1;
        if ($return || mysql_num_rows($res)==0) return "";
	addAggregatedResource(printEntity(mysql_fetch_array($res),$type),$datauri."Job/$entity[id]",$datauri.$runnable,$entity['format']);
	return $runnable;
}
function getURI($entity){
	global $datauri;
	addAggregatedResource("  <rdf:Description rdf:about=\"$entity[job_uri]\">\n    <dcterms:title>Server URI for Job</dcterms:title>\n  </rdf:Description>\n",$datauri."Job/$entity[id]",$entity[job_uri],$entity['format']);
	return $entity['job_uri'];
}
function getRunner($entity){
	global $datauri, $mappings, $sql;
	$runner="$entity[runner_type]/$entity[runner_id]";
	$cursql=$sql[$entity['runner_type']]." where id=$entity[runner_id]";
	$res=mysql_query($cursql);
	addAggregatedResource(printEntity(mysql_fetch_array($res),$entity['runner_type']),$datauri."Job/$entity[id]",$datauri.$runner,$entity['format']);
	return $runner;
}
function getProxyFor($entity){
	global $ontent;
	if ($entity['contributable_type']){
		if ($entity['contributable_version']){
			$entity['contributable_id']=getVersionID($entity);
			if ($entity['contributable_type']=="Workflow") $entity['contributable_type']="WorkflowVersion";
		}
		$etype=array_search($entity['contributable_type'],$ontent);
		return $etype."/".$entity['contributable_id'];
	}
	$xml.="    <rdf:Description rdf:about=\"".str_replace("&","&amp;",$entity['uri'])."\"";
        if ($entity['alternate_uri']) $xml.=">\n      <rdfs:seeAlso>\n        <rdf:Description rdf:about=\"".str_replace("&","&amp;",$entity['alternate_uri'])."\"/>\n      </rdfs:seeAlso>\n    </rdf:Description>\n";
	else $xml.="/>\n";
        return $xml;
}
function getDataflowComponents($entity,$type){
	global $myexp_inst,$datauri;
	$comp_path="/var/data/ld_dataflows/xml/";
	require_once('xmlfunc.inc.php');
	if ($type=="workflows"){
		require_once('connect.inc.php');
		$sql="select id from workflow_versions where version='$entity[current_version]' and workflow_id='$entity[id]'";
		$res=mysql_query($sql);
		$wfvid=mysql_result($res,0,'id');
		$fileloc=$comp_path.$wfvid;
		$ent_uri=$datauri."workflows/".$entity['id']."/versions/$entity[current_version]";
	}
	elseif ($type=="workflow_versions"){
		$fileloc=$comp_path.$entity['id'];
		$sql="select workflow_id, version from workflow_versions where id='$entity[id]'";
                $res=mysql_query($sql);
		$ent_uri=$datauri."workflows/".mysql_result($res,0,'workflow_id')."/versions/".mysql_result($res,0,'version');
	}
	if (file_exists($fileloc)){
		$fh=fopen($fileloc,"r");
 		while(!feof($fh)){
                	$xml.=fgets($fh);
	        }
        	fclose($fh);
		$dataflows=tabulateDataflowComponents(parseXML($xml),$ent_uri);
		if ($dataflows) return generateDataflows($dataflows,$ent_uri);
	}
}
	
function getDataflow($entity,$type){
	global $datauri;
	$comp_path="/var/data/ld_dataflows/rdf/";
 	if ($type=="workflows"){
                require_once('connect.inc.php');
                $sql="select id from workflow_versions where version='$entity[current_version]' and workflow_id='$entity[id]'";
                $res=mysql_query($sql);
                $wfvid=mysql_result($res,0,'id');
		$type="workflow_versions";
                $fileloc=$comp_path.$wfvid;
        }
        elseif ($type=="workflow_versions"){
                $fileloc=$comp_path.$entity['id'];
		$wfvid=$entity['id'];
        }
	$data="";
	if (file_exists($fileloc)){
		$fh=fopen($fileloc,'r');
		$data=fread($fh,8192);
		fclose($fh);
	}
	if (strlen($data)>0){
		$sql="select content_type_id from workflow_versions where id='$wfvid'";
                $res=mysql_query($sql);
		if (mysql_result($res,0,'content_type_id')==2) return $datauri.$type."/$wfvid/dataflows/1";
		return $datauri."workflow_versions/$wfvid/dataflow";
	}
	return "";
}
function getLicenseAttributes($license){
	$sql="select license_options.* from license_attributes inner join license_options on license_attributes.license_option_id=license_options.id where license_attributes.license_id=$license[id]";
	$res=mysql_query($sql);
	$xml="";
	for ($a=0; $a<mysql_num_rows($res); $a++){
		$row=mysql_fetch_array($res);
		$xml.="    <cc:$row[predicate] rdf:resource=\"$row[uri]\"/>\n";
	}   
	return $xml;;
}
?>
