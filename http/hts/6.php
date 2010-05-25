<h2>6. ORDER BY</h2>
<p>Like with the <a href="?page=FILTER#On Dates">dates example</a> previously you may want to go further so that the most recent workflows are listed first.  The <em>ORDER BY</em> clause can be used to do this:</p>
<div class="yellow"><pre>PREFIX xsd: &lt;http://www.w3.org/2001/XMLSchema#&gt;
PREFIX dcterms: &lt;http://purl.org/dc/terms/&gt;
PREFIX mecontrib: &lt;http://rdf.myexperiment.org/ontologies/contributions/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;
SELECT ?workflow ?added
WHERE { 
  ?workflow rdf:type mecontrib:Workflow ;
    dcterms:created ?added
  FILTER ( ?added &gt;= xsd:dateTime('2009-09-01T00:00:00Z') ) 
}
<b>ORDER BY DESC(?added)</b></pre>
<div style="float: right; position: relative; top: -35px; text-align: right;">[<a href="sparql?query=PREFIX+xsd%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F2001%2FXMLSchema%23%3E%0D%0APREFIX+dcterms%3A+%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%0D%0APREFIX+mecontrib%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fcontributions%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0ASELECT+%3Fworkflow+%3Fadded%0D%0AWHERE+%7B+%0D%0A++%3Fworkflow+rdf%3Atype+mecontrib%3AWorkflow+%3B%0D%0A++++dcterms%3Acreated+%3Fadded%0D%0A++FILTER+%28+%3Fadded+%3E%3D+xsd%3AdateTime%28%272009-09-01T00%3A00%3A00Z%27%29+%29+%0D%0A%7D%0D%0AORDER+BY+DESC%28%3Fadded%29&amp;formatting=HTML Table">Run</a>]<br/><span id="results1_show" onclick="showResults('results1');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results1_hide" onclick="hideResults('results1');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results1">
<table class="listing">
  <tr><th>workflow</th><th>added</th></tr>
  <tr><td class="shade">http://rdf.myexperiment.org/Workflow/1008</td><td class="shade">2009-12-22T20:45:54Z</td></tr>
  <tr><td>http://rdf.myexperiment.org/Workflow/1005</td><td>2009-12-15T22:33:09Z</td></tr>
  <tr><td class="shade">http://rdf.myexperiment.org/Workflow/1004</td><td class="shade">2009-12-15T22:17:56Z</td></tr>
  <tr><td>http://rdf.myexperiment.org/Workflow/1003</td><td>2009-12-15T22:17:11Z</td></tr>
  <tr><td class="shade">http://rdf.myexperiment.org/Workflow/1002</td><td class="shade">2009-12-15T22:16:23Z</td></tr>
</table>
</div>
<p>The <em>DESC</em> operand means order the results in descending order, in this case latest workflows first.  If you wanted them ordered ascending you don't require any operand.  If you want a second criteria to order on you just add this after the first parammeter.  An example of this might be order by workflow type with the latest of each type first:</p>
<div class="yellow"><pre>PREFIX mebase: &lt;http://rdf.myexperiment.org/ontologies/base/&gt;
PREFIX xsd: &lt;http://www.w3.org/2001/XMLSchema#&gt;
PREFIX dcterms: &lt;http://purl.org/dc/terms/&gt;
PREFIX mecontrib: &lt;http://rdf.myexperiment.org/ontologies/contributions/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;
SELECT ?workflow ?added ?ct_title
WHERE { 
  ?workflow rdf:type mecontrib:Workflow ;
    dcterms:created ?added ;
    mebase:has-content-type ?ct .
  ?ct dcterms:title ?ct_title 
  FILTER ( ?added &gt;= xsd:dateTime('2009-09-01T00:00:00Z') ) 
}
ORDER BY <b>?ct_title</b> DESC(?added)</pre>
<div style="float: right; position: relative; top: -35px; text-align: right;">[<a href="sparql?query=PREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+xsd%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F2001%2FXMLSchema%23%3E%0D%0APREFIX+dcterms%3A+%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%0D%0APREFIX+mecontrib%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fcontributions%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0ASELECT+%3Fworkflow+%3Fadded+%3Fct_title%0D%0AWHERE+%7B+%0D%0A++%3Fworkflow+rdf%3Atype+mecontrib%3AWorkflow+%3B%0D%0A++++dcterms%3Acreated+%3Fadded+%3B%0D%0A++++mebase%3Ahas-content-type+%3Fct+.%0D%0A++%3Fct+dcterms%3Atitle+%3Fct_title+%0D%0A++FILTER+%28+%3Fadded+%3E%3D+xsd%3AdateTime%28%272009-09-01T00%3A00%3A00Z%27%29+%29+%0D%0A%7D%0D%0AORDER+BY+%3Fct_title+DESC%28%3Fadded%29&amp;formatting=HTML Table">Run</a>]<br/><span id="results2_show" onclick="showResults('results2');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results2_hide" onclick="hideResults('results2');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results2">
<table class="listing">
  <tr><th>workflow</th><th>added</th><th>ct_title</th></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/36</td><td class="shade">2010-01-12T13:58:46Z</td><td class="shade">Taverna 1</td></tr>
  <tr><td><?= $datauri ?>workflows/15</td><td>2009-12-04T16:04:38Z</td><td>Taverna 1</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/994</td><td class="shade">2009-12-04T10:47:04Z</td><td class="shade">Taverna 1</td></tr>
  <tr><td><?= $datauri ?>workflows/1005</td><td>2009-12-15T22:33:09Z</td><td>Taverna 2 beta</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/1004</td><td class="shade">2009-12-15T22:17:56Z</td><td class="shade">Taverna 2 beta</td></tr>
  <tr><td><?= $datauri ?>workflows/1003</td><td>2009-12-15T22:17:11Z</td><td>Taverna 2 beta</td></tr>
</table>
</div>

<script type= "text/javascript"><!-- 
  hideResults('results1');
  hideResults('results2');
--></script>

