#!/usr/bin/php
<?php
	ini_set('include_path','/var/www/html/rdf/inc');
	include('genxml.inc.php');
	$ph=popen('ls /var/data/dataflows/xml/','r');
	$ph2=popen('ls /var/data/dataflows/reasoned/','r');
	$temp="";
	while (!feof($ph)){
		$temp.=fread($ph,8192);
	}
	$xmlfiles=explode("\n",$temp);
	$temp="";
	while (!feof($ph2)){
                $temp.=fread($ph2,8192);
        }

	$resfiles=explode("\n",$temp);
	//$resfiles=array();
	pclose($ph);
	pclose($ph2);
	$fhlist=fopen('/tmp/dataflows.txt','w');
	$dellist=fopen('/tmp/deldataflows.txt','w');
	//$xmlfiles=array('542');
	foreach ($xmlfiles as $xf){
		if (!in_array($xf,$resfiles)){
			$xmllines=file("/var/data/dataflows/xml/$xf");
			if (sizeof($xmllines)<=2){
					exec("rm /var/data/$argv[1]/reasoned/$xf /var/data/dataflows/rdf/$xf  2> /dev/null");
					if ($argv[1]=="myexp_public") fwrite($dellist,"/var/data/$argv[1]/dataflows/$xf\n");
			}
			else{
				$dataflows=pageheader();
				$dataflows.=getDataflowComponents(array('id'=>$xf),"workflow_versions","uris");
				$dataflows.=pagefooter();
				$fh=fopen("/var/data/dataflows/rdf/$xf",'w');
	                        fwrite($fh,$dataflows);
        	                fclose($fh);
				fwrite($fhlist,"/var/data/dataflows/rdf/$xf\n");
				echo "[".date("H:i:s")."] Generated RDF for components of workflow_versions $xf\n";
			}
		}
	}
	fclose($fhlist);
	fclose($dellist);
?>
