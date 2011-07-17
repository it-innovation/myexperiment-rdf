<div class="purple" style="float: left; margin-right: 10px; margin-bottom:10px;">
<h3>Contents</h3>
<ol>
  <li><a href="?page=Using the SPARQL Endpoint">Using the SPARQL Endpoint</a></li>
  <ol>
    <li><a href="?page=Using the SPARQL Endpoint#Useful Prefixes">Useful Prefixes</a></li>
    <li><a href="?page=Using the SPARQL Endpoint#Formatting">Formatting</a></li>
    <li><a href="?page=Using the SPARQL Endpoint#Soft Limit">Soft Limit</a></li>
    <li><a href="?page=Using the SPARQL Endpoint#Reasoning">Reasoning</a></li>
    <li><a href="?page=Using the SPARQL Endpoint#Automated Querying">Automated Querying</a></li>
  </ol>
  <li><a href="?page=PREFIX">PREFIX</a></li>
  <ol>
    <li><a href="?page=PREFIX#BASE">BASE</a></li>
  </ol>
  <li><a href="?page=SELECT">SELECT</a></li>
  <ol>
    <li><a href="?page=SELECT#DISTINCT">DISTINCT</a></li>
    <li><a href="?page=SELECT#COUNT">COUNT</a></li>
    <li><a href="?page=SELECT#SUM">SUM</a></li>
    <li><a href="?page=SELECT#AVG">AVG</a></li>
    <li><a href="?page=SELECT#MAX_MIN">MAX and MIN</a></li>
  </ol>
  <li><a href="?page=WHERE">WHERE</a></li>
  <ol>
    <li><a href="?page=WHERE#UNION">UNION</a></li>
    <li><a href="?page=WHERE#OPTIONAL">OPTIONAL</a></li>
  </ol>
  </li>
  <li><a href="?page=FILTER">FILTER</a></li>
  <ol>
    <li><a href="?page=FILTER#On Text">On Text</a></li>
    <li><a href="?page=FILTER#On Numbers">On Numbers</a></li>
    <li><a href="?page=FILTER#On Dates">On Dates</a></li>
  </ol>
  </li>
  <li><a href="?page=GROUP BY">GROUP BY</a></li>
  <li><a href="?page=ORDER BY">ORDER BY</a></li>
  <li><a href="?page=LIMIT">LIMIT</a></li>
  <ol>
    <li><a href="?page=LIMIT#OFFSET">OFFSET</a></li>
  </ol>
  <li><a href="?page=Troubleshooting">Troubleshooting</a></li>
  <ol>
    <li><a href="?page=Troubleshooting#Syntax Errors">Syntax Errors</a></li>
    <li><a href="?page=Troubleshooting#Complexity Warnings">Complexity Warnings</a></li>
    <li><a href="?page=Troubleshooting#Parser Warnings">Parser Warnings</a></li>
  </ol>
</ol>
</div>
<p>myExperiment's <a href="/sparql">SPARQL endpoint</a> allows anyone to query all of myExperiment's public data using the query language <a href="http://www.w3.org/TR/rdf-sparql-query/">SPARQL</a>.</p>
<p>Although SPARQL is relatively easy to use it may take a while to get your head round, particularly if you are coming to it new and you are not from a Computer Science background.  This guide is intended to help explain the basics of SPARQL and give usable examples that will return actual results from <a href="sparql">myExperiment's SPARQL endpoint</a>.</p>
<p>The first section explains how to use the SPARQL endpoint and the unique features of the  myExperiment SPARQL endpoint.</p>
<p>Sections 2-8 explain the main clauses used within SPARQL and how they are used to define queries.</p>
<p>Section 9 give some explanation of various warning and error messages you might be prompted with and how to fix your query to eliminate these.</p>

