<?php
	$url=str_replace("http://www.myexperiment.org/","",$_GET['url']);
	$url=str_replace(".rdf","",$url);
	$urlbits=explode("/",$url);
	$type=array_shift($urlbits);
	$id=array_shift($urlbits);
	$params=implode('/',$urlbits);
	$data="";
	if ($type){
		$cmd="/usr/bin/php ../rdfgen/rdfgencli.php $type $id $params";
		$ph=popen($cmd,'r');
		if ($ph !== false) {
	      	  	while (!feof($ph)) {
         		       	$data.=fgets($ph, 512); 
  	      		}
			fclose($ph);
		}
	}
	if ($data){
		header('Content-Type: application/rdf+xml; charset=utf-8');
		echo $data;
	}
	else{
		 header("HTTP/1.1 404 Not Found");
	}
	
?>

