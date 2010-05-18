<?php
	ini_set('include_path','../inc/:.');
	include('xmlfunc.inc.php');
        require('4storefunc.inc.php');
	require('miscfunc.inc.php');
	$domain="private";
	$ts=getTriplestoreForDomain($domain);
        $lines=getNoTriples($ts);
        $useport=getPortForTriplestore($ts);
	$prefix="Private ";
	$pagetitle="<small>".$prefix."SPARQL Endpoint</small>";
	$htmlheader[]='<script src="/js/sparql.js" type="text/javascript"></script>';
	$notriples=$lines[0];
	$lmdate=$lines[1];
    	$format = "sparql";
        $mimetype="application/xml";
	$clientlive=testSparqlQueryClient($useport);
        if (!$clientlive) $err="This myExperiment SPARQL Endpoint is currently unavailable";

if ($clientlive){
	if ($_POST){
		$query = str_replace('"',"'",$_POST['query']);
		$formatting = mysql_real_escape_string($_POST['formatting']);	
	}
	elseif ($_GET){		
		$query = str_replace('"',"'",rawurldecode($_GET['query']));
		$accepts=explode(",",$_SERVER['HTTP_ACCEPT']);
//              if (array_in_array(array("text/html","application/xhtml"),$accepts)) $formatting="";
                if (array_in_array(array("text/xml","application/xml","application/rdf+xml","application/sparql-results+xml"),$accepts)) $formatting="Raw";
		elseif (in_array("application/json",$accepts)) $formatting="JSON";
                elseif (in_array("text/csv",$accepts)) $formatting="CSV";
                else $formatting="Raw";
	}
	$qbits=preg_split('/[ \n\t]/',$query);
	$lastqbits=array();
        $type="ASK";
        foreach ($qbits as $qbit){
// print_r($lastqbits);
                if (preg_match("/^\?/",$qbit)){
                        foreach ($lastqbits as $lastqbit){
                                if (in_array(strtoupper($lastqbit),array("SELECT","ASK","CONSTRUCT","DESCRIBE"))){
                                        $type=trim(strtoupper($lastqbit));
                                        $typefound=true;
                                }
                        }
                }
                if ($typefound) break;
                array_unshift($lastqbits,$qbit);
        }
	if ($formatting=="JSON"){
		$format="json";
		$mimetype="application/json";
		$formatting="Raw";
	}	
	if ($query) {
		if (strtolower($query)=="test"){
			$formatting = "";
			if (modularizedFullTestSparqlQueryClient($useport)){
				$msg="This myExperiment SPARQL Endpoint is functioning correctly";
				$clientlive=true;
			}
			else{
				$err="This myExperiment SPARQL Endpoint needs restarting";
				$clientlive=false;
			}
		}
		else{
			if (in_array($type,array("SELECT","ASK","CONSTRUCT","DESCRIBE"))){
		                $results=sparqlQueryClient($useport,$query,$type,$format);
				if ($results) $clientlive=true;
				if (substr($results,0,12)=="Query Failed"){
					$err=$results;
					$results="";
				}
			}
			elseif ($clientlive=testSparqlQueryClient($useport)){
				$err="ERROR: Could not determine type of query";
				$results="";
			}
			else{
				$err="This myExperiment SPARQL Endpoint is currently unavailable";
				$results="";
			}
		}
	}
        else{
           $clientlive=testSparqlQueryClient($useport);
	   if (!$clientlive) $err="This myExperiment SPARQL Endpoint is currently unavailable";
        }
}
	$formattings=array("","Raw","HTML Table","CSV","CSV Matrix","JSON");
	$formattinglabels=array("XML (In Page)","XML (Raw)","HTML Table","CSV","CSV Matrix","JSON");
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
		if (substr($csvmatrix,0,5)=="ERROR"){
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
      <h3>Useful Prefixes</h3>
      <?=getUsefulPrefixes($prefix)?>
    </div>
    <br/>
    <div class="yellow">
    <form action="" method="post">
      <h3 style="margin: 0;">Querying</h3>
       <table style="font-size: 10pt;">
          <tr>
            <th style="text-align: right;">No. of Triples:</th>
            <td><?= $notriples ?></td>
          </tr>
          <tr>
            <th style="text-align: right;">Last Updated:</th>
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
      </table>
      <?php if ($err) echo "<br/><div class=\"red\"><b>$err</b></div>\n"; ?>
      <?php if ($msg) echo "<br/><div class=\"green\"><b>$msg</b></div>\n"; ?>
      <p>
        <textarea name="query" id="querybox" cols="110" rows="12"><?= $_POST['query'] ?></textarea>
      </p>
      <p>
        <input type="submit"/>
      </p>
    </form>
    </div>
  </div>
  <br/>
    <?php 
	if ($results){
		echo "<div class=\"results\">\n";
		echo "<h3>Results</h3>";
		if (!$formatting) echo "<pre>".htmlentities($results)."</pre>";
		elseif ($formatting=="HTML Table"){
			$parsedxml=parseXML($results);
			$tabres=tabulateSparqlResults($parsedxml);
			$formattedoutput=drawSparqlResultsTable($tabres);
			$nores=sizeof($tabres)-1;
			echo "<p><b>No. of Results:</b> $nores</p>\n";
			echo $formattedoutput;
		}
		echo "</div>\n";
	}
	include('footer.inc.php');
    ?>
<?php }elseif (!$done){ 
	include('header.inc.php');
	print_error($err);
	include('footer.inc.php');
} ?> 
