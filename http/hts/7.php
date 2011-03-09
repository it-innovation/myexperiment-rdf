<h2>7. ORDER BY</h2>
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
<div style="float: right; position: relative; top: -35px; text-align: right;">[<a href="/sparql?query=PREFIX+xsd%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F2001%2FXMLSchema%23%3E%0D%0APREFIX+dcterms%3A+%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%0D%0APREFIX+mecontrib%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fcontributions%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0ASELECT+%3Fworkflow+%3Fadded%0D%0AWHERE+%7B+%0D%0A++%3Fworkflow+rdf%3Atype+mecontrib%3AWorkflow+%3B%0D%0A++++dcterms%3Acreated+%3Fadded%0D%0A++FILTER+%28+%3Fadded+%3E%3D+xsd%3AdateTime%28%272009-09-01T00%3A00%3A00Z%27%29+%29+%0D%0A%7D%0D%0AORDER+BY+DESC%28%3Fadded%29&amp;formatting=HTML Table">Run</a>]<br/><span id="results1_show" onclick="showResults('results1');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results1_hide" onclick="hideResults('results1');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results1">
<table class="listing">
  <tr><th>workflow</th><th>added</th></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/1008</td><td class="shade">2009-12-22T20:45:54Z</td></tr>
  <tr><td><?= $datauri ?>workflows/1005</td><td>2009-12-15T22:33:09Z</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/1004</td><td class="shade">2009-12-15T22:17:56Z</td></tr>
  <tr><td><?= $datauri ?>workflows/1003</td><td>2009-12-15T22:17:11Z</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/1002</td><td class="shade">2009-12-15T22:16:23Z</td></tr>
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
<div style="float: right; position: relative; top: -35px; text-align: right;">[<a href="/sparql?query=PREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+xsd%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F2001%2FXMLSchema%23%3E%0D%0APREFIX+dcterms%3A+%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%0D%0APREFIX+mecontrib%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fcontributions%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0ASELECT+%3Fworkflow+%3Fadded+%3Fct_title%0D%0AWHERE+%7B+%0D%0A++%3Fworkflow+rdf%3Atype+mecontrib%3AWorkflow+%3B%0D%0A++++dcterms%3Acreated+%3Fadded+%3B%0D%0A++++mebase%3Ahas-content-type+%3Fct+.%0D%0A++%3Fct+dcterms%3Atitle+%3Fct_title+%0D%0A++FILTER+%28+%3Fadded+%3E%3D+xsd%3AdateTime%28%272009-09-01T00%3A00%3A00Z%27%29+%29+%0D%0A%7D%0D%0AORDER+BY+%3Fct_title+DESC%28%3Fadded%29&amp;formatting=HTML Table">Run</a>]<br/><span id="results2_show" onclick="showResults('results2');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results2_hide" onclick="hideResults('results2');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results2">
<table class="listing">
  <tr><th>workflow</th><th>added</th><th>ct_title</th></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/36</td><td class="shade">2010-01-12T13:58:46Z</td><td class="shade">Taverna 1</td></tr>
  <tr><td><?= $datauri ?>workflows/15</td><td>2009-12-04T16:04:38Z</td><td>Taverna 1</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/994</td><td class="shade">2009-12-04T10:47:04Z</td><td class="shade">Taverna 1</td></tr>
  <tr><td><?= $datauri ?>workflows/1005</td><td>2009-12-15T22:33:09Z</td><td>Taverna 2 beta</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/1004</td><td class="shade">2009-12-15T22:17:56Z</td><td class="shade">Taverna 2 beta</td></tr>
</table>
</div>
<br/>
<p>By default, <em>ORDER BY</em> will treat the field(s) it is ordering on as being alphanumeric.  In some cases it is necessary to treat a field as being numeric.  An example of this is a list of the most downloaded workflows.  Below is an example of how to treat <em>?downloaded</em> as a numeric field.</p>
<div class="yellow"><pre>BASE &lt;http://www.myexperiment.org/&gt;
PREFIX xsd: &lt;http://www.w3.org/2001/XMLSchema#&gt;
PREFIX mevd: &lt;http://rdf.myexperiment.org/ontologies/viewings_downloads/&gt;
PREFIX sioc: &lt;http://rdfs.org/sioc/ns#&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;
PREFIX mecontrib: &lt;http://rdf.myexperiment.org/ontologies/contributions/&gt;

SELECT ?workflow ?downloaded
WHERE {
  ?workflow rdf:type mecontrib:Workflow .
  ?workflow sioc:has_owner &lt;users/43&gt; .
  ?workflow mevd:downloaded ?downloaded
}
ORDER BY DESC(<b>xsd:nonNegativeInteger(?downloaded)</b>)</pre><div style="text-align: right; float: right; position: relative; top: -35px;">[<a href="/sparql?query=BASE+%3Chttp%3A%2F%2Fwww.myexperiment.org%2F%3E%0D%0APREFIX+xsd%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F2001%2FXMLSchema%23%3E%0D%0APREFIX+mevd%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fviewings_downloads%2F%3E%0D%0APREFIX+sioc%3A+%3Chttp%3A%2F%2Frdfs.org%2Fsioc%2Fns%23%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0APREFIX+mecontrib%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fcontributions%2F%3E%0D%0A%0D%0ASELECT+%3Fworkflow+%3Fdownloaded%0D%0AWHERE+%7B%0D%0A++%3Fworkflow+rdf%3Atype+mecontrib%3AWorkflow+.%0D%0A++%3Fworkflow+sioc%3Ahas_owner+%3Cusers%2F43%3E+.%0D%0A++%3Fworkflow+mevd%3Adownloaded+%3Fdownloaded%0D%0A%7D%0D%0AORDER+BY+DESC%28xsd%3AnonNegativeInteger%28%3Fdownloaded%29%29%0D%0A&amp;formatting=HTML Table">Run</a>]<br/><span id="results3_show" onclick="showResults('results3');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results3_hide" onclick="hideResults('results3');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results3" style="clear: both; position: relative; top: -26px;">
 <table style="margin-left: auto; margin-right: auto;"><tr><td style="vertical-align: top;">
    <h4 style="text-align: center; padding-bottom: 5px;">Alphanumeric Ordering</h4>
    <table class="listing">
      <tr><th>workflow</th><th>downloaded</th></tr>
      <tbody style="font-size: 0.9em;">
        <tr><td class="shade">http://www.myexperiment.org/workflows/1392</td><td class="shade">99</td></tr>
        <tr><td>http://www.myexperiment.org/workflows/12</td><td>988</td></tr>
        <tr><td class="shade">http://www.myexperiment.org/workflows/95</td><td class="shade">985</td></tr>
        <tr><td>http://www.myexperiment.org/workflows/1215</td><td>98</td></tr>
        <tr><td class="shade">http://www.myexperiment.org/workflows/1105</td><td class="shade">98</td></tr>
        <tr><td>http://www.myexperiment.org/workflows/1224</td><td>98</td></tr>
        <tr><td class="shade">http://www.myexperiment.org/workflows/1246</td><td class="shade">98</td></tr>
        <tr><td>http://www.myexperiment.org/workflows/1315</td><td>98</td></tr>
        <tr><td class="shade">http://www.myexperiment.org/workflows/75</td><td class="shade">978</td></tr>
        <tr><td>http://www.myexperiment.org/workflows/39</td><td>976</td></tr> 
      </tbody>
    </table>
  </td>
  <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
  <td style="vertical-align: top;">
    <h4 style="text-align: center; padding-bottom: 5px;">Numeric Ordering</h4>
    <table class="listing">
      <tr><th>workflow</th><th>downloaded</th></tr>
      <tbody style="font-size: 0.9em;">
        <tr><td class="shade">http://www.myexperiment.org/workflows/140</td><td class="shade">7852</td></tr>
        <tr><td>http://www.myexperiment.org/workflows/10</td><td>3250</td></tr>
        <tr><td class="shade">http://www.myexperiment.org/workflows/16</td><td class="shade">2918</td></tr>
        <tr><td>http://www.myexperiment.org/workflows/72</td><td>2631</td></tr>
        <tr><td class="shade">http://www.myexperiment.org/workflows/15</td><td class="shade">2542</td></tr>
        <tr><td>http://www.myexperiment.org/workflows/124</td><td>2303</td></tr>
        <tr><td class="shade">http://www.myexperiment.org/workflows/154</td><td class="shade">2162</td></tr>
        <tr><td>http://www.myexperiment.org/workflows/158</td><td>2008</td></tr>
        <tr><td class="shade">http://www.myexperiment.org/workflows/79</td><td class="shade">1788</td></tr>
        <tr><td>http://www.myexperiment.org/workflows/19</td><td>1617</td></tr>
      </tbody>
    </table>
  </td></tr></table>
</div>

<script type= "text/javascript"><!-- 
  hideResults('results1');
  hideResults('results2');
  hideResults('results3');
--></script>

