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
	$rdffile="/var/data/tmp/$ts/data.rdf";
	$rdfgzfile="/var/data/$ts/data.rdf.gz";
				
	ini_set('include_path','/var/www/html/rdf/inc/');
	include('changeddata.inc.php');
	include('genxml.inc.php');

	$ddsql=$sql;
	unset($ddsql['Downloads']);
	unset($ddsql['Viewings']);
	echo "[".date("H:i:s")."] Creating temporary file $rdffile\n";
	$fh=fopen($rdffile,"w");
	fwrite($fh,pageheader());
	foreach ($ddsql as $k => $v){
		$v=setUserAndGroups($v);
		echo "[".date("H:i:s")."] Adding ".getGroup($k)."\n";
		$res=mysql_query($v);
		$rows=mysql_num_rows($res);
		$xml="";
		$ents=0;
		for ($i=0; $i<$rows; $i++){
			$row=mysql_fetch_assoc($res);
			$id=$row['id'];
			if (!$row['user_id']) $row['user_id']="AnonymousUser";
			$xml.=printEntity($row,$k,$mappings[$k],"$datauri$k/",'id','');
			$ents++;
			if ($ents==1000){
		                fwrite($fh,$xml);
				$ents=0;
				$xml="";	
			}	
		}
	        fwrite($fh,$xml);
	}
	fclose($fh);
 	echo "[".date("H:i:s")."] Adding Dataflows\n";
	exec("for dffile in `ls /var/data/dataflows/rdf/*`; do
        head -$[`wc -l \$dffile | awk 'BEGIN{FS=\" \"}{print $1}'`] \$dffile | tail -$[`wc -l \$dffile | awk 'BEGIN{FS=\" \"}{print $1}'`-46] >> $rdffile
done");
	$fh=fopen($rdffile,"a");
        fwrite($fh,pagefooter());
	fclose($fh);
	echo "[".date("H:i:s")."] Gzipping to $rdfgzfile\n";
	exec("gzip -c $rdffile > $rdfgzfile");	
	echo "[".date("H:i:s")."] Deleting temporary file $rdffile\n";
	exec("rm $rdffile");
	echo "[".date("H:i:s")."] Data dump of $ts complete\n";
?>
