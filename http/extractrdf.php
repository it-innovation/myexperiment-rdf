<?php
	$domain="public";
	require('inc/genxml.inc.php');
	$type=$_GET['type'];
	$id=$_GET['id'];
	$params=explode('/',$_GET['params']);
	$uri=$datauri."$type/$id/".$_GET['params'];
	if ($type=="workflow_versions"){
		if ($params[0]=="dataflows"||$params[0]=="dataflow"){
			header('Content-type: application/rdf+xml');
		      	echo pageheader();
			$filename=$datapath."ld_dataflows/rdf/$id";
			$lines=file($filename);
			$l=0;
			while ((strpos($lines[$l],"rdf:about=\"$uri\"") === FALSE) && ($l<sizeof($lines))){
				$l++;
			}
			$ebits=explode(" ",str_replace(array("<",">"),"",trim($lines[$l])));	
			while ((strpos($lines[$l],'</'.$ebits[0].'>') === FALSE) && ($l<sizeof($lines))){
				echo $lines[$l];
                                $l++;
			}
			echo $lines[$l];
			echo pagefooter();

		}
	}
	if ($type=="workflows" && $params[0]=="versions"){
		$wvsql="select * from workflow_versions where workflow_id=$id and version=$params[1]";
		$wvres=mysql_query($wvsql);
		$wvrow=mysql_fetch_assoc($wvres);
		$uri=$guidedatauri."workflow_versions/$wvrow[id]";
//		echo $uri;
		header("Location: $uri");
	}
?>
