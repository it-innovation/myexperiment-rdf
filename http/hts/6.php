<h2>6. GROUP BY</h2>

<p>The purpose of the <em>GROUP BY</em> clause is to allow aggregation over one or more properties.  This is particularly useful when you want to use mathematical functions on variables in the <a href="?page=SELECT">SELECT</a> clause.  A good example is using <a href="?page=SELECT#COUNT">COUNT</a> to list how many workflows are owned by each user.</p>
<div class="yellow"><pre>PREFIX sioc: &lt;http://rdfs.org/sioc/ns#&gt;
PREFIX mecontrib: &lt;http://rdf.myexperiment.org/ontologies/contributions/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;

SELECT ?user (COUNT(?workflow) AS ?no_workflows)
WHERE{
  ?workflow rdf:type mecontrib:Workflow .
  ?workflow sioc:has_owner ?user .
}
<b>GROUP BY ?user</b></pre>
<div style="float: right; position: relative; top: -35px; text-align: right;">[<a href="/sparql?query=PREFIX+sioc%3A+%3Chttp%3A%2F%2Frdfs.org%2Fsioc%2Fns%23%3E%0D%0APREFIX+mecontrib%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fcontributions%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0A%0D%0ASELECT+%3Fuser+%28COUNT%28%3Fworkflow%29+AS+%3Fno_workflows%29%0D%0AWHERE%7B%0D%0A++%3Fworkflow+rdf%3Atype+mecontrib%3AWorkflow+.%0D%0A++%3Fworkflow+sioc%3Ahas_owner+%3Fuser+.%0D%0A%7D%0D%0AGROUP+BY+%3Fuser%0D%0A&amp;formatting=HTML Table">Run</a>]<br/><span id="results1_show" onclick="showResults('results1');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results1_hide" onclick="hideResults('results1');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results1">
<table class="listing">
  <tr><td class="shade">http://www.myexperiment.org/users/6890</td><td class="shade">3</td></tr>
  <tr><td>http://www.myexperiment.org/users/36</td><td>2</td></tr>
  <tr><td class="shade">http://www.myexperiment.org/users/10173</td><td class="shade">1</td></tr>
  <tr><td>http://www.myexperiment.org/users/884</td><td>2</td></tr>
  <tr><td class="shade">http://www.myexperiment.org/users/87</td><td class="shade">1</td></tr>
  <tr><td>http://www.myexperiment.org/users/8674</td><td>1</td></tr>
  <tr><td class="shade">http://www.myexperiment.org/users/12835</td><td class="shade">12</td></tr>
  <tr><td>http://www.myexperiment.org/users/7486</td><td>1</td></tr>
  <tr><td class="shade">http://www.myexperiment.org/users/4533</td><td class="shade">11</td></tr>
  <tr><td>http://www.myexperiment.org/users/83</td><td>1</td></tr>
</table>
</div>
<br/>
<p>The <em>GROUP BY</em> clause can also be used with the <a href="?page=SELECT#SUM">SUM</a> function to for example get the total number of downloads for all the workflows owned by each user.</p>
<div class="yellow"><pre>PREFIX mecontrib: <http://rdf.myexperiment.org/ontologies/contributions/>
PREFIX mevd: <http://rdf.myexperiment.org/ontologies/viewings_downloads/>
PREFIX sioc: <http://rdfs.org/sioc/ns#>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
SELECT ?user (<b>SUM</b>(?downloaded) AS ?total_downloads)
WHERE{
  ?workflow rdf:type mecontrib:Workflow ;
    sioc:has_owner ?user ;
    mevd:downloaded ?downloaded
}
GROUP BY ?user</pre>
<div style="float: right; position: relative; top: -35px; text-align: right;">[<a href="/sparql?query=PREFIX+mecontrib%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fcontributions%2F%3E%0D%0APREFIX+mevd%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fviewings_downloads%2F%3E%0D%0APREFIX+sioc%3A+%3Chttp%3A%2F%2Frdfs.org%2Fsioc%2Fns%23%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0ASELECT+%3Fuser+%28SUM%28%3Fdownloaded%29+AS+%3Ftotal_downloads%29%0D%0AWHERE%7B%0D%0A++%3Fworkflow+rdf%3Atype+mecontrib%3AWorkflow+%3B%0D%0A++++sioc%3Ahas_owner+%3Fuser+%3B%0D%0A++++mevd%3Adownloaded+%3Fdownloaded%0D%0A%7D%0D%0AGROUP+BY+%3Fuser%0D%0A&amp;formatting=HTML Table">Run</a>]<br/><span id="results2_show" onclick="showResults('results2');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results2_hide" onclick="hideResults('results2');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results2">
<table class="listing">
  <tr><th>user</th><th>total_downloads</th></tr>
  <tr><td class="shade">http://www.myexperiment.org/users/6890</td><td class="shade">351</td></tr>
  <tr><td>http://www.myexperiment.org/users/36</td><td>520</td></tr>
  <tr><td class="shade">http://www.myexperiment.org/users/10173</td><td class="shade">359</td></tr>
  <tr><td>http://www.myexperiment.org/users/884</td><td>792</td></tr>
  <tr><td class="shade">http://www.myexperiment.org/users/87</td><td class="shade">667</td></tr>
  <tr><td>http://www.myexperiment.org/users/8674</td><td>60</td></tr>
</table>
</div>
<br/>
<p>Again, the <em>GROUP BY</em> clause can also be used with the <a href="?page=SELECT#AVG">AVG</a>, <a href="?page=SELECT#MAX_MIN">MAX and MIN</a> functions to get for example the average, maximum and minium ratings of workflows.</p>
<div class="yellow"><pre>PREFIX mebase: &lt;http://rdf.myexperiment.org/ontologies/base/&gt;
PREFIX meannot: &lt;http://rdf.myexperiment.org/ontologies/annotations/&gt;
PREFIX mecontrib: &lt;http://rdf.myexperiment.org/ontologies/contributions/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;
SELECT ?workflow (<b>AVG</b>(?rating_score) AS ?avg_rating) (<b>MAX</b>(?rating_score) AS ?max_rating) 
  (<b>MIN</b>(?rating_score) AS ?min_rating)
WHERE {
  ?workflow rdf:type mecontrib:Workflow .
  ?rating rdf:type meannot:Rating ;
    mebase:annotates ?workflow ;
    meannot:rating-score ?rating_score
}
GROUP BY ?workflow</pre>
<div style="float: right; position: relative; top: -35px; text-align: right;">[<a href="/sparql?query=PREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+meannot%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fannotations%2F%3E%0D%0APREFIX+mecontrib%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fcontributions%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0ASELECT+%3Fworkflow+%28AVG%28%3Frating_score%29+AS+%3Favg_rating%29+%28MAX%28%3Frating_score%29+AS+%3Fmax_rating%29+%28MIN%28%3Frating_score%29+AS+%3Fmin_rating%29%0D%0AWHERE+%7B%0D%0A++%3Fworkflow+rdf%3Atype+mecontrib%3AWorkflow+.%0D%0A++%3Frating+rdf%3Atype+meannot%3ARating+%3B%0D%0A++++mebase%3Aannotates+%3Fworkflow+%3B%0D%0A++++meannot%3Arating-score+%3Frating_score%0D%0A%7D%0D%0AGROUP+BY+%3Fworkflow&amp;formatting=HTML Table">Run</a>]<br/><span id="results3_show" onclick="showResults('results3');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results3_hide" onclick="hideResults('results3');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results3">
<table class="listing">
  <tr><th>workflow</th><th>avg_rating</th>><th>max_rating</th><th>min_rating</th></tr>
  <tr><td class="shade">http://www.myexperiment.org/workflows/240</td><td class="shade">5</td><td class="shade">5</td><td class="shade">5</td></tr>
  <tr><td>http://www.myexperiment.org/workflows/761</td><td>4.999999999999999999</td><td>5</td><td>5</td></tr>
  <tr><td class="shade">http://www.myexperiment.org/workflows/1402</td><td class="shade">4</td><td class="shade">4</td><td class="shade">4</td></tr>
  <tr><td>http://www.myexperiment.org/workflows/20</td><td>3.9999999999999999992</td><td>5</td><td>3</td></tr>
  <tr><td class="shade">http://www.myexperiment.org/workflows/6</td><td class="shade">4.4999999999999999991</td><td class="shade">5</td><td class="shade">4</td></tr>
  <tr><td>http://www.myexperiment.org/workflows/688</td><td>4</td><td>4</td><td>4</td></tr>
</table>
<br/>
<p style="font-size: 0.9em; font-weight: bold;">N.B. Currently (March 9th 2011) 4Store does not properly handle floating point numbers properly so average results may need to be manually rounded to make for example 4.4999999999999999991 into 4.5.</p>
</div>
<br/>
<script type= "text/javascript"><!-- 
  hideResults('results1');
  hideResults('results2');
  hideResults('results3');
--></script>

