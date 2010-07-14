<div class="purple" style="float: left; margin-right: 10px; margin-bottom:10px;">
<h3>Contents</h3>
<ol>
  <li><a href="?page=Using the SPARQL Endpoint">Using the SPARQL Endpoint</a></li>
  <ol>
    <li><a href="?page=Using the SPARQL Endpoint#Automated Querying">Automated Querying</a></li>
  </ol>
  <li><a href="?page=PREFIX">PREFIX</a></li>
  <ol>
    <li><a href="?page=PREFIX#BASE">BASE</a></li>
  </ol>
  <li><a href="?page=SELECT">SELECT</a></li>
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
<p>Although SPARQL is quite easy to use it may take a while to get you head round it if you are coming to it new and you are not from a computer science background.  This guide is intended to help explain the basics of SPARQL and give usable examples that will return actual results from <a href-"sparql">myExperiment's SPARQL endpoint</a>, which can be compared against example results.  Although it should be noted that example results are intended as a guide to the format of results and will probably not be exactly the same as those returned by the SPARQL endpoint.</p>
<p>The first section explains how to use the SPARQL endpoint and the unique features the myExperiment SPARQL endpoint has.</p>
<p>Sections 2-7 explain the main clauses used within SPARQL and how they are used to define queries.</p>
<p>Section 8 give some explanation of various warning and error messages you might be prompted with and how to fix you query to eliminate these.</p>

