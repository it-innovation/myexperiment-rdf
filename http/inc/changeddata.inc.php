<?php

function getChangedSinceTime($domain){
	$lines=@file("../4store/log/".$domain."_updated.log");
	if (is_array($lines)) return $lines[0];
	return '0';
}
?>
