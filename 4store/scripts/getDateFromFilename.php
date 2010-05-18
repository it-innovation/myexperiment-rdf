#!/usr/bin/php
<?php
	
	$lines=@file('/var/data/log/'.$argv[1].'_update_time.log');
	$lastupdated=$lines[0];
	$filetimes=@file("php://stdin");
	foreach ($filetimes as $filetime){
		$ftbits=explode(" ",$filetime);
		if (strpos($ftbits[2],":")>0){
			$time=strtotime("$ftbits[2] $ftbits[1] $ftbits[0]");
			echo "$filetime = $time\n";
		}
		else{
			$time=strtotime("$ftbits[1] $ftbits[0] $ftbits[2]");
                        echo "$filetime = $time\n";
		}
	}
 
?>
	

