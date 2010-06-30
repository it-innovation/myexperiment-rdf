<?php
	include('include.inc.php');
	$pagetitle="Linked Data";
	include('header.inc.php');
	include('settings.inc.php');
?>
  <p><a href="http://linkeddata.org/">Linked Data</a> is a way of interconnecting data published on the web that has not been previously linked.  myExperiment is now a publisher of such data.</p>

  <p>Every myExperiment entity, whether it be a Workflow, Pack, User, Group, etc. has its own Non-Information Resource (NIR) URI to identify it.  This URI can also be used  with the appropeiate MIME type specified in the accept parameter of the HTTP request to be redirected to data about it in one of up to three different formats, HTML, RDF and XML, through a process called Content Negiotiation.  E.g.
<small>
    <ul>
      <li>wget --header &quot;Accept: text/html&quot; http://www.myexperiment.org/workflows/16 &rarr; <a href="http://www.myexperiment.org/workflows/16.html"title="Workflow 16's webpage">http://www.myexperiment.org/workflows/16.html</a></li>
      <li>wget --header &quot;Accept: application/rdf+xml&quot; http://www.myexperiment.org/workflows/16 &rarr; <a href="http://www.myexperiment.org/workflows/16.xml" title="Workflow 16's RDF in XML format">http://www.myexperiment.org/workflows/16.rdf</a></li>
      <li>wget --header &quot;Accept: application/xml&quot; http://www.myexperiment.org/workflows/16 &rarr; <a href="http://www.myexperiment.org/workflows/16.xml" title="Workflow 16's REST API XML">http://www.myexperiment.org/workflows/16.xml</a></li>
    </ul>
</small>
  If you are using an application that does not allow you to specify parameters of the HTTP request you can use the explicit URL for the different formats to retrieve data in that format.</p>
   </p>
  <h3>Ontology</h3>
  <p>The structure of myExperiment RDF is defined by the <a href="ontologies/">myExperiment Ontology</a>.  This is a set of modules that borrows classes/properties from <a hrepf="http://www.foaf-project.org/" title="FOAF Project Homepage">FOAF</a>, <a href="http://www.sioc-project.org/" title="SIOC Project Homepage">SIOC</a>, <a href="http://dublincore.org/" title="Dublin Core Metadata Initiative Homepage">Dublin Core</a>, <a href="http://creativecommons.org/" title="Creative Commons Homepage">Creative Commons</a> and <a href="http://www.openarchives.org/ore/" title-"Open Archives Initiative
Object Reuse and Exchange Homepage">OAI-ORE</a>, that can be assembled to build a comprehensive specification for the myExperiment data model.</p>

  <h3>SPARQL Endpoint</h3>
  <p>All myExperiment's public RDF data can queried using the query language SPARQL at <a href="sparql">myExperiment's SPARQL Endpoint</a>.</p>

  <h3>Vocabulary of Interlinked Datasets (VoID)</h3>
  <p>A <a href="void.rdf">description of myExperiment RDF</a> is specified in <a href="http://vocab.deri.ie/void" title="voiD Homepage">voiD</a>.  One thing voiD encourages is the publication of RDF datasets so they can easily be reused by others rather than having to crawl each NIR for its RDF.  All of <a href="myexperiment.rdf.gz">myExperiment's Public RDF can be downloaded as a gzipped file</a>.</p>

 <br/>
<?php include('footer.inc.php'); ?>
