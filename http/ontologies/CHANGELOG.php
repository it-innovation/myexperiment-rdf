<?php 
	ini_set('include_path','../inc/:.');
	$pagetitle="Changelog";
	include('header.inc.php');
?>
<p><big>This page includes the changelog for the <a href="../">myExperiment Ontology</a> and consequently myExperiment's RDF data.</big></p>

<ul class="changelog">
  <li><b>28/09/2009</b> Removed has-license property from Base module, cc:license should be used instead.</li>
  <li><b>26/08/2009</b> To improve Linked Data compliance object properties have been changed to anyURI typed datatype properties where the object of these properties is not intended to resolve to RDF. Also a file extension has been added to the filename property for workflows and workflow versions.</li>
  <li><b>22/06/2009</b> Converted mebase:uri from anyURI datatype property to an object property and replaced mepack:alternate-uri with rdfs:seeAlso.</li>
  <li><b>19/06/2009</b> Upgraded Jobs to Contributions to support ORE Aggregation capability.</li>
  <li><b>26/05/2009</b> Removed dcterms:type from Workflows, WorkflowVersions and Files to use mebase:has-content-type. ContentType contains dcterms:title as a human-readable label for the type and dcterms:format for the MIME type.</li>
</ul>

<?php include('footer.inc.php'); ?>
