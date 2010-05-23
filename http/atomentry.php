<?php
	$domain="protected";
	include('include.inc.php');
	include('genrdf.inc.php');
	include('atomfunc.inc.php');
	session_start();
	if ($_GET['id']){
		$type=$_GET['type'];
		$id=$_GET['id'];
		if ($type=="Experiment") $etype="Job";
		else $etype=$type;
	}
	else{
		$type=$groups[$_GET['type']];
		$etype=$type;
		if (!in_array($type,$aggregateclasses)){
			sc404();
	//		echo "<p>$type</p>";
			die();
		}
	}
	$published=1238670631;
//	echo "<!-- $type - $id -->\n";
	if (entityExists($type,$id)){
		if (entityViewable($type,$id))$userid=0;
		elseif (isset($_SESSION['userid'])) $userid=$_SESSION['userid'];
		elseif ($_SERVER['PHP_AUTH_USER']){
			if ($_SESSION['authuser']==$_SERVER['PHP_AUTH_USER']){
        	        	$_SESSION['authuser']="";
                	        sc401();
				die();
        	        }
			else{
				$res=whoami($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW']);
        		        $userid=$res[0];
				$_SESSION['authuser']=$_SERVER['PHP_AUTH_USER'];
			}
		}
		else $userid=0;
		$ingroups=userInGroups($userid);
		$esql = setRestrict($type,array('id','=',$id),$userid,$ingroups);
		$eres=mysql_query($esql);
		header('Content-type: text/xml');
		echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
?>
<!DOCTYPE atom:entry [
 <!ENTITY mebase '<?= $ontopath ?>base/'>
 <!ENTITY meac '<?= $ontopath ?>attrib_credit/'>
 <!ENTITY meannot '<?= $ontopath ?>annotations/'>
 <!ENTITY mepack '<?= $ontopath ?>packs/'>
 <!ENTITY meexp '<?= $ontopath ?>experiments/'>
 <!ENTITY mecontrib '<?= $ontopath ?>contributions/'>
 <!ENTITY mevd '<?= $ontopath ?>viewings_downloads/'>
 <!ENTITY mecomp '<?= $ontopath ?>components/'>
 <!ENTITY mespec '<?= $ontopath ?>specific/'>
 <!ENTITY snarm '<?= $ontopath ?>snarm/'>
 <!ENTITY xsd 'http://www.w3.org/2001/XMLSchema#'>
]>
<?php

		getAtomEntry($etype,mysql_result($eres,0,'id'),$userid,$ingroups);
	}
	else sc404();
?>
