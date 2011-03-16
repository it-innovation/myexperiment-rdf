#!/usr/bin/php
<?php
	include('include.inc.php');
	include('myexpconnect.inc.php');
	$sql="select workflow_versions.id, workflow_versions.workflow_id, workflow_versions.version from workflow_versions inner join content_types on workflow_versions.content_type_id=content_types.id inner join contributions on workflow_versions.workflow_id=contributions.contributable_id and contributions.contributable_type='Workflow' inner join policies on contributions.policy_id=policies.id where content_types.mime_type in ('application/vnd.taverna.scufl+xml','application/vnd.taverna.t2flow+xml') and policies.share_mode=0 order by id";
	$res=mysql_query($sql);
	for ($i=0; $i<mysql_num_rows($res); $i++){
		echo mysql_result($res,$i,'id')."\n";	
	}
?>
