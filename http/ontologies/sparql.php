<?php
	ini_set('include_path','../inc/:.');
	$pagetitle="<small>Ontologies SPARQL Endpoint</small>";
	$htmlheader[]='<script src="/js/sparql.js" type="text/javascript"></script>';
	include('xmlfunc.inc.php');
        require('4storefunc.inc.php');
	require('miscfunc.inc.php');
	$domain="ontologies";
	$prefix="Ontologies ";
	$ts="ontologies";
        $notriples=getNoTriples($ts);
	$ludate=getLastUpdated($ts);
	$softlimit=1;
        $maxsoftlimit=100;
	$clientlive=true;
	if ($_POST){
		$query = str_replace('"',"'",$_POST['query']);
		$formatting = mysql_real_escape_string($_POST['formatting']);	
		if (is_int(intval($_POST['softlimit'])) && intval($_POST['softlimit'])>0 && intval($_POST['softlimit'])<=$maxsoftlimit) $softlimit=intval($_POST['softlimit']);
	}
	elseif ($_GET){		
		$query = str_replace('"',"'",rawurldecode($_GET['query']));
		$accepts=explode(",",$_SERVER['HTTP_ACCEPT']);
		if (is_int(intval($_GET['softlimit'])) && intval($_GET['softlimit'])>0 && intval($_GET['softlimit'])<=$maxsoftlimit) $softlimit=intval($_GET['softlimit']);
                if ($_GET['formatting']) $formatting=$_GET['formatting'];
		elseif (array_in_array(array("text/html","application/xhtml"),$accepts)) $formatting="";
                elseif (array_in_array(array("text/xml","application/xml","application/rdf+xml","application/sparql-results+xml"),$accepts)) $formatting="Raw";
                else $formatting="Raw";
	}
	if ($query) {
		if (strtolower($query)=="test"){
			$formatting = "";
			if (ontologiesFullTestSparqlQueryClient($useport)){
				$msg="This myExperiment SPARQL Endpoint is functioning correctly";
				$clientlive=true;
			}
			else{
				$err="This myExperiment SPARQL Endpoint needs restarting";
				$clientlive=false;
			}
		}
		else{
			$results=sparqlQueryClient($ts,$query,"sparql",$softlimit*1000);
			if ($results) $clientlive=true;
			if (substr($results,0,12)=="Query Failed"){
				$err=$results;
				$results="";
			}	
		}
	}
	$formattings=array("","Raw","HTML Table","CSV","CSV Matrix","JSON");
	$formattinglabels=array("XML (In Page)","XML (Raw)","HTML Table","CSV","CSV Matrix","JSON");
	if ($formatting=="Raw"){
		header("Content-type: application/xml");
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
    <div style="background-color: thistle; border: 2px solid indigo; padding: 10px;">
      <h3 style="margin: 0;">Useful Prefixes</h3>
      <?=getUsefulPrefixes($prefix)?>
    </div>
    <br/>
    <div style="background-color: #FFFFCC; border: 2px solid goldenrod; padding: 10px;">
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
        <tr>
          <th style="text-align: right;">Soft Limit:</th>
          <td><input type="text" maxlength="3" size="3" name="softlimit" value="<?=$softlimit?>" /> %</td>
        </tr>
      </table>
      <?php if ($err) echo "<br/><div class=\"red\"><b>$err</b></div>\n"; ?>
      <?php if ($msg) echo "<br/><div class=\"green\"><b>$msg</b></div>\n"; ?>
      <p>
        <textarea name="query" id="querybox" cols="110" rows="12"><?= $query ?></textarea>
      </p>
      <p>
        <input type="submit">
      </p>
    </form>
    </div>
  </div>
  <br/>
    <?php 
	if ($results){
		echo "<div style=\"background-color: #FFFFFF; border: 2px solid black; padding: 10px;\">\n";
		echo "<h3 style=\"margin: 0;\">Results</h3>";
		if (!$formatting) echo "<pre>".htmlentities($results)."</pre>";
		elseif ($formatting=="HTML Table"){
			$parsedxml=parseXML($results);
			$tabres=tabulateSparqlResults($parsedxml);
			$formattedoutput=drawSparqlResultsTable($tabres);
			$nores=sizeof($tabres)-1;
			echo "<p><b>No. of Results:</b> $nores</p>\n";
			echo $formattedoutput;
		}
		echo "</div>\n<br/>\n";
	}
    ?>
<?php 
		include('footer.inc.php');
	}
	elseif (!$done){
		include('header.inc.php');
?>	
    <div align="center">
      <div class="red">
        <b><?= $err ?></b>
      </div>
    </div>
<?php 
		include('footer.inc.php');
	} 
?> 
