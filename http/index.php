<?php
	ini_set('include_path','inc/:.');
	$pagetitle="RDF API";
	include('header.inc.php');
	include('data.inc.php');
?>
  <div style="float: right;">
    <?php include('login.inc.php'); ?>
  </div>
  <h3 style="margin-top: 0;">Welcome to the RDF API for myExperiment</h3>
  <p>myExperiment's RDF API provides myExperiment data in RDF/XML format.   Please see the <a href="guide">guide</a> explaining how to request RDF for myExperiment.  Some of myExperiment's data is private and is protected using HTTP authentication.  If you request a URI that is private then you will need to provide your myExperiment username and password to prove you have permission to retrieve this RDF.  To save time you can &quot;log in&quot; using the login form to the right.  This will save you having to provide your creditentials for each URI.  It will also allow you to make requests for RDF for all entities of a certain type that you have permission to see.</p>

  <h3>Ontologies</h3>
  <p>The structure of myExperiment RDF is defined by the <a href="ontologies/">myExperiment ontologies</a>.  This is a set of modules that borrows classes/properties from FOAF, SIOC, Dublin Core, Creative Commons and OAI-ORE, that can be assembled to build a comprehensive specification for the myExperiment data model.</p>

  <h3>SPARQL Endpoint</h3>
  <p>All myExperiment's public RDF data can queried using the query language SPARQL at <a href="sparql">myExperiment's SPARQL Endpoint</a>.</p>

  <h3>OAI-ORE Export</h3>
  <p>myExperiment Packs and Experiments can be exported as OAI-ORE Resource Maps.  Like RDF export private entities can be accessed using HTTP authentication.  A guide for how to export OAI-ORE Resource Maps can be found <a href="ORE">here</a>.</p>

<?php include('footer.inc.php'); ?>
