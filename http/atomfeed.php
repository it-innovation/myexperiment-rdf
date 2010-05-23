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
	if ($_GET['limit']) $limit= "limit $_GET[limit]"; 
//	echo "<!-- $type - $id -->\n";
	if ($type=="Experiment" && isset($id)) $fsql = $sql['Job']." and experiment_id=$id order by updated_at desc $limit";
	else $fsql=$sql[$type]." order by updated_at desc $limit";
	
	if (isset($_SESSION['userid'])) $userid=$_SESSION['userid'];
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
	$fsql=str_replace(array('?','~'),array($userid,$ingroups),$fsql);
//echo $fsql;
	$fres=mysql_query($fsql);
	if ($type=="Experiment" && isset($id) && mysql_num_rows($fres)==0){
		$privsql=getPrivateSQL();
		$tsql=$privsql['Job']." where experiment_id=$id order by updated_at desc $limit";
//		echo $tsql;
		$tres=mysql_query($tsql);
		if (mysql_num_rows($tres)>0){
			sc401();
			die();
		}
	}
	
	if (mysql_num_rows($fres)>0) $lastup=strtotime(mysql_result($fres,0,'updated_at'));	
	if ($lastup>$published) $updated=date('Y-m-d\TH:i:s\Z',$lastup);
	else $updated=date('Y-m-d\TH:i:s\Z',$published);
	header('Content-type: application/atom+xml');
	echo '<?xml version="1.0" encoding="utf-8"?>';
?>

<!DOCTYPE feed [ <!ENTITY mebase '<?= $ontopath ?>base/'>
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

<feed xmlns="http://www.w3.org/2005/Atom">
 
 <title>myExperiment Atom Feed for <?=$_GET['type']?> <?=$id?></title>
 <link href="<?=$datauri."AtomFeed/".$_GET['type']?><? if ($id) echo "/$id"; ?>" rel="self"/>
 <link href="http://www.myexperiment.org/"/>
 <updated><?= $updated ?></updated>
 <author>
   <name>myExperiment</name>
   <email>enquiries@myexperiment.org</email>
 </author>
 <id><?=$datauri."AtomFeed/".$_GET['type'] ?></id>


<?php
	for ($e=0; $e<mysql_num_rows($fres); $e++){
		getAtomEntry($etype,mysql_result($fres,$e,'id'),$userid,$ingroups);
	}
?>	
</feed>
