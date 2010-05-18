<?php 
	$domain="private";
	ini_set('include_path','../inc/:.');
	require('loginfunc.inc.php');
	require('genxml.inc.php');
	session_start();
	$type=$_GET['type'];
	$subtype=$_GET['subtype'];
	$format=$_GET['format'];
	if ($_GET['id']){
		$id=$_GET['id'];
		$whereclause=array("id","=",$id);
	}
	if ($id && ($type=="Downloads" || $type=="Viewings")){
	#	echo "<!-- $type -->";
		$cursql=setRestrict($type,$whereclause);
		$curres=mysql_query($cursql);
		$currow=mysql_fetch_assoc($curres);
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
        }
	elseif (entityExists($type,$id)){
	        $cursql=setRestrict($type,$whereclause);
	        $res=mysql_query($cursql);
		$e=1;
		$xml=pageheader();
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
		if ($subtype=="Components"){
        		$xml.=getComponents(mysql_fetch_assoc($res),$type);
	        }
		else{
		        for ($e=0; $e<mysql_num_rows($res); $e++){
       			        $xml.=printEntity(mysql_fetch_assoc($res),$type,$format);
	        	}
		}
  		$xml.=pagefooter();
		header('Content-type: application/rdf+xml');
		echo $xml;
	}
	else{
 		sc404('../');
	}
?>
