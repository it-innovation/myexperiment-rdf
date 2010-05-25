#!/usr/bin/php
<?php
	$domain="public";
	include('include.inc.php');
	include('changeddata.inc.php');
	include('genrdf.inc.php');
        $ts=$triplestore;
        $start=2;


	function saveEntities($ts,$type,$xml,$id,$userid,$fileext=null){
		global $curids, $datapath;
		$xml.=pagefooter();
                if ($hasuserid) $fid=$userid;
                else $fid=$id;
		if ($fileext && $fileext!="_") $fid.="_$fileext";
		$curids[]=$fid;
                $file=$datapath."tmp/$ts/$type/$fid";
		$fileold=$datapath.$ts."/$type/$fid";
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
	$fh=fopen($datapath."tmp/$ts/delete_files",'w');
	fclose($fh);
	$sepusage=array('workflows','packs','files');
	if (sizeof($argv)>$start){
		$newcsql=array();
		for ($a=$start; $a<sizeof($argv); $a++){
			$newcsql[$argv[$a]]=$cdsql[$argv[$a]];
		}
		$cdsql=$newcsql;
	}
	foreach ($cdsql as $k => $v){
		$curids=array();
		$insepusage=in_array($k,$sepusage);
		$v=setUserAndGroups($v);
		echo "[".date("H:i:s")."] Checking $k\n";
		$res=mysql_query($v);
		$rows=mysql_num_rows($res);
		$xml=pageheader();
		$ents=0;
		$fileno=1;
		$curuserid="";
		$curcontrib="";
		for ($i=0; $i<$rows; $i++){
			$row=mysql_fetch_assoc($res);
			if ($insepusage==1){
				unset($row['viewings_count']);
				unset($row['downloads_count']);
			}
			$id=$row['id'];
			$xml.=printEntity($row,$k,$mappings[$k],"$datauri$k/",'id','');
			saveEntities($ts,$k,$xml,$id,$curuserid);
			$xml=pageheader();
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
			saveEntities($ts,$k,$uxml,'usage','');	
		}
		$ph=popen($lddir."4store/scripts/sqs.sh $ts list-graphs | grep '/$k/' | grep '[0-9]'",'r');
		$fnums=array();
		while (!feof($ph)){
			$curf=fgets($ph,8192);
			$curfb=explode("/",$curf);
			if(strlen(trim($curfb[sizeof($curfb)-1]))>0) $fnums[]=trim($curfb[sizeof($curfb)-1]);
		}
		pclose($ph);
		$ph=popen("ls $datapath$ts/$k/ | grep '[0-9]'",'r');
		$fnums2=array();
                while (!feof($ph)){
                        $curf=fgets($ph,8192);
                        if(strlen(trim($curf))>0) $fnums2[]=trim($curf);
                }
                pclose($ph);
		$dels=array_diff($fnums,$curids);
		$dels2=array_diff($fnums2,$curids);
		foreach ($dels2 as $d2){
			if (!in_array($d2,$dels)) $dels[]=$d2;
		}
		if (is_array($dels)){
			$fh=fopen($datapath."tmp/$ts/delete_files",'a');
			foreach ($dels as $del){
				$filedel="$datapath$ts/$k/$del\n";
	        	        fwrite($fh,$filedel);
			}
			fclose($fh);
		}
	}
	$fh=fopen($lddir."4store/log/".$ts."_updated.log","w");
	$lastupdated=mktime(0,0,0)-120;
	fwrite($fh,$lastupdated);
	fclose($fh);	
?>
