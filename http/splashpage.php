<?php 
$domain="protected";
ini_set('include_path','inc/');
include('genxml.inc.php');
include('loginfunc.inc.php');
session_start();
if (entityExists($_GET['type'],$_GET['id'])){	
	if (entityViewable($_GET['type'],$_GET['id'])) $userid='0';
	elseif (isset($_SESSION['userid'])) $userid=$_SESSION['userid'];
        elseif ($_SERVER['PHP_AUTH_USER']){
        	if ($_SESSION['authuser']==$_SERVER['PHP_AUTH_USER'] && isset($_SESSION['authuser'])){
                	unset($_SESSION['authuser']);
                        sc401();
                        die();
                }
                else{
                	$res=whoami($_SERVER['PHP_AUTH_USER'],$_SERVER['PHP_AUTH_PW']);
                        $userid=$res[0];
			//echo "#userid~$userid#";
                        $_SESSION['authuser']=$_SERVER['PHP_AUTH_USER'];
                }
        }
        else $userid='0';
	$pagetitle="Splash Page for $_GET[type] $_GET[id]";
	$htmlheader[]='<link rel="resourcemap" href="'.$datauri.'ResourceMap/'.$_GET['type'].'/'.$_GET['id'].'" type="application/rdf+xml" />';
	$htmlheader[]='<link rel="resourcemap" href="'.$datauri.'AtomEntry/'.$_GET['type'].'/'.$_GET['id'].'" type="application/atom+xml" />';
	if (entityViewable($_GET['type'],$_GET['id'],$userid,userInGroups($userid))){	
		include('header.inc.php');
			
?>
  <div class="hr"></div>
<?php
	  	if ($_SESSION['redirect']){
			unset($_SESSION['redirect']);
			print_message("You have been redirected from ".$datauri."Aggregation/$_GET[type]/$_GET[id] to this splash page because your primary accept type is text/html.  Please select one of the formats of resource map from the list below.");
		}
?>
  <ul>
    <li><a href="<?=$datauri."ResourceMap/$_GET[type]/$_GET[id]"?>" type="application/rdf+xml">RDF Resource Map</a></li>
    <li><a href="<?=$datauri."AtomEntry/$_GET[type]/$_GET[id]"?>" type="application/atom+xml">Atom Entry</a></li>
  </ul>

<?php  		include('footer.inc.php');
	}
	else sc401();
}
else sc404();
?>
