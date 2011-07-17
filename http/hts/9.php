<h2>9. Troubleshooting</h2>
<p>When trying to execute a query you may get one or more warning/error messages.  If you are using the query form with <em>HTML Table</em> results these messages will be shown in a red box just above the query text box. E.g.</p>
<div class="red" style="text-align: center;"><b> parser warning: Variable q was selected but is unused in the query. at line 1 </b></div>
<br/>
<p>If you request raw SPARQL results XML these messages will appear as within a comment tag <code><small>&lt;!-- --&gt;</small></code> in the XML itself.  Usually between the <em>head</em> and <em>results</em> tags.</p>
<div class="yellow"><pre>&lt;?xml version="1.0"?&gt;
&lt;sparql xmlns="http://www.w3.org/2005/sparql-results#"&gt;
  &lt;head&gt;
    &lt;variable name="q"/&gt;
  &lt;/head&gt;
<b>&lt;!-- parser warning: Variable q was selected but is unused in the query. at line 1 --&gt;</b>
  &lt;results&gt;
  &lt;/results&gt;
&lt;/sparql&gt;</pre></div>
<br/>
<p>Generally these messages are fairly self explanatory but here are some tips for how you might go about resolving them.</p>
<h3><a name="Syntax Errors"/>8.1. Syntax Errors</h3>
<p>Error messages are generally syntactical errors in the query itself.  Commonly this is because the the clauses have not be defined in the right order.  Thtype of error message you receive may look something like this:</p>
<div class="red" style="text-align: center;"><b> parser error: syntax error, unexpected WHERE, expecting AS at line 6 </b></div>
<br/>
<p>Below is a rough guide for how the clauses that have been described in this tutorial should be ordered. (Other ordering may work):
<div class="yellow"><pre>BASE
PREFIX
SELECT 
WHERE {
  UNION / OPTIONAL
  FILTER 
}
GROUP BY
ORDER BY
LIMIT
OFFSET</pre></div>
<p>A second common syntactical error is to fail to put a dot between triples in the WHERE clause.  This is why it is often good practice to start a new line for each triple and ident appropriately when using the semi-colon (;) operator. E.g.</p>
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
  FILTER (?score &gt;= 4)
}</pre>
</div>
<p>SPARQL queries often use several levels of parentheses either curly parentheses {} in WHERE, OPTIONAL and UNION clauses or round parentheses () in FILTER clauses.  If you get an error message like the one below you should check that all your parentheses pair up.
<div class="red" style="text-align: center;"><b> parser error: syntax error, unexpected $end, expecting '}' at line 11 </b></div>

<h3><a name="Complexity Warnings"/>8.2. Complexity Warnings</h3>
<p>If you run a query that is fairly complex (e.g. lots of triples, many OPTIONAL / UNION clauses, complicated FILTER clauses, etc.) and is expected to return quite a lot of results, you may receive a complexity warning message like the one below:</p>
<div class="red" style="text-align: center;"><b> warning: hit complexity limit 8 times, increasing soft limit may give more results </b></div>
<p>Usually you will receive a number of results but the query engine cannot guarantee it has returned all of them.  This can usually be resolved by increasing the Soft Limit to 5%, 10%, 20%, etc. until you cease to get this warning message.  Sometimes you may receive a complexity warning because there is a semantic error in your query, such as typo for one of your variable names which means your triples don't link together in the way you intended.</p>

<h3><a name="Parser Warnings"/>8.3. Parser Warnings</h3>
<p>Parser warnings like the one shown at the top of this page, mean that the query was syntactically correct and able to execute but there may be some mistake in your query causing you not to get the results you expected.  The error message below is a classic example of where a variable name in the SELECT clause does not match up with a variable in the WHERE clause:</p>
<div class="red" style="text-align: center;"><b> parser warning: Variable nmae was selected but is unused in the query. at line 1 </b></div>
<br/><br/>
<script type= "text/javascript"><!-- --></script>

