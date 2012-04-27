<?php
	include('include.inc.php');
	$pagetitle="Linked Data";
	include('header.inc.php');
	include('settings.inc.php');
?>
  <p>myExperiment's RDF is delivered following the principles of <a href="http://linkeddata.org/">Linked Data</a>.  Linked Data is a way of interconnecting data published on the web that has not been previously linked.</p>

  <p>Every myExperiment entity, whether it be a Workflow, Pack, User, Group, etc. has its own Non-Information Resource (NIR) URI to identify it.  This URI can also be used in a HTTP request where the header's accept parameter is set to an appropriate MIME type to retrieve data about it in one of up to three different formats, HTML, RDF and XML. This is done through a process called Content Negotiation, which redirects to an explicit URL for that particular format.  E.g.
<small>
    <ul>
      <li>wget --header &quot;Accept: text/html&quot; http://www.myexperiment.org/workflows/16 &rarr; <a href="http://www.myexperiment.org/workflows/16.html"title="Workflow 16's webpage">http://www.myexperiment.org/workflows/16.html</a></li>
      <li>wget --header &quot;Accept: application/rdf+xml&quot; http://www.myexperiment.org/workflows/16 &rarr; <a href="http://www.myexperiment.org/workflows/16.rdf" title="Workflow 16's RDF in XML format">http://www.myexperiment.org/workflows/16.rdf</a></li>
      <li>wget --header &quot;Accept: application/xml&quot; http://www.myexperiment.org/workflows/16 &rarr; <a href="http://www.myexperiment.org/workflows/16.xml" title="Workflow 16's REST API XML">http://www.myexperiment.org/workflows/16.xml</a></li>
    </ul>
</small>
  If you are using an application that does not allow you to specify parameters of the HTTP request you can use the explicit URL for the different formats to retrieve data in that format.</p>
   </p>
<p>To make myExperiment's RDF truly Linked Data it must link in with other Linked Data projects.  These links can either be from myExperiment, to myExperiment or a link created by a third party linking myExperiment and another Linked Data project.  Currently myExperiment has only a couple of links out to other Linked Data projects but now that the infrastructure for Linked Data is in place it should be possible to add further links both to and from myExperiment.</p>
  <h3>Ontology</h3>
  <p>The structure of myExperiment RDF is defined by a <a href="ontologies">ontology modules</a> that can be assembled to build the complete myExperiment Ontology.  This is a set of modules that borrows classes/properties from <a href="http://www.foaf-project.org/" title="FOAF Project Homepage">FOAF</a>, <a href="http://www.sioc-project.org/" title="SIOC Project Homepage">SIOC</a>, <a href="http://dublincore.org/" title="Dublin Core Metadata Initiative Homepage">Dublin Core</a>, <a href="http://creativecommons.org/" title="Creative Commons Homepage">Creative Commons</a> and <a href="http://www.openarchives.org/ore/" title-"Open Archives Initiative
Object Reuse and Exchange Homepage">OAI-ORE</a>, that can be assembled to build a comprehensive specification for the myExperiment data model.  <a href="http://rdf.myexperiment.org/ontologies/specification">Auto-generated documentation is available</a> that describes the documents and classes defined within the ontology.</p>

  <h3>SPARQL Endpoint</h3>
  <p>All myExperiment's public RDF data can queried using the query language SPARQL at <a href="sparql">myExperiment's SPARQL Endpoint</a>.  An introduction to SPARQL with a guide to querying myExperiment RDF <a href="howtosparql">is available here</a>.</p>

  <h3>Vocabulary of Interlinked Datasets (VoID)</h3>
  <p>A <a href="void.rdf">description of myExperiment RDF</a> is specified in <a href="http://vocab.deri.ie/void" title="voiD Homepage">voiD</a>.  One thing voiD encourages is the publication of RDF datasets so they can easily be reused by others rather than having to crawl each NIR for its RDF.  All of <a href="myexperiment.rdf.gz">myExperiment's Public RDF can be downloaded as a gzipped file</a>.</p>

 <br/>
<?php include('footer.inc.php'); ?>
