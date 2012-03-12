#!/usr/bin/php
<?php
	include('include.inc.php');
	require_once('genrdf.inc.php');
	$ph=popen('ls '.$datapath.'dataflows/dump/','r');
	$temp="";
	while (!feof($ph)){
		$temp.=fread($ph,8192);
	}
	$curfiles=explode("\n",$temp);
	pclose($ph);
        $sql="select workflow_versions.id as wfvid from workflow_versions inner join contributions on workflow_versions.workflow_id=contributions.contributable_id and contributions.contributable_type='Workflow' inner join policies on contributions.policy_id=policies.id where workflow_versions.content_type_id in (".implode(',',$dataflow_contenttypes).") and policies.share_mode = 0";
        $res=mysql_query($sql);
	$dbfiles=array();
	for ($i=0; $i<mysql_num_rows($res); $i++){
		$dbfiles[]=mysql_result($res,$i,'wfvid');
	}
	foreach ($curfiles as $cf){
		if (!in_array($cf,$dbfiles)){
			exec("rm ".$datapath."dataflows/dump/$cf  2> /dev/null");
			echo "[".date("H:i:s")."] Removed RDF for components of workflow_versions $cf because permissions have changed or workflow has been deleted\n";
		}
	}
	$newfiles=array();
	foreach ($dbfiles as $df){
		if (!in_array($df,$curfiles)){
			$newfiles[]=$df;
		}
	}
	$filelocdir=$datapath."dataflows/inc/";
	foreach ($newfiles as $wfvid){
                if (file_exists($filelocdir.$wfvid)) continue;
                $sql="select * from workflow_versions where id='$wfvid'";
                $wfv=mysql_fetch_assoc(mysql_query($sql));
                $wget="wget -q -O /tmp/wfvc_$wfvid.xml -o /dev/null '${datauri}workflow.xml?id=$wfv[workflow_id]&versions=$wfv[version]&elements=components'";
                exec($wget);
                $parsedxml=parseXML(implode("",file("/tmp/wfvc_$wfvid.xml")));
                $wfvc=$parsedxml[0]['children'][0]['children'];
                if ($wfv['content_type_id']==2) $ent_uri=$datauri."workflows/$wfv[workflow_id]/versions/$wfv[version]#dataflows/1";
                else $ent_uri=$datauri."workflows/$wfv[id]/versions/$wfv[version]#dataflow";
                $dataflows=tabulateDataflowComponents($wfvc,$ent_uri,$wfv['content_type_id']);
                $fh=fopen($filelocdir.$wfvid,'w');
                if ($dataflows) fwrite($fh,generateDataflows($dataflows,$ent_uri));
                else fwrite($fh,"NONE");
                fclose($fh);
		echo "[".date("H:i:s")."] Generated RDF for components of workflow_versions $wfvid\n";
        }
	echo "[".date("H:i:s")."] Copying RDF for components to RDF dump directory and encapsulating in an RDF graph\n";
	foreach ($newfiles as $nf){
	 	$dataflows=pageheader().getDataflowComponents($nf,"workflow_versions","uris").pagefooter();
                $fh=fopen($datapath."dataflows/dump/$nf",'w');
                fwrite($fh,$dataflows);
                fclose($fh);
	}

?>
