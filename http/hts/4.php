<h2>4. WHERE</h2>
<p>The <em>WHERE</em> clause of a SPARQL query defines where you want to find values for the variables you have defined in the SELECT clause.  Inside the curly parenthesis {} the basic unit is a triple, this is made up of three components, a subject, a predicate and an object.  This is much like the grammatical structure of a basic natural language sentence.</p>
<table style="margin-left: auto; margin-right: auto; width: 400px;">
  <tr><td style="text-align: center; font-weight: bold; color: red;">The &nbsp; &nbsp;boy</td><td style="text-align: center; font-weight: bold; color: blue;">catches</td><td style="text-align: center; font-weight: bold; color: green;">a &nbsp; &nbsp; ball</td></tr>
  <tr><td style="text-align: center; color: red;">Subject</td><td style="color: blue; text-align: center;">Predicate</td><td style="color: green; text-align: center;">Object </td></tr>
</table>
<p> In SPARQL the subject, predicate or object can take one of two forms a variable which is defined by putting a ? prior to the variable name, (e.g. ?a, ?text, etc.) or a Universal Resource Identifier (URI). As discussed in <a href="?page=PREFIX (and BASE)">PREFIX (and BASE)</a> this can take one of two forms depending on whether a prefix for the namespace of the URI has been defined within the query. These triples can then be concatenated together using a full-stop (.).  By doing this you can build an interconnected graph of nodes joined by relationships.  It is generally good ensure that each triple connects at some point in the graph.  The first of the following query is a completely connected graph where as the second isn't and therefore will return the superset of all possible homepage/mbox combinations.  This is such a high number of results that there is intentionally not a link to run this query as the webserver with run out of memory trying to process all the results.</p>
<div class="yellow"><pre>PREFIX foaf: &lt;http://xmlns.com/foaf/0.1/&gt;
PREFIX mebase: &lt;http://rdf.myexperiment.org/ontologies/base/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;

SELECT ?homepage ?mbox
WHERE {
  ?x rdf:type mebase:User .
  ?x foaf:homepage ?homepage .
  <b>?x</b> foaf:mbox ?mbox
}
</pre><div style="float: right; position: relative; top: -35px; text-align: right;">[<a href="sparql?query=PREFIX+foaf%3A+%3Chttp%3A%2F%2Fxmlns.com%2Ffoaf%2F0.1%2F%3E%0D%0APREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0A%0D%0ASELECT+%3Fhomepage+%3Fmbox%0D%0AWHERE+%7B%0D%0A++%3Fx+rdf%3Atype+mebase%3AUser+.%0D%0A++%3Fx+foaf%3Ahomepage+%3Fhomepage+.%0D%0A++%3Fx+foaf%3Ambox+%3Fmbox%0D%0A%7D%0D%0A&amp;formatting=HTML Table">Run</a>]<br/><span id="results1_show" onclick="showResults('results1');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results1_hide" onclick="hideResults('results1');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results1">
<?php include('hts/email_homepage.inc.php'); ?>
</div>
<br/>
<div class="yellow"><pre>PREFIX foaf: &lt;http://xmlns.com/foaf/0.1/&gt;
PREFIX mebase: &lt;http://rdf.myexperiment.org/ontologies/base/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;

SELECT ?homepage ?mbox
WHERE {
  ?x rdf:type mebase:User .
  ?x foaf:homepage ?homepage .
  <b>?y</b> foaf:mbox ?mbox
}</pre></div>
<br/>
<p>As like the query above it is not unusual to have the same subject multiple times over. A semi-colon (;) rather than a full-stop (.) can be used after each triple to replace the subject for the next triple if it is the same, leaving you only needing to define the predicate and object. This is useful because it helps reduce the chances typos like the one previously described.  The query below is the same as the previous query but uses this shorthand syntax:</p>

<div class="yellow"><pre>PREFIX foaf: &lt;http://xmlns.com/foaf/0.1/&gt;
PREFIX mebase: &lt;http://rdf.myexperiment.org/ontologies/base/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;

SELECT ?homepage ?mbox
WHERE {
  ?x rdf:type mebase:User <b>;
    foaf:homepage ?homepage ;
    foaf:mbox ?mbox</b>
}</pre><div style="float: right; position: relative; top: -35px; text-align: right;">[<a href="sparql?query=PREFIX+foaf%3A+%3Chttp%3A%2F%2Fxmlns.com%2Ffoaf%2F0.1%2F%3E%0D%0APREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0A%0D%0ASELECT+%3Fhomepage+%3Fmbox%0D%0AWHERE+%7B%0D%0A++%3Fx+rdf%3Atype+mebase%3AUser+%3B%0D%0A++++foaf%3Ahomepage+%3Fhomepage+%3B%0D%0A++++foaf%3Ambox+%3Fmbox%0D%0A%7D%0D%0A&amp;formatting=HTML Table">Run</a>]<br/><span id="results2_show" onclick="showResults('results2');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results2_hide" onclick="hideResults('results2');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results2">
<?php include('hts/email_homepage.inc.php'); ?>
</div>
<h3>4.1. UNION<a name="UNION"/></h3>
<p>The <em>UNION</em> clause allows you to return results where you want to match multiple patterns.  An example of this may be returning all comments and ratings for a particular workflow:</p>
<div class="yellow"><pre>BASE &lt;<?= $datauri ?>&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;
PREFIX meannot: &lt;http://rdf.myexperiment.org/ontologies/annotations/&gt;
PREFIX mebase: &lt;http://rdf.myexperiment.org/ontologies/base/&gt;
SELECT ?annotation ?annotator
WHERE{
  ?annotation mebase:annotates &lt;workflows/72&gt; .
  <b>{ ?annotation rdf:type meannot:Comment } UNION { ?annotation rdf:type meannot:Rating }</b> .
  ?annotation mebase:has-annotator ?annotator
}</pre>
<div style="float: right; position: relative; top: -35px; text-align: right;">[<a href="sparql?query=BASE+%3C<?= urlencode($datauri) ?>%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0APREFIX+meannot%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fannotations%2F%3E%0D%0APREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0ASELECT+%3Fannotation+%3Fannotator%0D%0AWHERE%7B%0D%0A++%3Fannotation+mebase%3Aannotates+%3Cworkflows%2F72%3E+.+%0D%0A++%7B+%3Fannotation+rdf%3Atype+meannot%3AComment+%7D+UNION+%7B+%3Fannotation+rdf%3Atype+meannot%3ARating+%7D+.%0D%0A++%3Fannotation+mebase%3Ahas-annotator+%3Fannotator%0D%0A%7D&amp;formatting=HTML Table">Run</a>]<br/><span id="results3_show" onclick="showResults('results3');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results3_hide" onclick="hideResults('results3');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results3">
<table class="listing">
  <tr><th>annotation</th><th>annotator</th></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/72/comments/33</td><td class="shade"><?= $datauri ?>users/18</td></tr>
  <tr><td><?= $datauri ?>workflows/72/ratings/63</td><td><?= $datauri ?>users/283</td></tr>
  <tr><td class="shade"><?= $datauri ?>workflows/72/comments/163</td><td class="shade"><?= $datauri ?>users/18</td></tr>
  <tr><td><?= $datauri ?>workflows/72/ratings/132</td><td><?= $datauri ?>users/690</td></tr>
</table>
</div>
<h3>4.2. OPTIONAL<a name="OPTIONAL"/></h3>
<p>There may be ocassions where you want to include additional variables in your search but you still want to return results if there are not values for these variables.  An example might be that you want to return the name, homepage and email address for users.  Some users may have not set a homepage and/or email address but you still want to know their name:</p>
<div class="yellow"><pre>PREFIX foaf: &lt;http://xmlns.com/foaf/0.1/&gt;
PREFIX sioc: &lt;http://rdfs.org/sioc/ns#&gt;
PREFIX mebase: &lt;http://rdf.myexperiment.org/ontologies/base/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;
SELECT ?name ?homepage ?email
WHERE{
  ?user rdf:type mebase:User ;
    sioc:name ?name .
    <b>OPTIONAL { ?user foaf:homepage ?homepage } .
    OPTIONAL { ?user foaf:mbox ?email }</b>
}</pre>
<div style="float: right; position: relative; top: -35px; text-align: right;">[<a href="sparql?query=PREFIX+foaf%3A+%3Chttp%3A%2F%2Fxmlns.com%2Ffoaf%2F0.1%2F%3E%0D%0APREFIX+sioc%3A+%3Chttp%3A%2F%2Frdfs.org%2Fsioc%2Fns%23%3E%0D%0APREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0ASELECT+%3Fname+%3Fhomepage+%3Femail%0D%0AWHERE%7B%0D%0A++%3Fuser+rdf%3Atype+mebase%3AUser+%3B%0D%0A++++sioc%3Aname+%3Fname+.%0D%0A++++OPTIONAL+%7B+%3Fuser+foaf%3Ahomepage+%3Fhomepage+%7D+.%0D%0A++++OPTIONAL+%7B+%3Fuser+foaf%3Ambox+%3Femail+%7D%0D%0A%7D&amp;formatting=HTML Table">Run</a>]<br/><span id="results4_show" onclick="showResults('results4');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results4_hide" onclick="hideResults('results4');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results4">
<table class="listing">
  <tr><th>name</th><th>homepage</th><th>email</th></tr>
  <tr><td class="shade">Workflowxdong</td><td class="shade"></td><td class="shade"></td></tr>

  <tr><td>Piculin</td><td></td><td></td></tr>
  <tr><td class="shade">Daniel Kornev</td><td class="shade">http://blogs.msdn.com/semantics/</td><td class="shade">mailto:daniel.kornev@microsoft.com</td></tr>
  <tr><td>Ramesh kuc</td><td></td><td></td></tr>
  <tr><td class="shade">John locke</td><td class="shade"></td><td class="shade"></td></tr>
  <tr><td>Nikolas</td><td></td><td></td></tr>

  <tr><td class="shade">Nlynch</td><td class="shade"></td><td class="shade"></td></tr>
  <tr><td>kondas</td><td></td><td></td></tr>
  <tr><td class="shade">Jos?? Manuel Rodr??guez</td><td class="shade">http://www.inab.org</td><td class="shade">mailto:jmrodriguez@cnio.es</td></tr>
  <tr><td>Babo1ug</td><td></td><td></td></tr>
  <tr><td class="shade">Funkyd101</td><td class="shade"></td><td class="shade">mailto:d.woodhead@gmail.com</td></tr>

  <tr><td>Hong harper</td><td></td><td>mailto:ihatewinter03@yahoo.com</td></tr>
</table>
</div>

<script type= "text/javascript"><!-- 
  hideResults('results1');
  hideResults('results2');
  hideResults('results3');
  hideResults('results4');
--></script>

