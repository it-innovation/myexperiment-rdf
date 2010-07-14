<h2>5. FILTER</h2>
<p>The <em>FILTER</em> clause is used within the curly parenthesis {} as a subclause of the WHERE clause.  As it's name suggests, it allows you to filter out the results based on certain conditions.  Most commonly you may want to filter on text but you can also filter on numbers and dates.</p>
<h3>5.1. On Text<a name="On Text"/></h3>
<p>An example of a query where you want to filter on text is when you want to find all Taverna workflows (both 1 and 2):</p>
<div class="yellow"><pre>PREFIX dcterms: &lt;http://purl.org/dc/terms/&gt;
PREFIX mebase: &lt;http://rdf.myexperiment.org/ontologies/base/&gt;
PREFIX mecontrib: &lt;http://rdf.myexperiment.org/ontologies/contributions/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;
SELECT ?workflow
WHERE{
  ?workflow rdf:type mecontrib:Workflow ;
    mebase:has-content-type ?ct .
  ?ct dcterms:title ?ct_title 
  <b>FILTER regex(?ct_title,'^taverna','i')</b>
}</pre>
<div style="float: right; position: relative; top: -35px; text-align: right;">[<a href="/sparql?query=PREFIX+dcterms%3A+%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%0D%0APREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+mecontrib%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fcontributions%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0ASELECT+%3Fworkflow+%3Fct_title%0D%0AWHERE%7B%0D%0A++%3Fworkflow+rdf%3Atype+mecontrib%3AWorkflow+%3B%0D%0A++++mebase%3Ahas-content-type+%3Fct+.%0D%0A++%3Fct+dcterms%3Atitle+%3Fct_title+%0D%0A++FILTER+regex%28%3Fct_title%2C%27%5Etaverna%27%2C%27i%27%29%0D%0A%7D&amp;formatting=HTML Table">Run</a>]<br/><span id="results1_show" onclick="showResults('results1');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results1_hide" onclick="hideResults('results1');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results1">
<table class="listing">
  <tr><th>workflow</th><th>ct_title</th></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/167</td><td class="shade">Taverna 1</td></tr>
  <tr><td><?= $datauri ?>workflows/932</td><td>Taverna 2 beta</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/193</td><td class="shade">Taverna 1</td></tr>
  <tr><td><?= $datauri ?>workflows/874</td><td>Taverna 1</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/519</td><td class="shade">Taverna 1</td></tr>
  <tr><td><?= $datauri ?>workflows/545</td><td>Taverna 1</td></tr>
</table>
</div>
<p style="clear: both;">The <em>regex</em> operand allows you to compare two text strings.  In the example above this compares the value of ?ct_title with the text string 'taverna'.  The caret (^) sign is used to indicate that the string for ?ct_title must start with 'taverna', not just have it somewhere within the string.  The 'i' as the third parameter for the regex operand means that the regular expression is case insentive, if you wish it to be case sensitive only the first two parameters are required.</p> 
<p>Sometimes you may want to pattern match to a variable that is a URI rather than a literal to do this you need to use the <em>str</em> operand to convert the URI into a string.  An example of this may be to find all the accepted membership where a group asked a user to join:</p>
<div class="yellow"><pre>PREFIX mebase: &lt;http://rdf.myexperiment.org/ontologies/base/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;
SELECT ?membership ?requester
WHERE{
  ?membership rdf:type mebase:Membership ;
    mebase:has-requester ?requester ;
    mebase:has-accepter ?accepter ;
    mebase:accepted-at ?accepted_at
  FILTER regex(<b>str(?requester)</b>,'Group','i')
}</pre>
<div style="float: right; position: relative; top: -35px; text-align: right;">[<a href="/sparql?query=PREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0ASELECT+%3Fmembership+%3Frequester%0D%0AWHERE%7B%0D%0A++%3Fmembership+rdf%3Atype+mebase%3AMembership+%3B%0D%0A++++mebase%3Ahas-requester+%3Frequester+%3B%0D%0A++++mebase%3Ahas-accepter+%3Faccepter+%3B%0D%0A++++mebase%3Aaccepted-at+%3Faccepted_at%0D%0A++FILTER+regex%28str%28%3Frequester%29%2C%27Group%27%2C%27i%27%29%0D%0A%7D&amp;formatting=HTML Table">Run</a>]<br/><span id="results2_show" onclick="showResults('results2');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results2_hide" onclick="hideResults('results2');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results2">
<table class="listing">
<tr><th>membership</th><th>requester</th></tr>
  <tr><td class="shade"><?= $datauri ?>users/30/memberships/96</td><td class="shade"><?= $datauri ?>groups/51</td></tr>
  <tr><td><?= $datauri ?>users/2646/memberships/825</td><td><?= $datauri ?>groups/195</td></tr>
  <tr><td class="shade"><?= $datauri ?>users/2213/memberships/884</td><td class="shade"><?= $datauri ?>groups/211</td></tr>
  <tr><td><?= $datauri ?>users/2611/memberships/745</td><td><?= $datauri ?>groups/187</td></tr>
  <tr><td class="shade"><?= $datauri ?>users/13/memberships/721</td><td class="shade"><?= $datauri ?>groups/194</td></tr>
</table>
</div>
<h3>5.2. On Numbers<a name="On Numbers"/></h3>
<p>In some cases you may want to query on a number being greater (or less than) a particular value.  The FILTER clause allows inequalities (and equalities) to be defined as criteria for filtering on.  An example of this may be to find all the workflows that have a rating that is 4 or greater:</p>
<div class="yellow"><pre>PREFIX dcterms: &lt;http://purl.org/dc/terms/&gt;
PREFIX mebase: &lt;http://rdf.myexperiment.org/ontologies/base/&gt;
PREFIX meannot: &lt;http://rdf.myexperiment.org/ontologies/annotations/&gt;
PREFIX mecontrib: &lt;http://rdf.myexperiment.org/ontologies/contributions/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;
SELECT DISTINCT ?workflow ?ct_title
WHERE{
  ?workflow rdf:type mecontrib:Workflow ;
    mebase:has-content-type ?ct .
  ?ct dcterms:title ?ct_title .
  ?rating rdf:type meannot:Rating ;
    mebase:annotates ?workflow ;
    meannot:rating-score ?score 
  <b>FILTER (?score &gt;= 4)</b>
}</pre>
<div style="float: right; position: relative; top: -35px; text-align: right;">[<a href="/sparql?query=PREFIX+dcterms%3A+%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%0D%0APREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+meannot%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fannotations%2F%3E%0D%0APREFIX+mecontrib%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fcontributions%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0ASELECT+DISTINCT+%3Fworkflow+%3Fct_title%0D%0AWHERE%7B%0D%0A++%3Fworkflow+rdf%3Atype+mecontrib%3AWorkflow+%3B%0D%0A++++mebase%3Ahas-content-type+%3Fct+.%0D%0A++%3Fct+dcterms%3Atitle+%3Fct_title+.%0D%0A++%3Frating+rdf%3Atype+meannot%3ARating+%3B%0D%0A++++mebase%3Aannotates+%3Fworkflow+%3B%0D%0A++++meannot%3Arating-score+%3Fscore%0D%0A++FILTER+%28%3Fscore+%3E%3D+4%29%0D%0A%7D&amp;formatting=HTML Table">Run</a>]<br/><span id="results3_show" onclick="showResults('results3');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results3_hide" onclick="hideResults('results3');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results3">
<table class="listing">
  <tr><td><?= $datauri ?>workflows/244</td><td>Taverna 1</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/742</td><td class="shade">SimileXMLv3</td></tr>
  <tr><td><?= $datauri ?>workflows/19</td><td>Taverna 1</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/133</td><td class="shade">Taverna 1</td></tr>
  <tr><td><?= $datauri ?>workflows/735</td><td>Excel 2007 Macro-Enabled Workbook</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/90</td><td class="shade">Taverna 1</td></tr>
  <tr><td><?= $datauri ?>workflows/549</td><td>Chemistry Plan</td></tr>
</table>
</div>
<p style="clear: both;">
You may want to filter on more than one criteria.  For the above example you may only want Taverna 1 workflows that are rated at least 4 out of 5:</p>
<div class="yellow"><pre>PREFIX dcterms: &lt;http://purl.org/dc/terms/&gt;
PREFIX mebase: &lt;http://rdf.myexperiment.org/ontologies/base/&gt;
PREFIX meannot: &lt;http://rdf.myexperiment.org/ontologies/annotations/&gt;
PREFIX mecontrib: &lt;http://rdf.myexperiment.org/ontologies/contributions/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;
SELECT DISTINCT ?workflow ?ct_title
WHERE{
  ?workflow rdf:type mecontrib:Workflow ;
    mebase:has-content-type ?ct .
  ?ct dcterms:title ?ct_title .
  ?rating rdf:type meannot:Rating ;
    mebase:annotates ?workflow ;
    meannot:rating-score ?score
  FILTER (?score &gt;= 4 <b>&amp;&amp; regex(?ct_title,'^Taverna 1')</b>)
}</pre>
<div style="float: right; position: relative; top: -35px; text-align: right;">[<a href="/sparql?query=PREFIX+dcterms%3A+%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%0D%0APREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+meannot%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fannotations%2F%3E%0D%0APREFIX+mecontrib%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fcontributions%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0ASELECT+DISTINCT+%3Fworkflow+%3Fct_title%0D%0AWHERE%7B%0D%0A++%3Fworkflow+rdf%3Atype+mecontrib%3AWorkflow+%3B%0D%0A++++mebase%3Ahas-content-type+%3Fct+.%0D%0A++%3Fct+dcterms%3Atitle+%3Fct_title+.%0D%0A++%3Frating+rdf%3Atype+meannot%3ARating+%3B%0D%0A++++mebase%3Aannotates+%3Fworkflow+%3B%0D%0A++++meannot%3Arating-score+%3Fscore%0D%0A++FILTER+%28%3Fscore+%3E%3D+4+%26%26+regex%28%3Fct_title%2C%27%5ETaverna+1%27%29%29%0D%0A%7D&amp;formatting=HTML Table">Run</a>]<br/><span id="results4_show" onclick="showResults('results4');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results4_hide" onclick="hideResults('results4');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results4">
<table class="listing">
  <tr><th>workflow</th><th>ct_title</th></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/173</td><td class="shade">Taverna 1</td></tr>
  <tr><td><?= $datauri ?>workflows/40</td><td>Taverna 1</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/66</td><td class="shade">Taverna 1</td></tr>
  <tr><td><?= $datauri ?>workflows/235</td><td>Taverna 1</td></tr>
</table>
</div>
<h3>5.3. On Dates<a name="On Dates"/></h3>
<p>Often one of the most useful filters is for finding things that have been added/modified before or after a certain date.  You may want to find all the workflows that have been added since the beginning of September 2009:</p>
<div class="yellow"><pre>PREFIX mecontrib: &lt;http://rdf.myexperiment.org/ontologies/contributions/&gt;
PREFIX dcterms: &lt;http://purl.org/dc/terms/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;
PREFIX xsd: &lt;http://www.w3.org/2001/XMLSchema#&gt;
SELECT ?workflow ?added
WHERE { 
  ?workflow rdf:type mecontrib:Workflow ;
    dcterms:created ?added
  <b>FILTER ( ?added &gt;= xsd:dateTime('2009-09-01T00:00:00Z') )</b>
}</pre>
<div style="float: right; position: relative; top: -35px; text-align: right;">[<a href="/sparql?query=PREFIX+mecontrib%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fcontributions%2F%3E%0D%0APREFIX+dcterms%3A+%3Chttp%3A%2F%2Fpurl.org%2Fdc%2Fterms%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0APREFIX+xsd%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F2001%2FXMLSchema%23%3E%0D%0ASELECT+%3Fworkflow+%3Fadded%0D%0AWHERE+%7B+%0D%0A++%3Fworkflow+rdf%3Atype+mecontrib%3AWorkflow+%3B%0D%0A++++dcterms%3Acreated+%3Fadded%0D%0A++FILTER+%28+%3Fadded+%3E%3D+xsd%3AdateTime%28%272009-09-01T00%3A00%3A00Z%27%29+%29+%0D%0A%7D&amp;formatting=HTML Table">Run</a>]<br/><span id="results5_show" onclick="showResults('results5');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results5_hide" onclick="hideResults('results5');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results5">
<table class="listing">
  <tr><th>workflow</th><th>added</th></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/932</td><td class="shade">2009-10-20T17:00:59Z</td></tr>
  <tr><td><?= $datauri ?>workflows/952</td><td>2009-11-16T12:37:25Z</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/906</td><td class="shade">2009-09-08T15:12:57Z</td></tr>
  <tr><td><?= $datauri ?>workflows/955</td><td>2009-11-16T18:10:54Z</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/912</td><td class="shade">2009-09-15T11:32:52Z</td></tr>
</table>
</div>
<script type= "text/javascript"><!-- 
  hideResults('results1');
  hideResults('results2');
  hideResults('results3');
  hideResults('results4');
  hideResults('results5');
--></script>

