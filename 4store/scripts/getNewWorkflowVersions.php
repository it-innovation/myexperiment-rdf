#!/usr/bin/php
<?php
	include('include.inc.php');
	include('myexpconnect.inc.php');
	$sql="select workflow_versions.id, workflow_versions.workflow_id, workflow_versions.version from workflow_versions inner join content_types on workflow_versions.content_type_id=content_types.id inner join contributions on workflow_versions.workflow_id=contributions.contributable_id and contributions.contributable_type='Workflow' inner join policies on contributions.policy_id=policies.id where content_types.mime_type in ('application/vnd.taverna.scufl+xml','application/vnd.taverna.t2flow+xml','application/vnd.galaxy.workflow+xml') and policies.share_mode = 0";
	$res=mysql_query($sql);
	for ($i=0; $i<mysql_num_rows($res); $i++){
		$wfv_db[]=mysql_result($res,$i,'id');
		$wfv_wv[]=mysql_result($res,$i,'id').",".mysql_result($res,$i,'workflow_id').",".mysql_result($res,$i,'version');
		
	}
	$cmd="ls ".$datapath."/dataflows/dump/";
	$ph=popen($cmd,'r');
	$lswfv="";
	while (!feof($ph)){
		$lswfv.=fread($ph,4096);
	}
	fclose($ph);
	$wfv_file=explode("\n",$lswfv);
	foreach($wfv_file as $k => $v){
 		if(strlen(trim($v))>0){
			$wfv_file[$k]=trim($v);
		}
		else{
			unset($wfv_file[$k]);
		}
	}
	$i=0;
	foreach ($wfv_db as $wfv){
		if (!in_array($wfv,$wfv_file)){
			echo "$wfv_wv[$i]\n";
		}
		$i++;
	}
?>
