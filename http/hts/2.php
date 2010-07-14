<h2>2. PREFIX</h2>
<p>The first part of most queries is the listing of one or more prefixes:</p>
<div class="yellow" ><pre><b>PREFIX mebase: &lt;http://rdf.myexperiment.org/ontologies/base/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;</b>

SELECT ?a ?text
WHERE {
  ?a rdf:type mebase:Announcement .
  ?a mebase:text ?text
}</pre><div style="text-align: right; float: right; position: relative; top: -35px;"><span>[<a href="/sparql?query=PREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0A%0D%0ASELECT+%3Fa+%3Ftext+%0D%0AWHERE+%7B+%0D%0A++%3Fa+rdf%3Atype+mebase%3AAnnouncement+.+%0D%0A++%3Fa+mebase%3Atext+%3Ftext%0D%0A%7D&amp;formatting=HTML Table">Run</a>]<br/><span id="results1_show" onclick="showResults('results1');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results1_hide" onclick="hideResults('results1');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results1">
  <?php include('hts/announcements.inc.php'); ?>
</div>
<div style="clear:both;"/>
<p>Prefixes are not required within a query they just save rewriting the namespace each time you need to use it in a query and it makes the query easier to read.  The previous query could be re-written as follows if you didn't want to use prefixes:</p>
<div class="yellow"><pre>SELECT ?a ?text
WHERE {
  ?a <b>&lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#type&gt;</b> <b>&lt;http://rdf.myexperiment.org/ontologies/base/Announcement&gt;</b> .
  ?a <b>&lt;http://rdf.myexperiment.org/ontologies/base/text&gt;</b> ?text
}

</pre><div style="text-align: right; float: right; position: relative; top: -35px;">[<a href="/sparql?query=SELECT+%3Fa+%3Ftext%0D%0AWHERE+%7B%0D%0A++%3Fa+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23type%3E+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2FAnnouncement%3E+.%0D%0A++%3Fa+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2Ftext%3E+%3Ftext%0D%0A%7D&amp;formatting=HTML Table">Run</a>]<br/><span id="results2_show" onclick="showResults('results2');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results2_hide" onclick="hideResults('results2');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results2" style="clear: both; position: relative; top: -26px;">
  <?php include('hts/announcements.inc.php'); ?>
</div>

<h3><a name="BASE"/>2.1. BASE</h3>
<p>The <em>BASE</em> clause is similar to the PREFIX clause but a name is not required, just the domain path need be defined.  If you wish to use the BASE clause it must proceed any PREFIX clauses.  A common use of the BASE clause is to define the path for data that is being queried. E.g.
<div class="yellow"><pre>SELECT ?p ?o
WHERE{
  &lt;<b><?= $datauri ?></b>workflows/12&gt; ?p ?o
}</pre><div style="text-align: right; float: right; position: relative; top: -35px;">[<a href="/sparql?query=SELECT+%3Fp+%3Fo%0D%0AWHERE%7B%0D%0A++%3C<?= urlencode($datauri) ?>workflows%2F12%3E+%3Fp+%3Fo%0D%0A%7D&amp;formatting=HTML Table">Run</a>]<br/><span id="results3_show" onclick="showResults('results3');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results3_hide" onclick="hideResults('results3');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results3" style="clear: both; position: relative; top: -26px;">
  <?php include('hts/workflow12.inc.php'); ?>
</div>
<div style="clear:both"/>
<p>Could be rewritten:</p>
<div class="yellow"><pre><b>BASE &lt;<?= $datauri ?>&gt;</b>
SELECT ?p ?o
WHERE{
  &lt;workflows/12&gt; ?p ?o
}</pre><div style="text-align: right; float: right; position: relative; top: -35px;">[<a href="/sparql?query=BASE+%3C<?= urlencode($datauri) ?>%3E%0D%0ASELECT+%3Fp+%3Fo%0D%0AWHERE%7B%0D%0A++%3Cworkflows%2F12%3E+%3Fp+%3Fo%0D%0A%7D&amp;formatting=HTML Table">Run</a>]<br/><span id="results4_show" onclick="showResults('results4');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results4_hide" onclick="hideResults('results4');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results4" style="clear: both; position: relative; top: -26px;">
  <?php include('hts/workflow12.inc.php'); ?>
</div>

<script type= "text/javascript"><!-- 
  hideResults('results1');
  hideResults('results2');
  hideResults('results3');
  hideResults('results4');
--></script>

