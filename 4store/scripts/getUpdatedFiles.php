#!/usr/bin/php
<?php
	
	$lines=@file($lddir.'4store/log/'.$argv[1].'_update_time.log');
	$lastupdated=$lines[0];
	$filetimes=@file("php://stdin");
	foreach ($filetimes as $filetime){
		$ftbits=explode(" ",$filetime);
		if (strpos($ftbits[2],":")>0){
			$time=strtotime("$ftbits[2] $ftbits[1] $ftbits[0]");
		}
		else{
			$time=strtotime("$ftbits[1] $ftbits[0] $ftbits[2]");
		}
		if ($time>=$lastupdated) echo $ftbits[4]."\n";
	}
 
?>
	

