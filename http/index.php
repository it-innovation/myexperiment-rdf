<?php
	ini_set('include_path','inc/:.');
	$pagetitle="Linked Data";
	include('header.inc.php');
	include('data.inc.php');
?>
  <h3 style="margin-top: 0;">Welcome to myExperiment Linked Data</h3>
  <p><a href="http://linkeddata.org/">Linked Data</a> is a way of interconnecting data published on the web that has not been previously linked.  myExperiment is a publishers of such data, making its public data available on the web in three different formats:
    <ul>
      <li>HTML web pages. E.g  <a href="http://www.myexperiment.org/workflows/16.html">http://www.myexperiment.org/workflows/16.html</a><li>
      <li>REST API XML. E.g.  <a href="http://www.myexperiment.org/workflows/16.xml">http://www.myexperiment.org/workflows/16.xml</a></li>
      <li>RDF/XML. E.g.  <a href="http://www.myexperiment.org/workflows/16.rdf">http://www.myexperiment.org/workflows/16.rdf</a></li>
    </ul>
  <p>
  <h3>Ontology</h3>
  <p>The structure of myExperiment RDF is defined by the <a href="ontologies/">myExperiment Ontology</a>.  This is a set of modules that borrows classes/properties from FOAF, SIOC, Dublin Core, Creative Commons and OAI-ORE, that can be assembled to build a comprehensive specification for the myExperiment data model.</p>

  <h3>SPARQL Endpoint</h3>
  <p>All myExperiment's public RDF data can queried using the query language SPARQL at <a href="sparql">myExperiment's SPARQL Endpoint</a>.</p>

  <h3>OAI-ORE Export</h3>
  <p>myExperiment Packs and Experiments can be exported as OAI-ORE Resource Maps.  Like RDF export private entities can be accessed using HTTP authentication.  A guide for how to export OAI-ORE Resource Maps can be found <a href="ORE">here</a>.</p>

 <br/>
<?php include('footer.inc.php'); ?>
