<?php
$pagetitle="SPARQL Endpoint";
$htmlheader[]='<link rel="stylesheet" type="text/css" href="/css/style.css"/>';
$htmlheader[]='<script src="/js/sparql.js" type="text/javascript"></script>';
$htmlheader[]='<script src="/js/codemirror.js" type="text/javascript"></script>';
include('include.inc.php');
include('xmlfunc.inc.php');
include('sparqlconnect.inc.php');
require('4storefunc.inc.php');
require('miscfunc.inc.php');
$domain="public";
$ts=$triplestore;
$notriples=getNoTriples($ts);
$prefix="";
$lmdate=date('r',strtotime(date('Y-m-d',getLastUpdated($ts)))-60);
$softlimit=1;
$maxsoftlimit=100;
$formatting="XML";
$mimetype="application/xml";
$format="sparql";

$formats=array("HTML Table"=>array("sparql",array("text/html","application/xhtml+xml")), "XML"=>array("sparql",array("application/xml","applications/sparql-results+xml")),"Text"=>array("text",array("text/plain")),"JSON"=>array("json",array("application/json")),"CSV"=>array("sparql",array("text/csv","application/csv")),"CSV Matrix"=>array("sparql",array("text/csv","application/csv")));
if (isset($_POST['formatting'])) $formatting=$_POST['formatting'];
elseif (isset($_GET['formatting']) && strlen($_GET['formatting'])>0) $formatting=$_GET['formatting'];
else{
	$mtfound=0;
	$fc_mimetype=get_first_choice_mimetype($_SERVER['HTTP_ACCEPT']);
	foreach ($formats as $fname => $aformat){
		foreach ($aformat[1] as $amimetype){
			if ($fc_mimetype==$amimetype){
				$formatting=$fname;
				$mtfound=1;
				break;
			}
		}
		if ($mtfound) break;
	}
}
if ($formatting!="XML"){
	$format=$formats[$formatting][0];
	$mimetype=$formats[$formatting][1][0];
}
$clientlive=testSparqlQueryClient($ts);
if (isset($_POST['generate_service'])){
	if (isset($_POST['query']) and strlen($_POST['query'])>0){
		$pagetitle="SPARQL Query Service";
	        include('header.inc.php');
		if (strlen($formatting)>0 && $formatting!="HTML Table") $formatparam="&formatting=".$formatting;
		
		$query=urlencode(preProcessQuery($_POST['query']));
		$service_url="http://".$_SERVER['SERVER_NAME']."/sparql?query=$query$formatparam";
		echo "<p>Below is a URL which you can use give to any application capable of making HTTP requests and it will return you the current results for the query you made.</p>";
		if ($formatting=="HTML Table"){
			echo "<div class=\"red\"><b>WARNING:</b> This service require the HTTP request to explictly specify its accept type in the request header.  if this is not set appropriately the format returned will most likely be HTML with an embedded table of results. To select a particular format, click back and select it from the list provided before clicking &quot;Generate Service for Query&quot; again.</div><br/>\n";
		}
		echo "<p style=\"margin: 0 30px\"><a href=\"$service_url\">$service_url</a></p>";
		include('footer.inc.php');
	       exit(1);
	}
	else{
		$err="No Query was Submitted";
	}
}
if (!$clientlive) $err="This myExperiment SPARQL Endpoint is currently unavailable";
else{
	if ($_POST){
		$query = $_POST['query'];
		if (is_int(intval($_POST['softlimit'])) && intval($_POST['softlimit'])>0 && intval($_POST['softlimit'])<=$maxsoftlimit) $softlimit=intval($_POST['softlimit']);	
	}
	elseif ($_GET){		
		$query = rawurldecode($_GET['query']);
		if (is_int(intval($_GET['softlimit'])) && intval($_GET['softlimit'])>0 && intval($_GET['softlimit'])<=$maxsoftlimit) $softlimit=intval($_GET['softlimit']);
	}
	if ($query) {	
		$query=preProcessQuery($query);
		$results=sparqlQueryClient($ts,$query,$format,$softlimit*10000);
		$err=implode('<br/>',$errs);
	}
}
if ($formatting!="HTML Table"){
	$done=1;
	if ($formatting=="CSV") $results=convertTableToCSV(tabulateSPARQLResults(parseXML($results)));
	elseif ($formatting=="CSV Matrix"){
		$csvmatrix=convertTableToCSVMatrix(tabulateSPARQLResults(parseXML($results)));
		if ($err){
                	$results=null;
			$done=0;
	        }
        	elseif (substr($csvmatrix,0,5)=="ERROR"){
                	$err=$csvmatrix;
	                $results=null;
			$done=0;
	        }
		else{
	                $results=$csvmatrix;
	        }
	}
	if ($done){
		header("Content-type: $mimetype");
		echo $results;
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
      <h3 style="text-align: center; margin: 0; margin-bottom: 10px;">Querying</h3>
      <p style="text-align: right; position: relative; top: -38px;"><small><a href="/howtosparql" title="How to SPARQL in myExperiment Guide">Need help querying myExperiment RDF?<br/>New to SPARQL?</a></small></p>
    <form name="queryform" method="post" action="">
       <p style="padding: 10px 100px; margin-top: -30px;"><small>From time to time modifications are made to the <a href="/ontologies/">myExperiment Ontology</a> and therefore the RDF queried by this SPARQL endpoint.  Please check the <a href="/ontologies/CHANGELOG">CHANGELOG</a> if your query has ceased to function.</p>
       <table style="font-size: 10pt;">
          <tr>
            <th style="text-align: right;">Version Info:</th>
            <td style="text-align: left;"><?= getVersions() ?></td>
          </tr>
          <tr>
            <th style="text-align: right;">No. of Triples:</th>
            <td style="text-align: left;"><?= $notriples ?></td>
          </tr>
          <tr>
            <th style="text-align: right;">Last Snapshot Taken At:</th>
            <td style="text-align: left;"><?= $lmdate ?></td>
          </tr>
          <tr>
            <th style="text-align: right;">Format:</th>
            <td style="text-align: left;">
              <select name="formatting">
          <?php
	$formattings=array_keys($formats);
        for ($f=0; $f<sizeof($formattings); $f++){
                echo "            <option ";
                if ($formattings[$f]==$formatting) echo 'selected="selected" ';
                echo "value=\"".$formattings[$f]."\">".$formattings[$f]."</option>\n";
        }
?>
              </select>
           </td>
        </tr>
        <tr>
          <th style="text-align: right;">Soft Limit:</th>
          <td style="text-align: left;"><input type="text" size="3" maxlength="3" name="softlimit" value="<?=$softlimit?>" />%</td>
        </tr>
      </table>
      <?php if ($err) echo "<br/><div class=\"red\"><b>$err</b></div><br/>\n"; ?>
      <?php if ($msg) echo "<br/><div class=\"green\"><b>$msg</b></div><br/>\n"; ?>
      
      <p>
        <textarea name="query" id="querybox" cols="110" rows="12" style="width: 800px;"><?= htmlentities($query) ?></textarea>
      </p>
      <script type="text/javascript">
var editor = CodeMirror.fromTextArea('querybox', {
    parserfile: ["parsesparql.js"],
    path: "js/",
    stylesheet: "css/sparqlcolors.css"
});
      </script>

      <p>
        <input type="submit" name="submit_query" value ="Submit Query"/>
        &nbsp;&nbsp;
        <input type="submit" name="generate_service" value ="Generate Service for Query"/>
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
		if ($formatting=="HTML Table"){
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
