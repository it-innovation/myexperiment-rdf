#!/usr/bin/php
<?php
	if ($argv[1]=="myexp_private"){
		$domain="private";
		$ts=$argv[1];
	}
	else{
		$domain="public";
		$ts="myexp_public";
	}
	$start=2;
				
	include('seetings.inc.php');
	include('changeddata.inc.php');
	include('genxml.inc.php');

	function saveEntities($ts,$type,$xml,$id,$userid,$hasuserid,$fileext=null){
		global $curids;
		$xml.=pagefooter();
                if ($hasuserid) $fid=$userid;
                else $fid=$id;
		if ($fileext && $fileext!="_") $fid.="_$fileext";
		$curids[]=$fid;
                $file="/var/data/tmp/$ts/".getGroup($type)."/$fid";
		$fileold="/var/data/$ts/".getGroup($type)."/$fid";
                $fh=fopen($file,'w');
                fwrite($fh,$xml);
                fclose($fh);
	 	$ph=popen("diff $file $fileold 2>&1",'r');
                $diff=fread($ph,8192);
                if (strlen($diff)==0){
                	exec("rm $file");
                }
                else{
                        exec("mv $file $fileold");
			if (substr($diff,0,5)=="diff:") echo "[".date("H:i:s")."] Created $fileold\n";
			else echo "[".date("H:i:s")."] Updated $fileold\n";
                }
	}
	$cdsql=$sql;
	$fh=fopen("/var/data/tmp/$ts/delete_files",'w');
	fclose($fh);
	$useuid=array('Downloads','Viewings');
	unset($cdsql['Downloads']);
	unset($cdsql['Viewings']);
	$sepusage=array('Workflow','Pack','File');
	if (sizeof($argv)>$start){
		$newcsql=array();
		for ($a=$start; $a<sizeof($argv); $a++){
			$newcsql[$argv[$a]]=$cdsql[$argv[$a]];
		}
		$cdsql=$newcsql;
	}
	foreach ($cdsql as $k => $v){
		$curids=array();
		$inuseuid=in_array($k,$useuid);
		$insepusage=in_array($k,$sepusage);
		$v=setUserAndGroups($v);
		echo "[".date("H:i:s")."] Checking ".getGroup($k)."\n";
		$res=mysql_query($v);
		$rows=mysql_num_rows($res);
		$xml=pageheader();
		$ents=0;
		$fileno=1;
		$curuserid="";
		$curcontrib="";
//		echo $rows;
		for ($i=0; $i<$rows; $i++){
			$row=mysql_fetch_assoc($res);
			if ($insepusage==1){
				unset($row['viewings_count']);
				unset($row['downloads_count']);
			}
			$id=$row['id'];
			if (!$row['user_id']) $row['user_id']="AnonymousUser";
			if (!$curuserid) $curuserid=$row['user_id'];
			if (!$curcontrib) $curcontrib=$row['contributable_type']."_".$row['contributable_id'];
			//print_r($currow);
			if (!$inuseuid || $row['user_id']!=$curuserid){		
				if (!in_array($k,$useuid)){
					$xml.=printEntity($row,$k,$mappings[$k],"$datauri$k/",'id','');
					$ents++;
				}
				if ($ents){
					if ($curuserid=="AnonymousUser") saveEntities($ts,$k,$xml,$id,$curuserid,$inuseuid,$curcontrib);
					elseif ($fileno>1) saveEntities($ts,$k,$xml,$id,$curuserid,$inuseuid,$fileno);
					else saveEntities($ts,$k,$xml,$id,$curuserid,$inuseuid);
					$fileno=1;
				}
				$ents=0;
				$xml=pageheader();
				//time_nanosleep(0,100000000);
			}
			else{
				if ($curcontrib!=$row['contributable_type']."_".$row['contributable_id'] && $curuserid=="AnonymousUser"){
					saveEntities($ts,$k,$xml,$id,$curuserid,$inuseuid,$curcontrib);
					$ents=0;
					$xml=pageheader();
				}
				elseif ($ents>1000 && $curuserid!="AnonymousUser"){
					saveEntities($ts,$k,$xml,$id,$curuserid,$inuseuid,$curcontrib);
                                        $ents=0;
                                        $fileno++;
                                        $xml=pageheader();
				}
				$xml.=printEntity($row,$k,$mappings[$k],"$datauri$k/",'id','');
				$ents++;
					
			}
			$curuserid=$row['user_id'];
			$curcontrib=$row['contributable_type']."_".$row['contributable_id'];
		}
		if ($ents>0){
	//		echo "Saving $id\n";
			if ($curuserid=="AnonymousUser") saveEntities($ts,$k,$xml,$id,$curuserid,$row['has_user_id'],$curcontrib);
			elseif ($fileno>1) saveEntities($ts,$k,$xml,$id,$curuserid,$row['has_user_id'],$fileno);
			else saveEntities($ts,$k,$xml,$id,$curuserid,$row['has_user_id']);
		}	
		if ($insepusage){
			$sqlsplit=explode("from",$v);
			unset($sqlsplit[0]);
			$endv=implode("from",$sqlsplit);
			$tname=$tables[$k];
			$usql="select $tname.id, contributions.viewings_count, contributions.downloads_count from $endv";
			$ures=mysql_query($usql);
			$uxml=pageheader();
			for ($u=0; $u<mysql_num_rows($ures); $u++){
				$urow=mysql_fetch_assoc($ures);
				$uxml.=printUsage($k,$urow['id'],$urow['viewings_count'],$urow['downloads_count']);
			}
			saveEntities($ts,$k,$uxml,'Usage','','');	
		}
		$ph=popen("/var/jena/scripts/sqs.sh $ts list-graphs | grep '/".getGroup($k)."/' | grep '[0-9]'",'r');
		$fnums=array();
		while (!feof($ph)){
			$curf=fgets($ph,8192);
			$curfb=explode("/",$curf);
			if(strlen(trim($curfb[sizeof($curfb)-1]))>0) $fnums[]=trim($curfb[sizeof($curfb)-1]);
		}
		pclose($ph);
		$ph=popen("ls /var/data/$ts/".getGroup($k)."/ | grep '[0-9]'",'r');
		$fnums2=array();
                while (!feof($ph)){
                        $curf=fgets($ph,8192);
                        if(strlen(trim($curf))>0) $fnums2[]=trim($curf);
                }
                pclose($ph);
		$dels=array_diff($fnums,$curids);
		$dels2=array_diff($fnums2,$curids);
/*		for ($d=0; $d<max(sizeof($curids),sizeof($dels),sizeof($dels2)); $d++){
			echo $curids[$d].",".$dels[$d].",".$dels2[$d]."\n";
		}*/
		foreach ($dels2 as $d2){
			if (!in_array($d2,$dels)) $dels[]=$d2;
		}
//		print_r($curid);
	//	echo "\n#####\n";
//		print_r($dels);
		if (is_array($dels)){
			$fh=fopen("/var/data/tmp/$ts/delete_files",'a');
			foreach ($dels as $del){
				$filedel="/var/data/$ts/".getGroup($k)."/$del\n";
	        	        fwrite($fh,$filedel);
			}
			fclose($fh);
		}
	}
	$fh=fopen("/var/jena/log/".$ts."_updated.log","w");
	$lastupdated=mktime(0,0,0)-120;
	fwrite($fh,$lastupdated);
	fclose($fh);	
?>
