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
        if ($id && ($type=="downloads" || $type=="viewings")){
                $cursql=setRestrict($type,$whereclause);
                $curres=mysql_query($cursql);
                $currow=mysql_fetch_assoc($curres);
		//echo $cursql;
		//print_r($currow);
                $whereclause=array();
                array_push($whereclause,'contribution_id','=',$currow['contribution_id']);
                if ($currow['user_id']) array_push($whereclause,'user_id','=',$currow['user_id']);
                else array_push($whereclause,'user_id','is','NULL');
                if ($currow['user_agent']) array_push($whereclause,'user_agent','=',$currow['user_agent']);
                else array_push($whereclause,'user_agent','is','NULL');
                array_push($whereclause,'accessed_from_site','=',$currow['accessed_from_site']);
        }
	if (in_array($type,$specialtypes)){
		$xml="";
		$ts=getTriplestoreForDomain($domain);
		//echo $ts;
		if ($id){
			$filepath=$datapath.getTriplestoreForDomain($domain)."/$type/$id";
			$lines=file($filepath);
		 	foreach ($lines as $line){
               	        	$xml.=$line;
	                }
		}
		else{
			$cmd="ls $datapath".getTriplestoreForDomain($domain)."/$type/*";
			$ph=popen("ls $datapath".getTriplestoreForDomain($domain)."/$type/*","r");
			$ls="";
			while (!feof($ph)){
				$ls.=fread($ph,8192);
			}
			fclose($ph);
			$files=explode("\n",$ls);
			$xml=privatepageheader();
			foreach ($files as $file){
				if ($file){
					$lines=file($file);
					$show=0;
					foreach ($lines as $line){
						if (strpos($line,":".$type)) $show=1;
						if (strpos($line,"rdf:RDF")) $show=0;
						if ($show) $xml.=$line;
					}
				}
			}
			$xml.=pagefooter();
		}		
                header('Content-type: application/rdf+xml');
                echo $xml;
		echo "<!-- $ls -->";
        }
	elseif (entityExists($type,$id)){
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
                                $curtime=date("Y-m-d\TH:i:s\Z");
                                $xml.="  <rdf:Description rdf:about=\"".$datauri."ResourceMap/$type/$id\">
    <ore:describes rdf:resource=\"".$datauri."$type/$id\"/>
    <dcterms:creator rdf:parseType=\"Resource\">
      <foaf:name>myExperiment Mothership RDF Generator</foaf:name>
      <foaf:page rdf:resource=\"$datauri\" />
    </dcterms:creator>
    <dcterms:created rdf:datatype=\"&xsd;dateTime\">$curtime</dcterms:created>
    <dcterms:modified rdf:datatype=\"&xsd;dateTime\">$curtime</dcterms:modified>
    <dc:rights>This Resource Map is available under the Creative Commons Attribution-Noncommercial 2.5 Generic license</dc:rights>
    <dcterms:rights rdf:resource=\"http://creativecommons.org/licenses/by-nc/2.5/\"/>
  </rdf:Description>\n\n";
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
