<?php
	ini_set('include_path','inc/:.');
	$pagetitle="How To SPARQL";
	include('header.inc.php');
	include('settings.inc.php');
?>
<div class="purple" style="float: left; margin-right: 10px; margin-bottom:10px;">
<h3>Contents</h3>
<ol>
  <li><a href="#PREFIX">PREFIX</a></li>
  <li><a href="#SELECT">SELECT</a></li>
  <li><a href="#WHERE">WHERE</a></li>
  <li><a href="#FILTER">FILTER</a></li>
    <ol>
      <li><a href="#OnText">On Text</a></li>
      <li><a href="#OnNumbers">On Numbers</a></li>
      <li><a href="#OnDates">On Dates</a></li>
    </ol>
  </li>
  <li><a href="#ORDERBY">ORDER BY</a></li>
  <li><a href="#ORDERBY">LIMIT</a></li>
  <li><a href="#UsingSPARQLEndpoint">Using the SPARQL Endpoint</a></li>
  <li><a href="#Troubleshooting">Troubleshooting</a></li>
</ol>
</div>
<p>myExperiment's <a href="sparql">SPARQL Endpoint</a> allows anyone to query all of myExperiment's public data using the query language <a href="http://www.w3.org/TR/rdf-sparql-query/">SPARQL</a>.</p>
<p>Although SPARQL is quite easy to use it may take a while to get you head round it if you are coming to it new and you are not from a computer science background.  This guide is intend help explain the basics of SPARQL and give usable examples that will return actual results from myExperiment's SPARQL Endpoint.</p>
<p>The first six sections explain the main clauses used within SPARQL and how they are used to define queries.</p>
<p>Section 7 explains how to use the SPARQL endpoint and some of the unique features the myExperiment SPARQL enpoint has.  
<p>Section 8 give some explanation of various warning error messages you might be prompted with and how to fix you query to eliminate these.</p>
<div style="clear:both;"/>
<h3>1. PREFIX<a name="PREFIX"/></h3>
<p>The first part of most queries is the listing of one or more prefixes:</p>
<div class="yellow"><pre><b>PREFIX mebase: &lt;<?= $ontopath ?>base/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;</b>

SELECT ?a ?text 
WHERE { 
  ?a rdf:type mebase:Announcement . 
  ?a mebase:text ?text
}</pre><div style="float: right; position: relative; top: -15px;">[<a href="sparql?query=PREFIX+mebase%3A+%3C<?= urlencode($ontopath) ?>base%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0A%0D%0ASELECT+%3Fa+%3Ftext+%0D%0AWHERE+%7B+%0D%0A++%3Fa+rdf%3Atype+mebase%3AAnnouncement+.+%0D%0A++%3Fa+mebase%3Atext+%3Ftext%0D%0A%7D&amp;formatting=In Page">Run</a>]</div></div>
<br/>
<p>Prefixes are not required within a query they just save rewriting the namespace each time you need to use it in a query and it makes the query itself more readable.  The previous query could be re-written as follows if you didn't want to use prefixes:</p>
<div class="yellow"><pre>
SELECT ?a ?text
WHERE {
  ?a <b>&lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#type&gt;</b> <b>&lt;<?= $ontopath ?>base/Announcement&gt;</b> .
  ?a <b>&lt;<?= $ontopath ?>text&gt;</b> ?text
}</pre><div style="float: right; position: relative; top: -15px;">[<a href="sparql?query=SELECT+%3Fa+%3Ftext%0D%0AWHERE+%7B%0D%0A++%3Fa+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23type%3E+%3C<?= urlencode($ontopath) ?>base%2FAnnouncement%3E+.%0D%0A++%3Fa+%3C<?= urlencode($ontopath) ?>base%2Ftext%3E+%3Ftext%0D%0A%7D&amp;formatting=In Page">Run</a>]</div></div>

<h3>2. SELECT<a name="SELECT"/></h3>
<p>After adding your prefixes most SPARQL queries start with a <em>SELECT</em>, although queries can start with ASK, DESCRIBE or CONSTRUCT but these will not be discussed here.  The purpose of the SELECT is very similar to is use in <a href="http://www.w3schools.com/sql/sql_select.asp">SQL</a>, it allows you to define which variables in your query you want values returned for.  Like SQL you can list this individually or use a * to specify you want values return for each variable.  The two queries below are therefore essentially the same and will return the same results:</p>
<div class="yellow"><pre>PREFIX mebase: &lt;<?= $ontopath ?>base/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;

<b>SELECT ?a ?text</b>
WHERE {
  ?a rdf:type mebase:Announcement .
  ?a mebase:text ?text
}</pre><div style="float: right; position: relative; top: -15px;">[<a href="sparql?query=PREFIX+mebase%3A+%3C<?= urlencode($ontopath) ?>base%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0A%0D%0ASELECT+%3Fa+%3Ftext%0D%0AWHERE+%7B%0D%0A++%3Fa+rdf%3Atype+mebase%3AAnnouncement+.%0D%0A++%3Fa+mebase%3Atext+%3Ftext%0D%0A%7D&amp;formatting=In Page">Run</a>]</div></div>
<br/>
<div class="yellow"><pre>PREFIX mebase: &lt;<?= $ontopath ?>base/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;

<b>SELECT *</b>
WHERE {
  ?a rdf:type mebase:Announcement .
  ?a mebase:text ?text
}</pre><div style="float: right; position: relative; top: -15px;">[<a href="sparql?query=PREFIX+mebase%3A+%3C<?= urlencode($ontopath) ?>base%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0A%0D%0ASELECT+%2A%0D%0AWHERE+%7B%0D%0A++%3Fa+rdf%3Atype+mebase%3AAnnouncement+.%0D%0A++%3Fa+mebase%3Atext+%3Ftext%0D%0A%7D&amp;formatting=In Page">Run</a>]</div></div>

<br/>
<p>It is not uncommon that the sets of results will return duplicates.  If you don't want duplicates you can append <em>DISTINCT</em> after the SELECT command.

<div class="yellow"><pre>PREFIX meannot: &lt;<?= $ontopath ?>annotations/&gt;
PREFIX sioc: &lt;http://rdfs.org/sioc/ns#&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;
PREFIX mebase: &lt;<?= $ontopath ?>base/&gt;
SELECT <b>DISTINCT</b> ?annotator_name
WHERE {
  ?comment mebase:annotates &lt;<?= $datauri ?>Workflow/52&gt; .
  ?comment rdf:type meannot:Comment .
  ?comment mebase:has-annotator ?annotator
  ?annotator sioc:name ?annotator_name
}
</pre><div style="float: right; position: relative; top: -15px;">[<a href="sparql?query=PREFIX+meannot%3A+%3C<?= urlencode($ontopath) ?>annotations%2F%3E%0D%0APREFIX+sioc%3A+%3Chttp%3A%2F%2Frdfs.org%2Fsioc%2Fns%23%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0APREFIX+mebase%3A+%3C<?= urlencode($ontopath) ?>base%2F%3E%0D%0ASELECT+%3Fannotator_name%0D%0AWHERE+%7B%0D%0A++%3Fcomment+mebase%3Aannotates+%3C<?= urlencode($datauri) ?>Workflow%2F52%3E+.%0D%0A++%3Fcomment+rdf%3Atype+meannot%3AComment+.%0D%0A++%3Fcomment+mebase%3Ahas-annotator+%3Fannotator%0D%0A++%3Fannotator+sioc%3Aname+%3Fannotator_name%0D%0A%7D&amp;formatting=In Page">Run <font style="font-size: 0.6em;">(Without DISTINCT)</font></a>] 
[<a href="sparql?query=PREFIX+meannot%3A+%3C<?= urlencode($ontopath) ?>annotations%2F%3E%0D%0APREFIX+sioc%3A+%3Chttp%3A%2F%2Frdfs.org%2Fsioc%2Fns%23%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0APREFIX+mebase%3A+%3C<?= urlencode($ontopath) ?>base%2F%3E%0D%0ASELECT+DISTINCT+%3Fannotator_name%0D%0AWHERE+%7B%0D%0A++%3Fcomment+mebase%3Aannotates+%3C<?= urlencode($datauri) ?>Workflow%2F52%3E+.%0D%0A++%3Fcomment+rdf%3Atype+meannot%3AComment+.%0D%0A++%3Fcomment+mebase%3Ahas-annotator+%3Fannotator%0D%0A++%3Fannotator+sioc%3Aname+%3Fannotator_name%0D%0A%7D&amp;formatting=On Page">Run <font style="font-size: 0.6em;">(With DISTINCT)</font></a>]</div></div>

<h3>3. WHERE<a name="WHERE"/></h3>
<p>The <em>WHERE</em> clause of a SPARQL query defines where you want to find values for the variables you have define in the SELECT clause.  Inside the curly brackets {} the basic unit is a triple, this is made up of three components, a subject, a predicate and an object.  This is much like the grammatical structure of a basic natural language sentence.</p>
<table style="margin-left: auto; margin-right: auto; width: 400px;">
  <tr><td style="text-align: center; font-weight: bold; color: red;">The &nbsp; &nbsp;boy</td><td style="text-align: center; font-weight: bold; color: blue;">catches</td><td style="text-align: center; font-weight: bold; color: green;">a &nbsp; &nbsp; ball</td></tr>
  <tr><td style="text-align: center; color: red;">Subject</td><td style="color: blue; text-align: center;">Predicate</td><td style="color: green; text-align: center;">Object </td></tr>
</table>
<p> In SPARQL the subject, predicate or object can take one of two forms a variable which is defined by putting a ? prior to the variable name, (e.g. ?a, ?text, etc.) or a Universal Resource Identifier (URI). As discussed in <a href="#PREFIX">PREFIX</a> this can take one of two forms depending on whether a prefix for the namespace of the URI has been defined within the query. These triples can then be concatenate together using a full-stop (.).  By doing this you build an interconnected graph of nodes joined by relationships.  It is generally good ensure that each triple connects at some point in the graph.  The first of the following query is a completely connected graph where as the second isn't and therefore will return the superset of all possible homepage/mbox combinations.  This such a high number of results that there is intentionally not a link to run this query as PHP with run out of memory trying to process all the results.</p> 
<div class="yellow"><pre>PREFIX foaf: &lt;http://xmlns.com/foaf/0.1/&gt;
PREFIX mebase: &lt;<?= $ontopath ?>base/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;

SELECT ?homepage ?mbox
WHERE {
  ?x rdf:type mebase:User .
  ?x foaf:homepage ?homepage .
  <b>?x</b> foaf:mbox ?mbox
}
</pre><div style="float: right; position: relative; top: -15px;">[<a href="sparql?query=PREFIX+foaf%3A+%3Chttp%3A%2F%2Fxmlns.com%2Ffoaf%2F0.1%2F%3E%0D%0APREFIX+mebase%3A+%3C<?= urlencode($ontopath) ?>base%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0A%0D%0ASELECT+%3Fhomepage+%3Fmbox%0D%0AWHERE+%7B%0D%0A++%3Fx+rdf%3Atype+mebase%3AUser+.%0D%0A++%3Fx+foaf%3Ahomepage+%3Fhomepage+.%0D%0A++%3Fx+foaf%3Ambox+%3Fmbox%0D%0A%7D%0D%0A&amp;formatting=In Page">Run</a>]</div></div>
<br/>
<div class="yellow"><pre>PREFIX foaf: &lt;http://xmlns.com/foaf/0.1/&gt;
PREFIX mebase: &lt;<?= $ontopath ?>base/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;

SELECT ?homepage ?mbox
WHERE {
  ?x rdf:type mebase:User .
  ?x foaf:homepage ?homepage .
  <b>?y</b> foaf:mbox ?mbox
}</pre></div>
<br/>
<p>As like the query above it is not unusual to have the same subject multiple times over. A semi-colon (;) rather than a full-stop (.) can be used after each triple to replace the subject for the next triple if it is the same, leaving you only needing to define the predicate and object. This is useful because it can help reduce the chances of you making a typo like that previously described.  The query below is the same as the previous query but uses this shorthand syntax:</p>

<div class="yellow"><pre>PREFIX foaf: &lt;http://xmlns.com/foaf/0.1/&gt;
PREFIX mebase: &lt;<?= $ontopath ?>base/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;

SELECT ?homepage ?mbox
WHERE {
  ?x rdf:type mebase:User <b>;
    foaf:homepage ?homepage ;
    foaf:mbox ?mbox</b>
}</pre><div style="float: right; position: relative; top: -15px;">[<a href="sparql?query=PREFIX+foaf%3A+%3Chttp%3A%2F%2Fxmlns.com%2Ffoaf%2F0.1%2F%3E%0D%0APREFIX+mebase%3A+%3C<?= urlencode($ontopath) ?>base%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0A%0D%0ASELECT+%3Fhomepage+%3Fmbox%0D%0AWHERE+%7B%0D%0A++%3Fx+rdf%3Atype+mebase%3AUser+%3B%0D%0A++++foaf%3Ahomepage+%3Fhomepage+%3B%0D%0A++++foaf%3Ambox+%3Fmbox%0D%0A%7D%0D%0A&amp;formatting=In Page">Run</a>]</div></div> 

<h3>4. FILTER<a name="FILTER"/></h3>
The FILTER clause is use within the curly parenthesis as a subclause of the WHERE clause.  As its name suggest it allows you to filter out the results based on certain conditions.
<h4>4.1. On Text<a name="OnText"/></h4>
<h4>4.2. On Numbers<a name="OnNumbers"/></h4>
<h4>4.3. On Dates<a name="OnDates"/></h4>

<h3>5. ORDER BY<a name="ORDERBY"/></h3>

<h3>6. LIMIT<a name="LIMIT"/></h3>

<h3>7. Using the SPARQL Endpoint<a name="UsingSPARQLEndpoint"/></h3>

<h3>8. Troubleshooting<a name="Troubleshooting"/></h3>



<?php include('footer.inc.php'); ?>
