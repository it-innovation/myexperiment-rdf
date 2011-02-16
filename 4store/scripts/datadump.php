#!/usr/bin/php
<?php
        $domain="public";
	include('include.inc.php');
        include('changeddata.inc.php');
        include('genrdf.inc.php');
	
        $ts=$triplestore;
	$start=2;
	$rdffile="$datapath$ts/myexperiment.rdf";
	$rdfgzfile="$datapath$ts/myexperiment.rdf.gz";
				
	$ddsql=$sql;
	echo "[".date("H:i:s")."] Creating temporary file $rdffile\n";
	$fh=fopen($rdffile,"w");
	fwrite($fh,pageheader());
	foreach ($ddsql as $k => $v){
		$v=setUserAndGroups($v);
		echo "[".date("H:i:s")."] Adding $k\n";
		$res=mysql_query($v);
		if ($res!==false){
			$rows=mysql_num_rows($res);
			$xml="";
			$ents=0;
			for ($i=0; $i<$rows; $i++){
				$row=mysql_fetch_assoc($res);
				$id=$row['id'];
				if (!isset($row['user_id'])) $row['user_id']="AnonymousUser";
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
		else{
			 echo "[".date("H:i:s")."] Invalid query <$v>\n";
		}
	}
	fclose($fh);
 	echo "[".date("H:i:s")."] Adding dataflows\n";
	exec("for dffile in `ls ".$datapath."dataflows/rdf/*`; do
        head -$[`wc -l \$dffile | awk 'BEGIN{FS=\" \"}{print $1}'`] \$dffile | tail -$[`wc -l \$dffile | awk 'BEGIN{FS=\" \"}{print $1}'`-48] >> $rdffile
done");
	$fh=fopen($rdffile,"a");
        fwrite($fh,pagefooter());
	fclose($fh);
	echo "[".date("H:i:s")."] Calculating the number of triples and saving to ${lddir}4store/log/${ts}_datadump_triples.log.\n";
	exec("rapper -c $rdffile 2>&1 | tail -n 1 | awk 'BEGIN{FS=\" \"}{print $4}' > ${lddir}/4store/log/${ts}_datadump_triples.log");
	echo "[".date("H:i:s")."] Gzipping to $rdfgzfile\n";
	exec("gzip -c $rdffile > $rdfgzfile");	
	echo "[".date("H:i:s")."] Data dump of $ts complete\n";
?>
