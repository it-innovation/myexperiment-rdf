<?php $san_pt=preg_replace("/<[^>]+>/","",$pagetitle); ?>
<html>

<head>
  <title><?php if (!$headername) echo 'myExperiment'; else echo $headername ?> <?= $san_pt; ?></title>
  <link rel="stylesheet" type="text/css" href="/css/style.css"/>
</head>
<body>

<div class="page">
 <h2><?php
	if ($headerimg && $headerimg!="none") echo "    <a href=\"$url\"><img src=\"$headerimg\" alt=\"$headername Logo\"/></a>";
	echo $pagetitle;
?></h2>
