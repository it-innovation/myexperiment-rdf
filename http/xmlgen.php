<?php 
	$domain="protected";
	ini_set('include_path','inc/:.');
	require('loginfunc.inc.php');
	require('genxml.inc.php');
	session_start();
	$type=$_GET['type'];
	if ($_GET['id']) $id=$_GET['id'];
	//var_dump($_GET);
	$params=explode("/",$_GET['params']);
	if ($params[0]=="versions" && $type="workflows"){
		if (!$params[1]){
			sc404();
			die();
		}
		$type="workflow_versions"; 
		$wvsql="select id from workflow_versions where workflow_id=$id and version=$params[1]";
		$wvres=mysql_query($wvsql);
		$wfid=$id;
		$id=mysql_result($wvres,0,'id');
	}
	elseif ($params[0]=="announcements" && $type=="groups"){
		$type="group_announcements";
		$id=$params[1];
	}
	if ($id) $whereclause=array("id","=",$id);
	$format=$_GET['format'];
	if (entityExists($type,$id)){
		if (entityViewable($type,$id)) $userid='0';
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
	        else $userid='0';
	        $gsql="select network_id from memberships where user_id=$userid and user_established_at is not null and network_established_at is not null";
		$ingroups='0,';
	        $gres=mysql_query($gsql);
	        for ($g=0; $g<mysql_num_rows($gres); $g++){
        	        $ingroups.=mysql_result($gres,$g,'network_id').",";
	        }
	        $ingroups=substr($ingroups,0,-1);

	        $cursql=setRestrict($type,$whereclause,$userid,$ingroups);
	        $res=mysql_query($cursql);
		$e=1;
		$xml=pageheader();
//		$xml.="<!-- ".$cursql." -->\n";
		if ($format=="ore"){
			$xml.=getResourceMapRDF($type,$id);
                }
		if ($params[2]=="dataflows"||$params[2]=="dataflow"){
			array_shift($params);
			$version=array_shift($params);
        		$xml.=extractRDF($id,$wfid,$version,$params);
	        }
		else{
		        for ($e=0; $e<mysql_num_rows($res); $e++){
       			        $xml.=printEntity(mysql_fetch_assoc($res),$type,$format);
	        	}
		}
  		$xml.=pagefooter();
		if ($e>0 || !$id){
			header('Content-type: application/rdf+xml');
			echo $xml;
		}
		else{
			sc401();
		}
	}
	else{
 		sc404();
	}
?>
