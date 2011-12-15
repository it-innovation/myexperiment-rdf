#!/usr/bin/php
<?php
	$entity_sizes=array();
	include('include.inc.php');
	echo "========= ".date('D M d H:i:s T Y')." =========\n";
	if (file_exists("${lddir}rdfgen/entity_sizes.txt")){
		$lines=file("${lddir}rdfgen/entity_sizes.txt");
		foreach ($lines as $line){
			$lbits=explode(" ",$line);
			$entity_sizes[$lbits[0]]=array('id'=>$lbits[1],'size'=>trim($lbits[2]));
		}
	}
	else{
		$argv[1]="Regenerate";
		$lines=file("${lddir}rdfgen/entity_sizes.txt.pre");
                foreach ($lines as $line){
                        $lbits=explode(" ",$line);
                        $entity_sizes[$lbits[0]]=array('id'=>$lbits[1],'size'=>trim($lbits[2]));
                }
	}
	if (isset($argv[1]) && $argv[1]=="Regenerate") $fh=fopen("${lddir}rdfgen/entity_sizes.txt",'w');
	foreach ($entity_sizes as $entity => $entsize){
		echo "Checking $entity/$entsize[id]:\n";
		$ph=popen("${lddir}rdfgen/rdfgencli.php $entity $entsize[id] | wc -l",'r');
		$entsize['newsize']=trim(fgets($ph,8192));
		fclose($ph);
		if ($entsize['newsize']<$entsize['size']){
			file_put_contents("php://stderr","Current size of $entity/$entsize[id] is smaller than previous size ($entsize[newsize] v $entsize[size])");
			echo "\nERROR: $entsize[newsize] < $entsize[size]\n\n\n";
		}
		echo "\nOK: $entsize[newsize] >= $entsize[size]\n\n\n";
		if (isset($argv[1]) && $argv[1]=="Regenerate") fwrite($fh,"$entity $entsize[id] $entsize[newsize]\n");
	}
	if (isset($argv[1]) && $argv[1]=="Regenerate") fclose($fh);
?>
