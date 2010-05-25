<h2>3. SELECT</h2>
<p>After adding your prefixes most SPARQL queries start with a <em>SELECT</em>, although queries can start with ASK, DESCRIBE or CONSTRUCT but these will not be discussed here.  The purpose of the SELECT is very similar to it's use in <a href="http://www.w3schools.com/sql/sql_select.asp">SQL</a>. It allows you to define which variables in your query you want values returned for.  Like SQL you can list these individually or use an asterisk (*) to specify you want values return for each variable.  E.g.</p>
<div class="yellow"><pre>PREFIX mebase: &lt;http://rdf.myexperiment.org/ontologies/base/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;

<b>SELECT ?a ?text</b>
WHERE {
  ?a rdf:type mebase:Announcement .
  ?a mebase:text ?text
}</pre><div style="text-align: right; float: right; position: relative; top: -35px;">[<a href="sparql?query=PREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0A%0D%0ASELECT+%3Fa+%3Ftext%0D%0AWHERE+%7B%0D%0A++%3Fa+rdf%3Atype+mebase%3AAnnouncement+.%0D%0A++%3Fa+mebase%3Atext+%3Ftext%0D%0A%7D&amp;formatting=HTML Table">Run</a>]<br/><span id="results1_show" onclick="showResults('results1');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results1_hide" onclick="hideResults('results1');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
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
}</pre><div style="text-align: right; float: right; position: relative; top: -35px;">[<a href="sparql?query=PREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0A%0D%0ASELECT+%2A%0D%0AWHERE+%7B%0D%0A++%3Fa+rdf%3Atype+mebase%3AAnnouncement+.%0D%0A++%3Fa+mebase%3Atext+%3Ftext%0D%0A%7D&amp;formatting=HTML Table">Run</a>]<br/><span id="results2_show" onclick="showResults('results2');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results2_hide" onclick="hideResults('results2');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results2">
  <?php include('hts/announcements.inc.php'); ?>
</div>

<br/>
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
</pre><div style="float: right; position: relative; top: -35px; text-align: right;">[<a href="sparql?query=PREFIX+meannot%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fannotations%2F%3E%0D%0APREFIX+sioc%3A+%3Chttp%3A%2F%2Frdfs.org%2Fsioc%2Fns%23%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0APREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0ASELECT+%3Fannotator_name%0D%0AWHERE+%7B%0D%0A++%3Fcomment+mebase%3Aannotates+%3C<?= urlencode($datauri) ?>workflows%2F52%3E+.%0D%0A++%3Fcomment+rdf%3Atype+meannot%3AComment+.%0D%0A++%3Fcomment+mebase%3Ahas-annotator+%3Fannotator%0D%0A++%3Fannotator+sioc%3Aname+%3Fannotator_name%0D%0A%7D&amp;formatting=HTML Table">Run <font style="font-size: 0.6em;">(Without DISTINCT)</font></a>]
[<a href="sparql?query=PREFIX+meannot%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fannotations%2F%3E%0D%0APREFIX+sioc%3A+%3Chttp%3A%2F%2Frdfs.org%2Fsioc%2Fns%23%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0APREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0ASELECT+DISTINCT+%3Fannotator_name%0D%0AWHERE+%7B%0D%0A++%3Fcomment+mebase%3Aannotates+%3C<?= urlencode($datauri) ?>workflows%2F52%3E+.%0D%0A++%3Fcomment+rdf%3Atype+meannot%3AComment+.%0D%0A++%3Fcomment+mebase%3Ahas-annotator+%3Fannotator%0D%0A++%3Fannotator+sioc%3Aname+%3Fannotator_name%0D%0A%7D&amp;formatting=On Page">Run <font style="font-size: 0.6em;">(With DISTINCT)</font></a>]<br/><span id="results3_show" onclick="showResults('results3');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results3_hide" onclick="hideResults('results3');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
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

<script type= "text/javascript"><!-- 
  hideResults('results1');
  hideResults('results2');
  hideResults('results3');
--></script>

