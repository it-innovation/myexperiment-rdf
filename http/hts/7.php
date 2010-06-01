<h2>7. LIMIT</h2>
<p>Sometimes you may not want all possible results.  The <em>LIMIT</em> clause allows you to limit how many results are returned.  In the examples used in <a href="?page=FILTER">FILTER</a> and <a href="?page=ORDER BY">ORDER BY</a> you may only want to the latest 10 workflows:</p>
<div class="yellow"><pre>PREFIX dcterms: &lt;http://purl.org/dc/terms/&gt;
PREFIX mecontrib: &lt;http://rdf.myexperiment.org/ontologies/contributions/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;
SELECT ?workflow ?added
WHERE { 
  ?workflow rdf:type mecontrib:Workflow ;
    dcterms:created ?added
}
ORDER BY DESC(?added)
<b>LIMIT 10</b></pre>
<div style="float: right; position: relative; top: -35px; text-align: right;">[<a href="sparql?query=PREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+xsd%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F2001%2FXMLSchema%23%3E%0D%0APREFIX+dcterms%3A+%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%0D%0APREFIX+mecontrib%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fcontributions%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0ASELECT+%3Fworkflow+%3Fadded+%3Fct_title%0D%0AWHERE+%7B+%0D%0A++%3Fworkflow+rdf%3Atype+mecontrib%3AWorkflow+%3B%0D%0A++++dcterms%3Acreated+%3Fadded+%3B%0D%0A++++mebase%3Ahas-content-type+%3Fct+.%0D%0A++%3Fct+dcterms%3Atitle+%3Fct_title+%0D%0A++FILTER+%28+%3Fadded+%3E%3D+xsd%3AdateTime%28%272009-09-01T00%3A00%3A00Z%27%29+%29+%0D%0A%7D%0D%0AORDER+BY+%3Fct_title+DESC%28%3Fadded%29&amp;formatting=HTML Table">Run</a>]<br/><span id="results1_show" onclick="showResults('results1');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results1_hide" onclick="hideResults('results1');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results1">
  <table class="listing">
  <tr><th>workflow</th><th>added</th></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/1010</td><td class="shade">2010-01-12T13:58:46Z</td></tr>
  <tr><td><?= $datauri ?>workflows/1009</td><td>2010-01-04T17:42:36Z</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/1008</td><td class="shade">2009-12-22T20:45:54Z</td></tr>
  <tr><td><?= $datauri ?>workflows/1005</td><td>2009-12-15T22:33:09Z</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/1004</td><td class="shade">2009-12-15T22:17:56Z</td></tr>
  <tr><td><?= $datauri ?>workflows/1003</td><td>2009-12-15T22:17:11Z</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/1002</td><td class="shade">2009-12-15T22:16:23Z</td></tr>
  <tr><td><?= $datauri ?>workflows/1001</td><td>2009-12-15T22:15:21Z</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/1000</td><td class="shade">2009-12-15T22:14:39Z</td></tr>
  <tr><td><?= $datauri ?>workflows/999</td><td>2009-12-15T22:13:44Z</td></tr>
</table>
</div>
<h3><a name="OFFSET"/>7.1 OFFSET</h3>
<p>Once you have got the first 10 workflows you might want to get the next 10. The <em>OFFSET</em> clause allows you to do this:</p>
<div class="yellow"><pre>PREFIX dcterms: &lt;http://purl.org/dc/terms/&gt;
PREFIX mecontrib: &lt;http://rdf.myexperiment.org/ontologies/contributions/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;
SELECT ?workflow ?added
WHERE {
  ?workflow rdf:type mecontrib:Workflow ;
    dcterms:created ?added
}
ORDER BY DESC(?added)
LIMIT 10
<b>OFFSET 10</b></pre>
<div style="float: right; position: relative; top: -35px; text-align: right;">[<a href="sparql?query=PREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+dcterms%3A+%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%0D%0APREFIX+mecontrib%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fcontributions%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0ASELECT+%3Fworkflow+%3Fadded%0D%0AWHERE+%7B+%0D%0A++%3Fworkflow+rdf%3Atype+mecontrib%3AWorkflow+%3B%0D%0A++++dcterms%3Acreated+%3Fadded%0D%0A%7D%0D%0AORDER+BY+DESC%28%3Fadded%29%0D%0ALIMIT+10%0D%0AOFFSET+10&amp;formatting=HTML Table">Run</a>]<br/><span id="results2_show" onclick="showResults('results2');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results2_hide" onclick="hideResults('results2');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results2">
<table class="listing">
  <tr><th>workflow</th><th>added</th></tr>
  <tr><td class="shade"><?= $datauri ?>workflowss/998</td><td class="shade">2009-12-15T22:12:50Z</td></tr>
  <tr><td><?= $datauri ?>workflows/997</td><td>2009-12-15T22:12:00Z</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/996</td><td class="shade">2009-12-15T22:10:36Z</td></tr>
  <tr><td><?= $datauri ?>workflows/995</td><td>2009-12-04T16:04:38Z</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/994</td><td class="shade">2009-12-04T10:47:04Z</td></tr>
  <tr><td><?= $datauri ?>workflows/993</td><td>2009-12-01T06:56:54Z</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/992</td><td class="shade">2009-12-01T06:28:26Z</td></tr>
  <tr><td><?= $datauri ?>workflows/991</td><td>2009-12-01T06:24:54Z</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/990</td><td class="shade">2009-12-01T04:08:08Z</td></tr>
  <tr><td><?= $datauri ?>workflows/989</td><td>2009-12-01T03:01:28Z</td></tr>
</table>
<p>If you then want the third set of 10 you can change to <em>OFFSET 20</em>.  The OFFSET clause can be used without the LIMIT clause, by removing the LIMIT clause in the example above you will get all but the more recent 10 workflows.</p>
</div>


<script type= "text/javascript"><!-- 
  hideResults('results1');
  hideResults('results2');
--></script>

