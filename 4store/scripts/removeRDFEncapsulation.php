<?php
	chdir(dirname($argv[0]));
	$ph=popen("ls ../../data/dataflows/dump/*",'r');
	$filesdata="";
	while (!feof($ph)){
		$filesdata.=fgets($ph,8192);
	}
	pclose($ph);
	$files=explode("\n",$filesdata);
	echo "Extracting RDF from files in data/dataflows/dump/ and copying to data/dataflows/inc/\n";
	echo("Processing");
	foreach ($files as $file){
		$filebits=explode("/",$file);
		if (!is_numeric($filebits[sizeof($filebits)-1])) continue;
		$lines=file($file);
		$l=0;
		foreach($lines as $l => $line){
			if (preg_match("/<mecomp:Dataflow/",$line)) break;
		}
		$fh=fopen("../../data/dataflows/inc/".$filebits[sizeof($filebits)-1],'w');
		fwrite($fh,implode("",array_slice($lines,$l,-1)));
		fclose($fh);
		print(".");
	}
	print "Done";
	echo "\n";
?>
