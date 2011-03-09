<?php
	include('include.inc.php');
	$pagetitle="How To SPARQL";
	$htmlheader=array('<script src="/js/howtosparql.js" type="text/javascript"></script>');
	include('header.inc.php');
	require('./hts/nav.inc.php');
	$pages=array(1=>"Using the SPARQL Endpoint", 2=>"PREFIX", 3=>"SELECT", 4=>"WHERE", 5=>"FILTER", 6=> "GROUP BY", 7=>"ORDER BY", 8=>"LIMIT", 9=>"Troubleshooting");
	if (!$_GET['page']){
		include('hts/intro.php');
	}
	else{
		$pageno=array_search($_GET['page'],$pages);
		echo "<div style=\"text-align: center;\">\n";
		nav_form('top_nav',$pageno,$pages);
		echo "</div>\n";
		if ($pageno) include("hts/$pageno.php");	
		else echo "<h2>Page Not Found</h2>";
		echo "<div style=\"text-align: center; clear: both;\">\n";
		nav_form('bottom_nav',$pageno,$pages);
		echo "</div>\n";
		
	}	
?>
<br/>
<?php include('footer.inc.php'); ?>
