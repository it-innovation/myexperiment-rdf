<?php
        include('include.inc.php');
        require('functions.inc.php');
	$spo=array('subject'=>$argv[1],'predicate'=>$argv[2],'object'=>$argv[3]);
	echo getRelationshipURN($spo);
?>

