<?php 
	$san_pt=preg_replace("/<[^>]+>/","",$pagetitle);
	if (!$ignoreloc){
		if (preg_match('/\/rdf\//',$_SERVER['REQUEST_URI'])) $hpath="/rdf";
		elseif (preg_match('/\/rdfdev\//',$_SERVER['REQUEST_URI'])) $hpath="/rdfdev";
		elseif (preg_match('/\/linkeddata\//',$_SERVER['REQUEST_URI'])) $hpath="/linkeddata";
	}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en"> 

<head>
  <title><?php if (!$headername) echo 'myExperiment'; else echo $headername ?> <?= $san_pt; ?></title>
  <link rel="stylesheet" type="text/css" href="<?=$hpath?>/css/style.css"/>
  <link rel="icon" href="<?=$hpath?>/img/favicon.ico" type="image/x-icon" />
  <meta http-equiv="Content-type" content="text/html;charset=UTF-8" />
<?php
	if (is_array($htmlheader)){
		foreach ($htmlheader as $line) echo "  $line\n";
	}
?>
</head>
<body>

<div class="page">
 <div style="float: right;"><a href="http://www.myexperiment.org/feedback?subject=RDF%20API">Submit Feedback/Bug Report</a></div> <h1>
<?php
if ($headerimg){
	if ($headerimg!="none") echo "    <img src=\"$headerimg\" alt=\"$headername Logo\"/>";
	echo $pagetitle;
}
else{
	echo "<a href=\"/\"><img src=\"$hpath/img/logo.png\" alt=\"myExperiment Logo\"/></a>&nbsp;$pagetitle";
}

?>
  </h1>
