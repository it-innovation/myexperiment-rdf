<?php
function getEntityURI($type,$id,$entity,$format=''){
	 global $datauri, $nesting, $annotatable;
	 if (isset($nesting[$type])){
	 	switch ($type){
			case 'attributions':
			case 'comments':
                        case 'creditations':
			case 'ratings':
			case 'reviews':
                        case 'policies':
                                if (isset($annotatable[$entity[$nesting[$type][0]]])) $entitytype = $annotatable[$entity[$nesting[$type][0]]]; 
				else $entitytype = $entity[$nesting[$type][0]];
				return $datauri.$entitytype."/".$entity[$nesting[$type][1]]."/$type/$id";
			case 'citations': 
				return $datauri."workflows/".$entity[$nesting[$type][0]]."/versions/".$entity[$nesting[$type][1]]."/$type/$id";
			case 'favourites':
			case 'friendships':
			case 'memberships':
				return $datauri."users/".$entity[$nesting[$type][0]]."/$type/$id";
			case 'jobs':
				return $datauri."experiments/".$entity[$nesting[$type][0]]."/$type/$id";
			case 'local_pack_entries':
			case 'remote_pack_entries':
			case 'pack_relationships':
				return $datauri."packs/".$entity[$nesting[$type][0]]."/$type/$id";
			case 'taggings':
				return $datauri."tags/".$entity[$nesting[$type][0]]."/$type/$id";
			case 'ontologies':
                                return $entity['uri'];
			case 'predicates':
				return $entity['ontology_uri']."/".$entity['title'];
			default:
				break;
		}
	 }
         elseif ($type=="workflow_versions"){
	        $wvsql="select workflow_id, version from workflow_versions where id=$id";
                $wvres=mysql_query($wvsql);
                return $datauri."workflows/".mysql_result($wvres,0,'workflow_id')."/versions/".mysql_result($wvres,0,'version');
         }
         elseif ($type=="group_announcements"){
   	 	$gasql="select network_id from group_announcements where id=$id";
                $gares=mysql_query($gasql);
                return $datauri."groups/".mysql_result($gares,0,'network_id')."/announcements/".$id;
         }
	 elseif ($type=="ontologies") return $entity['uri'];
         return $datauri."$type/$id";
}

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
	if (!$mship['user_established_at']){
		if ($mship['user_id']) return "users/".$mship['user_id'];
		return "";
	}
	elseif(!$mship['network_established_at']) return "groups/".$mship['network_id'];
	else{
		$utime=strtotime($mship['user_established_at']);
		$ntime=strtotime($mship['network_established_at']);
		if ($utime<=$ntime) return "groups/".$mship['network_id'];
		else return "users/".$mship['user_id'];	
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

function getMembers($group){
	global $datauri;
	$xml="";
        $msql = "select * from memberships where network_id=$group[id] and network_established_at is not null and user_established_at is not null";
	$mres=mysql_query($msql);
        $xml.="    <sioc:has_member rdf:resource=\"${datauri}users/".$group['user_id']."\"/>\n";
        for ($m=0; $m<mysql_num_rows($mres); $m++){
        	$xml.="    <sioc:has_member rdf:resource=\"${datauri}users/".mysql_result($mres,$m,'user_id')."\"/>\n";
        }
	return $xml;
}

function getMemberships($user){
	global $sql, $datauri;
	$xml="";
	$msql=$sql['memberships']." where user_id=$user[id]";
	$mres=mysql_query($msql);
	for ($m=0; $m<mysql_num_rows($mres); $m++){
		$xml.="    <mebase:has-membership rdf:resource=\"${datauri}users/$user[id]/memberships/".mysql_result($mres,$m,'id')."\"/>\n";
	}
	return $xml;
}
function getFriendships($user){
        global $sql, $datauri;
	$xml="";
	$fsql=addWhereClause($sql['friendships'],"user_id=$user[id] or friend_id=$user[id]");
        $fres=mysql_query($fsql);
        for ($f=0; $f<mysql_num_rows($fres); $f++){
                $xml.="    <mebase:has-friendship rdf:resource=\"${datauri}users/".mysql_result($fres,$f,'user_id')."/friendships/".mysql_result($fres,$f,'id')."\"/>\n";
        }
        return $xml;
}
function getFavourites($user){
	global $sql, $datauri;
	$xml="";
        $fsql=addWhereClause($sql['favourites'],"user_id=$user[id]");
        $fres=mysql_query($fsql);
        for ($f=0; $f<mysql_num_rows($fres); $f++){
                $xml.="    <mebase:has-favourite rdf:resource=\"${datauri}users/$user[id]/favourites/".mysql_result($fres,$f,'id')."\"/>\n";
        }
        return $xml;
}
function getUser($action,$hash=0){
	global $salt, $data, $ontopath;
	if ($action['user_id'] && $action['user_id']!="AnonymousUser"){
		if ($hash) return "users/".md5($salt.$action['user_id']);
	 	return "users/".$action['user_id'];
	}
	return $ontopath."specific/AnonymousUser";
}
function getTaggings($tag){
	global $sql, $datauri;
	$xml="";
	$tsql=addWhereClause($sql['taggings'],"tag_id=$tag[id]");
	$tres=mysql_query($tsql);
	for ($t=0; $t<mysql_num_rows($tres); $t++){
		$row=mysql_fetch_assoc($tres);
		$xml.="    <meannot:for-tagging rdf:resource=\"".getEntityURI('taggings',$row['id'],$row)."\"/>\n";
	}
	return $xml;
}
function getPolicyURI($contrib,$type){
	if ($type=="workflow_versions"){
		$policy['contributable_type']="workflows";
		$policy['contributable_id']=$contrib['workflow_id'];
	}
	else{
		$policy['contributable_type']=$type;
		$policy['contributable_id']=$contrib['id'];
	}
	
	return getEntityURI('policies',$contrib['policy_id'],$policy);
}
function getThumbnail($workflow){
	$url=getUrl($workflow,'thumb');
	if (isset($workflow['format'])) $format = $workflow['format'];
        else $format = "";
	return $url;
}
function getThumbnailBig($workflow){
	$url=getUrl($workflow,'medium');
	if (isset($workflow['format'])) $format = $workflow['format'];
        else $format = "";
	return $url;
}
function getPreview($workflow){
        $url=getUrl($workflow,'full');
	if (isset($workflow['format'])) $format = $workflow['format'];
        else $format = "";
	return $url;
}
function getSVG($workflow){	
	$url=getUrl($workflow,'svg');
	if (isset($workflow['format'])) $format = $workflow['format'];
        else $format = "";
	return $url;
}
function workflowOrVersion($entity){
	global $datauri;
	if (isset($entity['current_version'])) $type="Workflow";
        else $type="WorkflowVersion";
	return $datauri.$type."/".$entity['id'];
}
function getUrl($workflow,$type=""){
 	global $datauri;
        if (isset($workflow['workflow_id'])){
                return $datauri."workflows/".$workflow['workflow_id']."/versions/".$workflow['version']."/previews/".$type;
        }
        return $datauri."workflows/".$workflow['id']."/previews/".$type;
}
function getWorkflowDownloadUrl($workflow){
	global $datauri;
	if (isset($workflow['workflow_id'])){
                $url=$datauri."workflows/".$workflow['workflow_id']."/download/".urlencode($workflow['unique_name']).".".$workflow['file_ext']."?version=".$workflow['version'];
		$table="workflow_versions";
		$id=$workflow['workflow_id'];
        }
        else{
		$url=$datauri."workflows/".$workflow['id']."/download/".urlencode($workflow['unique_name']).".".$workflow['file_ext'];
		$table="workflows";
		$id= $workflow['id'];
	}
//	print_r($workflow);
	$ctsql="select mime_type from content_types where id=$workflow[content_type_id]";
//	echo "<!-- $ctsql -->\n";
	$ctres=mysql_query($ctsql);
//	print_r(mysql_fetch_assoc($ctres));
	if (isset($workflow['format'])) $format = $workflow['format'];
	else $format = "";
	return $url;
	
}
function getFileDownloadUrl($file){
	global $datauri;
	return $datauri."blobs/".$file['id']."/download/".urlencode($file['local_name']);
}
	
function getLicense($contrib){
	global $licenses;
	return $licenses[$contrib['license']];
}
function getCurrentWorkflowVersion($workflow){
	global $mappings, $datauri, $sql;
        $wvsql=$sql['workflow_versions']. " and workflow_id=$workflow[id] and version=$workflow[current_version]";
        $wvsql=str_replace(array('~','?'),array('0','0'),$wvsql);
        $res=mysql_query($wvsql);
       	$row=mysql_fetch_assoc($res);
	$aggregates="workflows/$workflow[id]/versions/$workflow[current_version]";
	if (isset($workflow['format'])) $format = $workflow['format'];
        else $format = "";
        return $aggregates;
}
function isCurrentVersion($workflowversion){
	$sql="select workflows.id from workflows inner join workflow_versions on workflows.current_version=workflow_versions.version and workflows.id=workflow_versions.workflow_id where workflow_versions.id=$workflowversion[id]";
//	echo $sql;
	$res=mysql_query($sql);
	return mysql_num_rows($res);
}
function getWorkflowVersions($workflow){
	global $mappings, $datauri, $sql;
	$wvsql=$sql['workflow_versions']. " and workflow_id=$workflow[id] and version!=$workflow[current_version]";
	$res=mysql_query($wvsql);
	$aggregates="";
	for ($i=0; $i<mysql_num_rows($res); $i++){
		$row=mysql_fetch_assoc($res);
		 if (isset($workflow['format'])) $format = $workflow['format'];
        	else $format = "";
		
		if ($format=="ore") $aggregates.="    <ore:aggregates rdf:resource=\"".$datauri."workflows/".$row['workflow_id']."/versions/".$row['version']."\"/>\n";
		else $aggregates.="    <mebase:has-version rdf:resource=\"".$datauri."workflows/".$row['workflow_id']."/versions/".$row['version']."\"/>\n";
	}
	return $aggregates;	
}
function foafPictureURL($pic_id){
	global $datauri;
	return $datauri."pictures/show/$pic_id?size=160x160.png";
}
function pictureURL($user){
       	global $datauri;
       	if (isset($user['avatar_id']) && $user['avatar_id']>0) return $datauri."pictures/show/".$user['avatar_id']."?size=160x160.png";
	return $datauri."images/avatar.png";
}
function validateEmail($email){
       if (!preg_match("/^[_a-zA-Z0-9-]+(\.[_a-zA-Z0-9-]+)*@[a-zA-Z0-9-]+(\.[a-zA-Z0-9-]+)*(\.[a-zA-Z]{2,3})$/", $email)) return 0;
       return 1;
}

function mailto_foaf($email){
	if (validateEmail($email)) return "mailto:".$email;
}
function getSiocAndFoafName($user){
	global $datatypes;
	return "    <sioc:name rdf:datatype=\"&xsd;".$datatypes['sioc:name']."\">$user[name]</sioc:name>\n    <foaf:name rdf:datatype=\"&xsd;".$datatypes['foaf:name']."\">$user[name]</foaf:name>\n";
}   
function mailto($user){
	global $userid, $domain;
	if (validateEmail($user['email']) && ($userid==$user['id'] || $domain=="private")) return "mailto:".$user['email'];
}
function mbox_sha1sum($user){
	return sha1($user['email']);
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
	$residence="";
	$mats=array("/",",","  ");
	$reps=array(" ",", "," ");
	if(isset($user['location_city']) && strlen(trim($user['location_city']))>0) {
		$city = str_replace("+","_",urlencode(my_ucwords(trim(str_replace($mats,$reps,$user['location_city'])))));
		$residence.="    <dbpedia:residence rdf:resource=\"http://dbpedia.org/resource/$city\"/>\n";
	}
	if(isset($user['location_country']) && strlen(trim($user['location_country']))>0) {
		$country = str_replace("+","_",urlencode(my_ucwords(trim(str_replace($mats,$reps,$user['location_country'])))));
		$residence.="    <dbpedia:residence rdf:resource=\"http://dbpedia.org/resource/$country\"/>\n";
	}
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
	return  "packs/".$entry['pack_id'];
}
function getVersionID($entity){
	if ($entity['contributable_type']=="Workflow") $sql="select id from workflow_versions where workflow_id=$entity[contributable_id] and version=$entity[contributable_version]";
	if ($sql){
		$res=mysql_query($sql);
		return mysql_result($res,0,'id');
	}
}
	
function getHomepage($entity,$type){
	global $hspent, $datauri;	
	$gtype=$hspent[$type]."/";
	if ($type=="workflow_versions"){
		$gtype="workflows/".$entity['workflow_id']."/versions/";
		$entity['id']=$entity['version'];
	}
	elseif ($type=="jobs") $gtype="experiments/".$entity['experiment_id']."/jobs/";
	elseif ($type=="group_announcements") $gtype="groups/".$entity['network_id']."/announcements/";
	elseif ($type=="reviews") $gtype=$hspent[$entity['reviewable_type']]."/".$entity['reviewable_id']."/reviews/";
	$url=$datauri.$gtype.$entity['id'];
	if (isset($entity['format'])) $format = $entity['format'];
        else $format = "";
	if ($url) return $url.".html";
	return "";
	
}
function getFilename($entity,$type){
	if ($type=="Workflow"||$type="WorkflowVersion") return $entity['unique_name'].".".$entity['file_ext'];
	return '';
}
function getPackEntries($pack){
	global $datauri;
	$lsql="select * from pack_contributable_entries where pack_id=$pack[id]";
	$lres=mysql_query($lsql);
	$xml="";
	$packurl=getEntityURI('packs',$pack['id'],$pack);
	for ($e=0; $e<mysql_num_rows($lres); $e++){
		$xml.="    <mepack:has-pack-entry rdf:resource=\"$packurl/local_pack_entries/".mysql_result($lres,$e,'id')."\"/>\n";
	}
	$rsql="select * from pack_remote_entries where pack_id=$pack[id]";
	$rres=mysql_query($rsql);
        for ($e=0; $e<mysql_num_rows($rres); $e++){
                $xml.="    <mepack:has-pack-entry rdf:resource=\"$packurl/remote_pack_entries/".mysql_result($rres,$e,'id')."\"/>\n";
        }
	return $xml;
}

	
function getOutput($entity){
	global $datauri;
	$xml="";
	if ($entity['outputs_uri']){		
		$uri=getEntityURI('jobs',$entity['id'],$entity);
		$xml="      <meexp:Data rdf:about=\"$uri/output\">\n";
		if ($entity['outputs_uri']) $xml.= "        <mebase:uri rdf:resource=\"$entity[outputs_uri]\"/>\n";

		$xml.="      </meexp:Data>";
	}
	return $xml;
}
function getInput($entity){
        global $datauri;
	$xml="";
	if ($entity['inputs_uri'] || $entity['inputs_data']){
		$uri=getEntityURI('jobs',$entity['id'],$entity);
		$xml="<meexp:Data rdf:about=\"$uri/input\">\n";
		if ($entity['inputs_uri']) $xml.= "        <mebase:uri rdf:resource=\"$entity[inputs_uri]\"/>\n";
		if ($entity['inputs_data']) $xml.= "        <mebase:text rdf:datatype=\"&xsd;string\">$entity[inputs_data]</mebase:text>\n";
		$xml.="      </meexp:Data>";
	}
        return $xml;
}
function getRunnable($entity){
	global $datauri, $mappings, $sql, $annotatable;
	if ($entity['runnable_version']){
		$cursql="select id from workflow_versions where workflow_id=$entity[runnable_id] and version=$entity[runnable_version]";
                $res=mysql_query($cursql);
		if (mysql_num_rows($res)>0) $id=mysql_result($res,0,'id');
		else $id='0';
		$type="workflow_versions";
		$idf="workflow_versions.id";
	}
	else{
		$id=$entity['runnable_id'];
		$type=$annotatable[$entity['runnable_type']];
		$idf="workflows.id";
	}
	$runnable=$annotatable[$entity['runnable_type']]."/".$entity['runnable_id'];
	if ($entity['runnable_version']) $runnable.="/versions/".$entity['runnable_version'];
	
	$cursql="$sql[$type] and $idf=$id";
	$res=mysql_query($cursql);
        if ($res!== false || mysql_num_rows($res)==0) return "";
	if (isset($entity['format'])) $format = $entity['format'];
        else $format = "";
	return $runnable;
}
function getJobURI($entity){
	global $datauri;
	if (isset($entity['format'])) $format = $entity['format'];
        else $format = "";
	return $entity['job_uri'];
}
function getRunner($entity){
	global $datauri, $mappings, $sql;
	$runner="runners/$entity[runner_id]";
	$cursql=$sql['runners']." where id=$entity[runner_id]";
	$res=mysql_query($cursql);
//	echo $cursql;
	if (isset($entity['format'])) $format = $entity['format'];
        else $format = "";
	return $runner;
}
function getProxyFor($entity){
	global $ontent, $modelalias;
	$xml="";
	if (isset($entity['contributable_type'])){
		if ($entity['contributable_version']){
			$entity['contributable_id']=getVersionID($entity);
			if ($entity['contributable_type']=="Workflow") $entity['contributable_type']="WorkflowVersion";
		}
		if (in_array($entity['contributable_type'],$modelalias)) $etype=array_search($entity['contributable_type'],$modelalias);
		else $etype = $entity['contributable_type'];
		$etype=array_search($etype,$ontent);
		return $etype."/".$entity['contributable_id'];
	}
	$xml.="    <rdf:Description rdf:about=\"".str_replace("&","&amp;",$entity['uri'])."\"";
        if ($entity['alternate_uri']) $xml.=">\n      <rdfs:seeAlso>\n        <rdf:Description rdf:about=\"".str_replace("&","&amp;",$entity['alternate_uri'])."\"/>\n      </rdfs:seeAlso>\n    </rdf:Description>\n";
	else $xml.="/>";
        return $xml;
}
function getDataflowComponents($entity,$type){
	global $datauri,$datapath;
	$comp_path=$datapath."dataflows/xml/";
	require_once('xmlfunc.inc.php');
	if ($type=="workflows"){
		require_once('myexpconnect.inc.php');
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
		$xml="";
 		while(!feof($fh)){
                	$xml.=fgets($fh);
	        }
        	fclose($fh);
		$dataflows=tabulateDataflowComponents(parseXML($xml),$ent_uri);
		if ($dataflows) return generateDataflows($dataflows,$ent_uri);
	}
}
	
function getDataflow($entity,$type){
	global $datauri,$datapath;
	$comp_path=$datapath."dataflows/rdf/";
 	if ($type=="workflows"){
                require_once('myexpconnect.inc.php');
                $sql="select id from workflow_versions where version='$entity[current_version]' and workflow_id='$entity[id]'";
                $res=mysql_query($sql);
                $wfvid=mysql_result($res,0,'id');
		$type="workflow_versions";
                $fileloc=$comp_path.$wfvid;
		$id=$entity['id'];
		$version=$entity["current_version"];
                
        }
        elseif ($type=="workflow_versions"){
                $fileloc=$comp_path.$entity['id'];
		$wfvid=$entity['id'];
		$id=$entity['workflow_id'];
		$version=$entity['version'];
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
		if (mysql_result($res,0,'content_type_id')==2) return $datauri."workflows/$id/versions/$version#dataflows/1";
		return $datauri."workflows/$id/versions/$version#dataflow";
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
function getAnnotationSQL($type, $p1,$p2){
	global $sql, $annotwhereclause;
	$cursql=addWhereClause($sql[$type],str_replace('~',$p2,str_replace('?',$p1,$annotwhereclause[$type])));
	return $cursql;
	
}
function getAnnotations($entity,$type){
	global $entannot, $ontent, $modelalias, $annotprop, $datauri;
	$ea = $entannot[$type];
	$atype=$ontent[$type];
	if (isset($modelalias[$atype])) $atype=$modelalias[$atype];
	$xml="";
	foreach ($ea as $annot){
		if ($annot=="citations") $cursql=getAnnotationSQL($annot,$entity['workflow_id'],$entity['version']);
		else  $cursql=getAnnotationSQL($annot,$atype,$entity['id']);
		$res = mysql_query($cursql);
	        for ($a=0; $a<mysql_num_rows($res); $a++){
			$row=mysql_fetch_assoc($res);
                        $auri=getEntityURI($annot,$row['id'],$row);
                //        $xml.="<!-- ".var_export($row,true)." -->\n";
                        $xml.="    <meannot:".$annotprop[$annot]." rdf:resource=\"$auri\"/>\n";
		}
	}
	return $xml;
}
function getPackRelationships($pack){
	global $sql, $datauri;
	$rsql=$sql['pack_relationships'];
	if (strpos("where",$rsql)>0) $rsql.=" and ";
	else $rsql.=" where ";
	$rsql.="pack_id=$pack[id]";
	$res=mysql_query($rsql);
	$xml="";
	if ($res!==false){
		for ($r=0; $r<mysql_num_rows($res); $r++){
			$xml.="    <mepack:has-pack-relationship rdf:resource=\"${datauri}packs/$pack[id]/relationships/".mysql_result($res,$r,'id')."\"/>\n";
		}
	}
	return $xml;
}

// Needs rewriting once predicate relationships are introduced.
function getPredicateRelations($entity){
	global $sql, $datauri;
	$xml="";
	$predicatesql=$sql['predicate_relations'];
	if (!stripos('where',$sql['predicate_relations'])) $predicatesql.=" where ";
        else $predicatesql.=" and ";
        $predicaterelsql=$predicatesql."subject_predicate_id=$entity[id]";
        $res=mysql_query($predicaterelsql);
	if ($res!==false){
	        for ($r=0; $r<mysql_num_rows($res); $r++){
        	        $row=mysql_fetch_assoc($res);
                	$xml.="    <rdfs:subClassOf rdf:resource=\"$entity[ontology_uri]/$row[object_predicate_id]\"/>\n";
	        }
	}
	return $xml;
}
function getRelationshipSubject($entity){
	return getRelationshipNode($entity['subject_id'],$entity['subject_type']);
}
function getRelationshipObject($entity){
        return getRelationshipNode($entity['objekt_id'],$entity['objekt_type']);
}
function getRelationshipPredicate($entity){
	return getPredicate($entity['predicate_id']);
}
function getRelationshipNode($id,$type){
	global $datauri, $tables;
	$oetype=getOntologyEntityTypeFromDBType($type);
	$table=$tables[$oetype];
	$nsql="select * from $table where id = $id";
	$res=mysql_query($nsql);
	$row=mysql_fetch_assoc($res);
	if ($type=="PackRemoteEntry") return $row['uri'];
	if ($type=="PackContributableEntry"){
		$uri = $datauri.getOntologyEntityTypeFromDBType($row['contributable_type'])."/".$row['contributable_id'];
		if ($row['contributable_version']) return $uri."/versions/".$row['contributable_version'];
		return $uri;
	}
}
function getPredicate($id){
	$predsql="select ontologies.uri as ontology_uri, predicates.title as predicate from predicates inner join ontologies on predicates.ontology_id=ontologies.id where predicates.id=$id";
	$res=mysql_result($predsql);
	return mysql_result($res,0,'ontology_uri')."/".mysql_result($res,0,'predicate');
}
function printPredicates($id){
	global $sql;
	$predssql=$sql['predicates'];
	if (!stripos('where',$sql['predicates'])) $predssql.=" where ";
        else $predssql.=" and ";
	$predssql.="predicates.ontology_id=$id";
	echo $predssql;
	$res=mysql_query($predssql);
	$xml="";
	for ($p=0; $p<mysql_num_rows($res); $p++){
		$xml.=printEntity(mysql_fetch_assoc($res),"predicates");
	}
	return $xml;
}
	
function getOntologyEntityTypeFromDBType($type){
	global $modelalias, $ontent;
	if (in_array($type,$modelalias)) $type=array_search($type,$modelalias);
	if (in_array($type,$ontent)) $type=array_search($type,$ontent);
	return $type;
}
function getStaticOntologyDetails($entity){
	return "    <dc:language rdf:datatype=\"&xsd;string\">en</dc:language>\n    <dc:publisher rdf:resource=\"http://www.myexperiment.org\"/>\n    <dc:format rdf:datatype=\"&xsd;string\">rdf/xml</dc:format>\n";
}

?>
