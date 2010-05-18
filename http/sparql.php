<?php
	$pagetitle="SPARQL Endpoint";
	$htmlheader[]='<script src="/js/sparql.js" type="text/javascript"></script>';
	ini_set('include_path','inc/:.');
	include('xmlfunc.inc.php');
	include('sq_connect.inc.php');
        require('4storefunc.inc.php');
	require('miscfunc.inc.php');
	$domain="public";
	$ts=getTriplestoreForDomain($domain);
	$notriples=getNoTriples($ts);
	$prefix="";
	$lmdate=date('r',strtotime(date('Y-m-d',getLastUpdated($ts)))-60);
    	$format = "sparql";
        $mimetype="application/xml";
	$softlimit=1;
	$maxsoftlimit=100;
	$formatting="In Page";
	$clientlive=testSparqlQueryClient($ts);
        if (!$clientlive) $err="This myExperiment SPARQL Endpoint is currently unavailable";
else{
	if ($_POST){
		$query = str_replace('"',"'",$_POST['query']);
		$formatting = $_POST['formatting'];
		if (is_int(intval($_POST['softlimit'])) && intval($_POST['softlimit'])>0 && intval($_POST['softlimit'])<=$maxsoftlimit) $softlimit=intval($_POST['softlimit']);	
	}
	elseif ($_GET){		
		$formatting="";
		$query = str_replace('"',"'",rawurldecode($_GET['query']));
		if ($query=="test"){
			$query="PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX mecontrib: <http://rdf.myexperiment.org/ontologies/contributions/>
PREFIX mebase: <http://rdf.myexperiment.org/ontologies/base/>
PREFIX dcterms: <http://purl.org/dc/terms/>
select ?workflow ?ct_title { ?workflow rdf:type mecontrib:Workflow . ?workflow mebase:has-content-type ?ct . ?ct dcterms:title ?ct_title . FILTER(REGEX(?ct_title,'trident','i'))}";
			if ($_POST['format']) $formatting = mysql_real_escape_string($_POST['formatting']);
			elseif ($_GET['format']) $formatting = mysql_real_escape_string($_GET['formatting']); 
			else $formatting="In Page";
		}
		$accepts=explode(",",$_SERVER['HTTP_ACCEPT']);
		if (is_int(intval($_GET['softlimit'])) && intval($_GET['softlimit'])>0 && intval($_GET['softlimit'])<=$maxsoftlimit) $softlimit=intval($_GET['softlimit']);
                //if (array_in_array(array("text/html","application/xhtml"),$accepts)) $formatting="";
		if ($_GET['formatting']) $formatting=$_GET['formatting'];
                elseif (array_in_array(array("text/xml","application/xml","application/rdf+xml","application/sparql-results+xml"),$accepts)) $formatting="Raw";
                elseif (in_array("text/csv",$accepts)) $formatting="CSV";
                elseif (!$formatting) $formatting="Raw";
	}
	if ($query) {
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
		$results=sparqlQueryClient($ts,$query,$softlimit*10000);
		$err=implode('<br/>',$errs);
	}
        }
	$formattings=array("In Page","Raw","HTML Table","CSV","CSV Matrix");
	$formattinglabels=array("XML (In Page)","XML (Raw)","HTML Table","CSV","CSV Matrix");
	if ($formatting=="Raw"){
		header("Content-type: $mimetype");
		echo $results;
		$done=1;
	}
	elseif ($formatting=="CSV"){
		header('Content-type: text/csv');
                echo convertTableToCSV(tabulateSPARQLResults(parseXML($results)));
		$done=1;
	}
	elseif ($formatting=="CSV Matrix"){
		$csvmatrix=convertTableToCSVMatrix(tabulateSPARQLResults(parseXML($results)));
		if ($err){
			$results=null;
		}
		elseif (substr($csvmatrix,0,5)=="ERROR"){
			$err=$csvmatrix;
			$results=null;
		}
		else{
			header('Content-type: text/csv');
			echo $csvmatrix;
			$done=1;
		}
	}
	if($clientlive && !$done){
		include('header.inc.php');
?>
    <div align="center"> 
    <div class="purple">
      <h3 style="margin: 0;">Useful Prefixes</h3>
      <?=getUsefulPrefixes($domain)?>
    </div>
    <br/>
    <div class="yellow">
      <p style="text-align: right;"><small>New to SPARQL? Click <a href="howtosparql">here for help</a></small></p>
    <form name="queryform" method="post" action="">
      <h3 style="margin: 0;">Querying</h3>
       <p style="padding: 10px 100px;"><small>From time to time modifications are made to the <a href="ontologies/">myExperiment Ontology</a> and therefore the RDF queried by this SPARQL endpoint.  Please check the <a href="ontologies/CHANGELOG">CHANGELOG</a> if your query has ceased to function.  <b>The recent reduction of triples is due to anonymised usage statistics being removed. See the <a href="ontologies/CHANGELOG">CHANGELOG</a>  for more details.</b>
       <table style="font-size: 10pt;">
          <tr>
            <th style="text-align: right;">No. of Triples:</th>
            <td><?= $notriples ?></td>
          </tr>
          <tr>
            <th style="text-align: right;">Last Snapshot Taken At:</th>
            <td><?= $lmdate ?></td>
          </tr>
          <tr>
            <th style="text-align: right;">Format:</th>
            <td>
              <select name="formatting">
          <?php
        for ($f=0; $f<sizeof($formattings); $f++){
                echo "            <option ";
                if ($formattings[$f]==$formatting) echo 'selected="selected" ';
                echo "value=\"".$formattings[$f]."\">".$formattinglabels[$f]."</option>\n";
        }
?>
              </select>
           </td>
        </tr>
        <tr>
          <th style="text-align: right;">Soft Limit:</th>
          <td><input type="text" size="3" maxlength="3" name="softlimit" value="<?=$softlimit?>" />%</td>
        </tr>
      </table>
      <?php if ($err) echo "<br/><div class=\"red\"><b>$err</b></div>\n"; ?>
      <?php if ($msg) echo "<br/><div class=\"green\"><b>$msg</b></div>\n"; ?>
      <p>
        <textarea name="query" id="querybox" cols="110" rows="12" style="width: 800px;"><?= htmlentities($query) ?></textarea>
      </p>
      <p>
        <input type="submit" name="submit_query" value ="Submit Query"/>
      </p>
    </form>
 </div>
</div>

  <br/>
    <?php 
	if ($results){
		echo "<div class=\"results\">\n";
		echo "<h3>Results</h3>";
		if ($timetaken==0) $timetaken="<1";
		echo "<p><b>Time Taken:</b> $timetaken seconds</p>\n";
		if ($formatting=="In Page") echo "<pre style=\"white-space: pre-wrap;\">".htmlentities($results)."</pre>";
		elseif ($formatting=="HTML Table"){
			$parsedxml=parseXML($results);
			$tabres=tabulateSparqlResults($parsedxml);
			$formattedoutput=drawSparqlResultsTable($tabres);
			$nores=sizeof($tabres)-1;
			echo "<p><b>No. of Results:</b> $nores</p>\n";
			echo $formattedoutput;
		}
		echo "</div>\n<br/><br/>\n";
	}
    ?>
<?php 
include('footer.inc.php');
}
elseif (!$done){ 
	include_once('header.inc.php');
        print_error($err);
	include('footer.inc.php');
} 
?> 
