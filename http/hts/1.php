<h2>1. Using the SPARQL Endpoint</h2>
<p>The <a href="/sparql">myExperiment SPARQL endpoint</a> has a number of features to assist in its use.  As explained in the <a href="?page=PREFIX">PREFIX</a> page, both the PREFIX and BASE clauses facilitate writing more succinct and easier to follow queries.  If you need to include any prefixes in your query you need just click on the appropriate prefixes in the <em>Useful Prefixes</em> table and they will be prepended to your query.  If you fail to add all the required prefixes the endpoint will attempt to add them automatically mapping the prefixes inside the curly brackets {} to those defined in the Useful Prefixes table.</p>
<p>myExperiment's triplestore to which the SPARQL endpoint queries is updated with the previous day's data between 08:40 and 09:00 each moroning UK time.  The endpoint provides information about the time the latest snapshot was taken and the number of triples, so you can be sure how current the data is.</p>
<p>SPARQL results can be rendered in a number of formats:</p>
<ul>
  <li><b>XML (In Page):</b> The default format, which renders the XML SPARQL results as preformatted HTML in the SPARQL endpoint page.</li>
  <li><b>HTML Table:</b> Renders the results in an HTML table, giving a more visual way to view your results.</li>
  <li><b>XML (Raw):</b> Renders just the SPARQL results on an application/sparql-results+xml content type page</li>
  <li><b>CSV:</b> Returns results as comma separated values, in table columns format.</li>
  <li><b>CSV Matrix:</b> Returns results as comma separated values in a matrix format, where the first variable is enumrated on the x-axis, the second variable is enumerated on the y-axis and 1s are rendered for each tuplet.</li>
</ul>
<p>An example of a use for the CSV Matrix format is for friendships:</p>
<div class="yellow"><pre>PREFIX mebase: &lt;http://rdf.myexperiment.org/ontologies/base/&gt;
PREFIX rdf: &lt;http://www.w3.org/1999/02/22-rdf-syntax-ns#&gt;
SELECT ?requester ?accepter
WHERE{
  ?friendship rdf:type mebase:Friendship ;
    mebase:accepted-at ?accepted_time ;
    mebase:has-requester ?requester ;
    mebase:has-accepter ?accepter .
}</pre><div style="text-align: right; float: right; position: relative; top: -35px;">[<a href="/sparql?query=PREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0ASELECT+%3Frequester+%3Faccepter+%0D%0AWHERE%7B+%0D%0A++%3Ffriendship+rdf%3Atype+mebase%3AFriendship+%3B%0D%0A++++mebase%3Aaccepted-at+%3Faccepted_time+%3B+%0D%0A++++mebase%3Ahas-requester+%3Frequester+%3B%0D%0A++++mebase%3Ahas-accepter+%3Faccepter+%0D%0A%7D&amp;formatting=CSV Matrix">Run</a>]<br/><span id="results1_show" onclick="showResults('results1');" style="display: none;">[<span class="link">Show&nbsp;Example&nbsp;Results</span>]</span><span id="results1_hide" onclick="hideResults('results1');">[<span class="link">Hide&nbsp;Example&nbsp;Results</span>]</span></div></div>
<div class="green" id="results1" style="clear: both; position: relative; top: -26px; text-align: center;">
  <img src="img/fs_results.png" alt="Friendship CVS Matrix Results" />
</div>
<br/>
<p>The <em>Soft Limit</em> option determines the amount of resources dedicated to returning all the matching results.  In general 1% is sufficient.  However, if all the results are not returned then a warning message will be displayed and you can try re-running the query with a greater Soft Limit percentage.</p>

<h3><a name="Automated Querying"/>1.1. Automated Querying</h3>
<p>If you wish to write automated queries rather than using the endpoint form you can insert the query (in URL encoded format) into the URL as the <em>query</em> parameter in the HTTP GET header.  If you have built a query using the endpoint form and want to use it as an automated service in something such as a workflow, instead of clicking &quot;Submit Query&quot;, click on &quot;Generate Service from Query&quot;.  This will take you to a page with a link something like the one below, that you can copy and paste into your workflow or HTTP request capable application.</a></p>
<code><small><a target="_blank" href="/sparql?query=PREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0ASELECT+%3Frequester+%3Faccepter+%0D%0AWHERE%7B+%0D%0A++%3Ffriendship+rdf%3Atype+mebase%3AFriendship+%3B%0D%0A++++mebase%3Aaccepted-at+%3Faccepted_time+%3B+%0D%0A++++mebase%3Ahas-requester+%3Frequester+%3B%0D%0A++++mebase%3Ahas-accepter+%3Faccepter+%0D%0A%7D"><?= $hostpath ?>sparql<b>?query=PREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0ASELECT+%3Frequester+%3Faccepter+%0D%0AWHERE%7B+%0D%0A++%3Ffriendship+rdf%3Atype+mebase%3AFriendship+%3B%0D%0A++++mebase%3Aaccepted-at+%3Faccepted_time+%3B+%0D%0A++++mebase%3Ahas-requester+%3Frequester+%3B%0D%0A++++mebase%3Ahas-accepter+%3Faccepter+%0D%0A%7D</b></a></small></code>
<p>As you will notice is you click on the link above this will return results is raw SPARQL results XML format.  If you wish to get the results in a different format you can also set the <em>formatting</em> parameter in the GET header:</p>
<code><small><a target="_blank" href="/sparql?query=PREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0ASELECT+%3Frequester+%3Faccepter+%0D%0AWHERE%7B+%0D%0A++%3Ffriendship+rdf%3Atype+mebase%3AFriendship+%3B%0D%0A++++mebase%3Aaccepted-at+%3Faccepted_time+%3B+%0D%0A++++mebase%3Ahas-requester+%3Frequester+%3B%0D%0A++++mebase%3Ahas-accepter+%3Faccepter+%0D%0A%7D&amp;formatting=In Page"><?= $hostpath ?>sparql?query=PREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0ASELECT+%3Frequester+%3Faccepter+%0D%0AWHERE%7B+%0D%0A++%3Ffriendship+rdf%3Atype+mebase%3AFriendship+%3B%0D%0A++++mebase%3Aaccepted-at+%3Faccepted_time+%3B+%0D%0A++++mebase%3Ahas-requester+%3Frequester+%3B%0D%0A++++mebase%3Ahas-accepter+%3Faccepter+%0D%0A%7D<b>&amp;formatting=In Page</b></a></small></code>
<p>The options for the formatting parameter are:</p>
<ul>
  <li>In Page</li>
  <li>Raw</li>
  <li>HTML Table</li>
  <li>CSV</li>
  <li>CSV Matrix</li>
</ul>
<p>The Soft Limit can also be set as an integer between 1 (default) and 100 by using the GET header parameter <em>softlimit</em>:</p>
<code><small><a target="_blank" href="/sparql?query=PREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0ASELECT+%3Frequester+%3Faccepter+%0D%0AWHERE%7B+%0D%0A++%3Ffriendship+rdf%3Atype+mebase%3AFriendship+%3B%0D%0A++++mebase%3Aaccepted-at+%3Faccepted_time+%3B+%0D%0A++++mebase%3Ahas-requester+%3Frequester+%3B%0D%0A++++mebase%3Ahas-accepter+%3Faccepter+%0D%0A%7D&amp;formatting=In Page&amp;softlimit=5"><?= $hostpath ?>sparql?query=PREFIX+mebase%3A+%3Chttp%3A%2F%2Frdf.myexperiment.org%2Fontologies%2Fbase%2F%3E%0D%0APREFIX+rdf%3A+%3Chttp%3A%2F%2Fwww.w3.org%2F1999%2F02%2F22-rdf-syntax-ns%23%3E%0D%0ASELECT+%3Frequester+%3Faccepter+%0D%0AWHERE%7B+%0D%0A++%3Ffriendship+rdf%3Atype+mebase%3AFriendship+%3B%0D%0A++++mebase%3Aaccepted-at+%3Faccepted_time+%3B+%0D%0A++++mebase%3Ahas-requester+%3Frequester+%3B%0D%0A++++mebase%3Ahas-accepter+%3Faccepter+%0D%0A%7D&amp;formatting=In Page<b>&amp;softlimit=5</b></a></small></code>
<br/><br/>

<script type= "text/javascript"><!-- 
  hideResults('results1');
--></script>

