<?php

function getCodePath(){
	global $lddir;
	return $lddir."4store/code";
}
function getScriptPath(){
	global $lddir;
        return $lddir."4store/scripts";
}
function getPath(){
	global $store4execpath;
	return $store4execpath;
}
function getQueryPreamble(){
	return "export LD_LIBRARY_PATH=/usr/local/lib; export BANG='!'; "; 
}

function preProcessQuery($query){
	global $namespaces, $prefixes, $domain;
	$query=str_replace('"',"'",$query);
	$query=str_replace("\'","'",$query);
        preg_match_all("/([^ \t\n:]+):[^ \t\n:]+/",$query,$namespaces);
        preg_match_all('/PREFIX ([\w]+):[^\n]+/i',$query,$prefixes);
        $allprefixes=getUsefulPrefixesArray($domain,true);
        foreach ($namespaces[1] as $ns){
        	if (!in_array($ns,$prefixes[1])){
                	if ($allprefixes[$ns]){
                        	$query="PREFIX $ns: <".$allprefixes[$ns].">\n".$query;
                                $prefixes[1][]=$ns;
                        }
                }
        }
	$qlines=explode("\n",$query);
	foreach ($qlines as $q => $line){
		if (preg_match("/^[ \t]*BASE/",$line)){
			array_splice($qlines,$q,1);
			array_unshift($qlines,$line);
			break;
		}
	}
	$query=implode("\n",$qlines);
	return $query;
}


//Named Graph Functions
function listNamedGraphs($modelname){
	$cmd=getScriptPath()."/sqs.sh $modelname list-graphs";
	$ph=popen($cmd,"r");
	$data="";
	while ($line=fgets($ph,4096)){
		$data.=str_replace(" ","",$line);
	}
	return $data;
}
function addNamedGraph($modelname,$url){
	$cmd=getScriptPath()."/sqs.sh $modelname remove $url";
	$ph=popen($cmd,"r");
	while ($line=fgets($ph,4096)){
                $data.=str_replace(" ","",$line);
        }
	$cmd=getScriptPath()."/sqs.sh $modelname add $url";
        $ph=popen($cmd,"r");
        while ($line=fgets($ph,4096)){
                $data.=str_replace(" ","",$line);
        }
	return $data;
}
function removeNamedGraph($modelname,$url){
	$cmd=getScriptPath()."/sqs.sh $modelname remove $url";
	$ph=popen($cmd,"r");
	while ($line=fgets($ph,4096)){
                $data.=str_replace(" ","",$line);
        }
	return $data;
}
function sparqlQueryClient($kb,$query,$format="sparql",$softlimit=1000,$reasoning=0){
	global $timetaken, $errs;
	$errs=array();
        $data="";
	$oquery=$query;
	$query=str_replace(array('!',"\r","\n","\t","  "),array('${BANG}',' ',' ',' ',' '),$query);
	if ($reasoning==1 || (is_string($reasoning) && (strtolower($reasoning)=="true" || strtolower($reasoning)=="yes"))) $reason="-R CPDR";
        $cmd=getQueryPreamble().getPath()."4s-query $reason -f $format $kb \"".$query."\" -s $softlimit";
	$start=time();
        $ph=popen($cmd,'r');
	$data="";
        while (!feof($ph)) {
                $data.=fgets($ph, 4096);
        }
        pclose($ph);
	$stop=time();
	$timetaken=$stop-$start;
        if ($data){
		$rbits=explode('<!--',$data);
                unset($rbits[0]);
                foreach($rbits as $rb){
                        $rbb=explode('-->',$rb);
                        $errs[]=$rbb[0];
                }
		if (sizeof($errs)>0) $status="errors";
		else $status="succeeded";
	}
	else $status='failed';
	$sql="insert into sparql_queries values('','$kb','$oquery',$start,$stop,'$status','$data')";
	mysql_query($sql); 
        return $data;
}
function sparqlQueryClientMultiple($kb,$queries,$softlimit=1000,$timeout=30){
	global $lddir, $datapath;
	$qfp=md5(time().rand());
	$qids=array_keys($queries);
	foreach($qids as $qid){
		$filenames[$qid]="${datapath}tmp/queries/".$qfp."_$qid";
		$cmd=$lddir."4store/scripts/runquery.sh $kb \"".$queries[$qid]."\" $softlimit ".$filenames[$qid]." &";
		//echo $cmd."<br/>\n";
		exec($cmd);
	}

	$start=time();
	$check="ps aux | grep '$qfp' | grep -v 'grep' | wc -l";
	while ($start+$timeout>time()){
	//	echo "waiting";
		$ph=popen($check,'r');
		$queriesleft=fread($ph,8192);
//		echo "queriesleft: $queriesleft\n";
		pclose($ph);
		if ($queriesleft==0) break;
		sleep(1);
	}
	$results=array();	
	foreach($qids as $qid){
		if (file_exists($filenames[$qid])){
			$fh=fopen($filenames[$qid],'r');
			while(!feof($fh)){
				$data=fread($fh,8192);
				$results[$qid].=$data;
			}
			exec("rm -f ".$filenames[$qid]);
			fclose($fh);
		}
	}			
        return $results;
}

function testSparqlQueryClient($kb){
   //  $cmd=getPath()."java -cp ".getJenaClassPath()." SPARQLQueryClient $portno PING";
       //echo "##$cmd##";
   //  $ph=popen($cmd,"r");
     //$data=fgets($ph,4096);
	//echo "~~$data~~";
	return true;
       if (trim($data) == "PONG") return true;
       return false;
}

function modularizedFullTestSparqlQueryClient($kb){
        global $ontopath;
	$query="PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
select ?x where {?x rdfs:isDefinedBy <".$ontopath."snarm/>}";
        $cmd=getQueryPreamble().getPath()."4s-query $kb \"".$query."\" -s 50000 2>/dev/null";
	$ph=popen($cmd,"r");
	$data="";
        while ($line = fgets($ph, 4096)) {
                $data.=$line;
        }
	pclose($ph);
        if (strlen($data)==1184) return '1';
        return '0';
}
function ontologiesFullTestSparqlQueryClient($kb){
        global $ontopath;
        $query="PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
select ?x where {?x rdfs:isDefinedBy <".$ontopath."snarm/>}";
        $cmd=getQueryPreamble().getPath()."4s-query $kb \"".$query."\" -s 50000 2>/dev/null";
        $ph=popen($cmd,"r");
        $data="";
        while ($line = fgets($ph, 4096)) {
                $data.=$line;
        }
        pclose($ph);
        if (strlen($data)==1184) return '1';
        return '0';
}

function reasonOntology($name,$url,$id,$log){
	$cmd=getScriptPath()."/reasonRemoteOntology.sh '$url' $name $id > $log &";
	//echo $cmd."###<br/>";
        exec($cmd);
}


function cacheSpec($name,$url,$id,$log){
        $cmd=getScriptPath()."/cacheSpec.sh '$url' $name $id > $log &";
//      echo $cmd."<br/>";
	exec($cmd);
}
function reasonFiles($filelocs,$modelname,$reasonedloc){
	$listfile="/tmp/reason_file_".date();
	$fh=fopen($listfile,'w');
	if (!is_array($filelocs)) fwrite($fh,$filelocs);
	else{
		foreach ($filelocs as $fileloc) echo "$fileloc\n";
	}
	fclose($fh);
	$cmd=getScriptPath()."/sqs.sh $modelname reason-files $listfile $reasonedloc";
//      echo $cmd."<br/>";
        exec($cmd);
}
function reasonFile($fileloc,$kb,$reasonedloc){
	$cmd=getScriptPath()."/sqs.sh $kb reason-file $fileloc $reasonedloc";
//	echo $cmd;
	exec($cmd);
	$flbits=explode("/",$fileloc);
	return $reasonedloc.$flbits[sizeof($flbits)-1];
/*	$ph=popen($cmd,'r');
	while ($line = fgets($ph, 4096)) {
                echo $line."</br>";
        }
	pclose($ph);*/
}
function getNoTriples($kb){
	global $lddir;
	$lines=@file($lddir."4store/log/".$kb."_triples.log");
	if (is_array($lines) && sizeof($lines)>0 && $lines[0]>0) return $lines[0];
	$cmd=getScriptPath()."/sqs.sh $kb count-triples";
	exec($cmd);
	$lines=@file($lddir."4store/log/".$kb."_triples.log");
        if (is_array($lines)) return $lines[0];
	return array('0','');
}
function getLastUpdated($kb){
	global $lddir;
	$lines=@file($lddir."4store/log/".$kb."_update_time.log");
        return $lines[0];
}
function getVersions(){
        global $lddir;
        $lines=@file($lddir."4store/log/4storeversions.log");
        return $lines[0];
}
?>
