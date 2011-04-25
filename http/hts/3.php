<h2>3. SELECT</h2>
<p>After adding your prefixes most SPARQL queries start with a <em>SELECT</em>, although queries can start with ASK, DESCRIBE or CONSTRUCT but these will not be discussed here.  The purpose of the SELECT is very similar to it's use in <a href="http://www.w3schools.com/sql/sql_select.asp">SQL</a>. It allows you to define which variables in your query you want values returned for.  Like SQL you can list these individually or use an asterisk (*) to specify you want values return for each variable.  E.g.</p>
<div class="yellow"><pre>PREFIX mebase: &lt;http://rdf.myexperiment.org/ontologies/base/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;

<b>SELECT ?a ?text</b>
WHERE {
  ?a rdf:type mebase:Announcement .
  ?a mebase:text ?text
}</pre><div style="text-align: right; float: right; position: relative; top: -35px;">[<a href="/sparql?query=PREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0A%0D%0ASELECT+%3Fa+%3Ftext%0D%0AWHERE+%7B%0D%0A++%3Fa+rdf%3Atype+mebase%3AAnnouncement+.%0D%0A++%3Fa+mebase%3Atext+%3Ftext%0D%0A%7D&amp;formatting=HTML Table">Run</a>]<br/><span id="results1_show" onclick="showResults('results1');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results1_hide" onclick="hideResults('results1');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results1">
  <?php include('hts/announcements.inc.php'); ?>
</div>
<div style="clear:both;"/>
<p>Is the same query as:</p>
<div class="yellow"><pre>PREFIX mebase: &lt;http://rdf.myexperiment.org/ontologies/base/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;

<b>SELECT *</b>
WHERE {
  ?a rdf:type mebase:Announcement .
  ?a mebase:text ?text
}</pre><div style="text-align: right; float: right; position: relative; top: -35px;">[<a href="/sparql?query=PREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0A%0D%0ASELECT+%2A%0D%0AWHERE+%7B%0D%0A++%3Fa+rdf%3Atype+mebase%3AAnnouncement+.%0D%0A++%3Fa+mebase%3Atext+%3Ftext%0D%0A%7D&amp;formatting=HTML Table">Run</a>]<br/><span id="results2_show" onclick="showResults('results2');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results2_hide" onclick="hideResults('results2');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results2">
  <?php include('hts/announcements.inc.php'); ?>
</div>

<br/>
<h3><a name="DISTINCT"/>3.1. DISTINCT</h3>
<p style="clear: both;">It is not uncommon that the sets of results will return duplicates.  If you don't want duplicates you can append <em>DISTINCT</em> after SELECT.

<div class="yellow"><pre>PREFIX meannot: &lt;http://rdf.myexperiment.org/ontologies/annotations/&gt;
PREFIX sioc: &lt;http://rdfs.org/sioc/ns#&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;
PREFIX mebase: &lt;http://rdf.myexperiment.org/ontologies/base/&gt;
SELECT <b>DISTINCT</b> ?annotator_name
WHERE {
  ?comment mebase:annotates &lt;<?= $datauri ?>workflows/52&gt; .
  ?comment rdf:type meannot:Comment .
  ?comment mebase:has-annotator ?annotator
  ?annotator sioc:name ?annotator_name
}
</pre><div style="float: right; position: relative; top: -35px; text-align: right;">[<a href="/sparql?query=PREFIX+meannot%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fannotations%2F%3E%0D%0APREFIX+sioc%3A+%3Chttp%3A%2F%2Frdfs.org%2Fsioc%2Fns%23%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0APREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0ASELECT+%3Fannotator_name%0D%0AWHERE+%7B%0D%0A++%3Fcomment+mebase%3Aannotates+%3C<?= urlencode($datauri) ?>workflows%2F52%3E+.%0D%0A++%3Fcomment+rdf%3Atype+meannot%3AComment+.%0D%0A++%3Fcomment+mebase%3Ahas-annotator+%3Fannotator%0D%0A++%3Fannotator+sioc%3Aname+%3Fannotator_name%0D%0A%7D&amp;formatting=HTML Table">Run <font style="font-size: 0.6em;">(Without DISTINCT)</font></a>]
[<a href="/sparql?query=PREFIX+meannot%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fannotations%2F%3E%0D%0APREFIX+sioc%3A+%3Chttp%3A%2F%2Frdfs.org%2Fsioc%2Fns%23%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0APREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0ASELECT+DISTINCT+%3Fannotator_name%0D%0AWHERE+%7B%0D%0A++%3Fcomment+mebase%3Aannotates+%3C<?= urlencode($datauri) ?>workflows%2F52%3E+.%0D%0A++%3Fcomment+rdf%3Atype+meannot%3AComment+.%0D%0A++%3Fcomment+mebase%3Ahas-annotator+%3Fannotator%0D%0A++%3Fannotator+sioc%3Aname+%3Fannotator_name%0D%0A%7D&amp;formatting=HTML Table">Run <font style="font-size: 0.6em;">(With DISTINCT)</font></a>]<br/><span id="results3_show" onclick="showResults('results3');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results3_hide" onclick="hideResults('results3');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results3" style="clear: both; position: relative; top: -26px;">
  <table style="margin-left: auto; margin-right: auto;"><tr><td style="vertical-align: top;">
    <h4 style="text-align: center; padding-bottom: 5px;">Without DISTINCT</h4>
    <table class="listing">
      <tr><th>annotator_name</th></tr>
      <tr><td>Don Cruickshank</td></tr>
      <tr><td class="shade">Franck Tanoh</td></tr>
      <tr><td>Franck Tanoh</td></tr>
      <tr><td class="shade">Franck Tanoh</td></tr>
    </table>
  </td>
  <td>&nbsp;&nbsp;&nbsp;&nbsp;</td>
  <td style="vertical-align: top;">
    <h4 style="text-align: center; padding-bottom: 5px;">With DISTINCT</h4>
    <table class="listing">
      <tr><th>annotator_name</th></tr>
      <tr><td>Don Cruickshank</td></tr>
      <tr><td class="shade">Franck Tanoh</td></tr>
    </table>
  </td></tr></table>
</div>
<br/>
<h3><a name="COUNT"/>3.2. COUNT</h3>
<p style="clear: both;">As of <a href="http://www.w3.org/TR/sparql11-query/" title="SPARQL 1.1 Working Draft">SPARQL 1.1</a> it has been possible to apply mathematical functions to selected variables.  The most straightforward of these is COUNT.  The example below is a way of founding out how many public workflows myExperiment has without requiring any post-processing.</P>

<div class="yellow"><pre>PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;
PREFIX mecontrib: &lt;http://rdf.myexperiment.org/ontologies/contributions/&gt;

SELECT <b>(COUNT(?workflow) AS ?no_workflows)</b>
WHERE {
  ?workflow rdf:type mecontrib:Workflow
}</pre><div style="text-align: right; float: right; position: relative; top: -35px;">[<a href="/sparql?query=PREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0APREFIX+mecontrib%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fcontributions%2F%3E%0D%0A%0D%0ASELECT+%28COUNT%28%3Fworkflow%29+AS+%3Fno_workflows%29%0D%0AWHERE+%7B%0D%0A++%3Fworkflow+rdf%3Atype+mecontrib%3AWorkflow%0D%0A%7D%0D%0A&amp;formatting=HTML Table">Run</a>]<br/><span id="results4_show" onclick="showResults('results4');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results4_hide" onclick="hideResults('results4');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results4" style="clear: both; position: relative; top: -26px;">
  <table class="listing">
    <tr><th>no_workflows</th></tr>
    <tr><td>1255</td></tr>
  </table>
</div>
<br/>
<h3><a name="SUM"/>3.3. SUM</h3>
<p style="clear: both;">Another common mathematical function now available is SUM, which like COUNT works in the same way as it does in SQL.  The following example gives a sum of the downloads of all workflows owned by the user with the ID 43.</p>
<div class="yellow"><pre>BASE &lt;http://www.myexperiment.org/&gt;
PREFIX mevd: &lt;http://rdf.myexperiment.org/ontologies/viewings_downloads/&gt;
PREFIX sioc: &lt;http://rdfs.org/sioc/ns#&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;
PREFIX mecontrib: &lt;http://rdf.myexperiment.org/ontologies/contributions/&gt;

SELECT <b>(SUM(?downloaded) AS ?total_downloaded)</b>
WHERE {
  ?workflow rdf:type mecontrib:Workflow .
  ?workflow sioc:has_owner &lt;users/43&gt; .
  ?workflow mevd:downloaded ?downloaded
}</pre><div style="text-align: right; float: right; position: relative; top: -35px;">[<a href="/sparql?query=BASE+%3Chttp%3A%2F%2Fwww.myexperiment.org%2F%3E%0D%0APREFIX+mevd%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fviewings_downloads%2F%3E%0D%0APREFIX+sioc%3A+%3Chttp%3A%2F%2Frdfs.org%2Fsioc%2Fns%23%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0APREFIX+mecontrib%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fcontributions%2F%3E%0D%0A%0D%0ASELECT+%28SUM%28%3Fdownloaded%29+AS+%3Ftotal_downloaded%29%0D%0AWHERE+%7B%0D%0A++%3Fworkflow+rdf%3Atype+mecontrib%3AWorkflow+.%0D%0A++%3Fworkflow+sioc%3Ahas_owner+%3Cusers%2F43%3E+.%0D%0A++%3Fworkflow+mevd%3Adownloaded+%3Fdownloaded%0D%0A%7D%0D%0A&amp;formatting=HTML Table">Run</a>]<br/><span id="results5_show" onclick="showResults('results5');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results5_hide" onclick="hideResults('results5');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results5" style="clear: both; position: relative; top: -26px;">
  <table class="listing">
    <tr><th>total_downloaded</th></tr>
    <tr><td>29382</td></tr>
  </table>
</div>
<br/>
<h3><a name="AVG"/>3.4. AVG</h3>
<p style="clear: both;">The SUM example above is probably not that useful as it will vary depending on the number of workflows owned by the user.  An average for the number of downloads can be found using the AVG function.</p>
<div class="yellow"><pre>BASE &lt;http://www.myexperiment.org/&gt;
PREFIX mevd: &lt;http://rdf.myexperiment.org/ontologies/viewings_downloads/&gt;
PREFIX sioc: &lt;http://rdfs.org/sioc/ns#&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;
PREFIX mecontrib: &lt;http://rdf.myexperiment.org/ontologies/contributions/&gt;

SELECT <b>(AVG(?downloaded) AS ?average_downloads)</b>
WHERE {
  ?workflow rdf:type mecontrib:Workflow .
  ?workflow sioc:has_owner &lt;users/43&gt; .
  ?workflow mevd:downloaded ?downloaded
}</pre><div style="text-align: right; float: right; position: relative; top: -35px;">[<a href="/sparql?query=BASE+%3Chttp%3A%2F%2Fwww.myexperiment.org%2F%3E%0D%0APREFIX+mevd%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fviewings_downloads%2F%3E%0D%0APREFIX+sioc%3A+%3Chttp%3A%2F%2Frdfs.org%2Fsioc%2Fns%23%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0APREFIX+mecontrib%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fcontributions%2F%3E%0D%0A%0D%0ASELECT+%28AVG%28%3Fdownloaded%29+AS+%3Faverage_downloads%29%0D%0AWHERE+%7B%0D%0A++%3Fworkflow+rdf%3Atype+mecontrib%3AWorkflow+.%0D%0A++%3Fworkflow+sioc%3Ahas_owner+%3Cusers%2F43%3E+.%0D%0A++%3Fworkflow+mevd%3Adownloaded+%3Fdownloaded%0D%0A%7D%0D%0A&amp;formatting=HTML Table">Run</a>]<br/><span id="results6_show" onclick="showResults('results6');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results6_hide" onclick="hideResults('results6');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results6" style="clear: both; position: relative; top: -26px;">
  <table class="listing">
    <tr><th>average_downloads</th></tr>
    <tr><td>367.2749999999999970618</td></tr>
  </table>
</div>
<br/>
<h3><a name="MAX_MIN"/>3.5. MAX and MIN</h3>
<p style="clear: both;">As well as knowing the average you might want to know the most and least times a workflow that belongs to a user has been downloaded. This can be achieved used the MAX and MIN function.</p>
<div class="yellow"><pre>BASE &lt;http://www.myexperiment.org/&gt;
PREFIX mevd: &lt;http://rdf.myexperiment.org/ontologies/viewings_downloads/&gt;
PREFIX sioc: &lt;http://rdfs.org/sioc/ns#&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;
PREFIX mecontrib: &lt;http://rdf.myexperiment.org/ontologies/contributions/&gt;

SELECT (MAX(?downloaded) AS ?max_downloaded) (MIN(?downloaded) AS ?min_downloaded)
WHERE {
  ?workflow rdf:type mecontrib:Workflow .
  ?workflow sioc:has_owner &lt;users/43&gt; .
  ?workflow mevd:downloaded ?downloaded
}
</pre><div style="text-align: right; float: right; position: relative; top: -35px;">[<a href="/sparql?query=BASE+%3Chttp%3A%2F%2Fwww.myexperiment.org%2F%3E%0D%0APREFIX+mevd%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fviewings_downloads%2F%3E%0D%0APREFIX+sioc%3A+%3Chttp%3A%2F%2Frdfs.org%2Fsioc%2Fns%23%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0APREFIX+mecontrib%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fcontributions%2F%3E%0D%0A%0D%0ASELECT+%28MAX%28%3Fdownloaded%29+AS+%3Fmax_downloaded%29+%28MIN%28%3Fdownloaded%29+AS+%3Fmin_downloaded%29%0D%0AWHERE+%7B%0D%0A++%3Fworkflow+rdf%3Atype+mecontrib%3AWorkflow+.%0D%0A++%3Fworkflow+sioc%3Ahas_owner+%3Cusers%2F43%3E+.%0D%0A++%3Fworkflow+mevd%3Adownloaded+%3Fdownloaded%0D%0A%7D%0D%0A&amp;formatting=HTML Table">Run</a>]<br/><span id="results7_show" onclick="showResults('results7');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results7_hide" onclick="hideResults('results7');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results7" style="clear: both; position: relative; top: -26px;">
  <table class="listing">
    <tr><th>max_downloaded</th><th>min_downloaded</th></tr>
    <tr><td>3250</td><td>1</td></tr>
  </table>
</div>

<p style="clear: both;">One problem with using the MAX and MIN functions is that you cannot easily return the workflow that has been downloaded the most/least.  This can be acheived using two separate query using the <a href="?page=ORDER BY">ORDER BY</a> and <a href="?page=LIMIT">LIMIT</a> clauses discussed later.</p>
<p>These examples of the use of mathematical functions in the SELECT clause are quite basic and return just a single row of results.  The <ahref="?page=GROUP BY">GROUP BY</a> function allows aggregation on a particular entity so you could give a league table of users by the total number of downloads for their workflows.</p>

<script type= "text/javascript"><!-- 
  hideResults('results1');
  hideResults('results2');
  hideResults('results3');
  hideResults('results4');
  hideResults('results5');
  hideResults('results6');
  hideResults('results7');
--></script>

