<?php 
        include('../include.inc.php');
        $pagetitle="Changelog";
	include('header.inc.php');
?>
<p><big>This page includes the changelog for the <a href=".">myExperiment Ontology</a> and consequently myExperiment's RDF data.</big></p>

<ul class="changelog">
  <li><b>09/03/2011</b> Updated Packs module to create a separate Relationship class that has PackRelationship as an ore:Proxy to describe it in the context of the Pack. Fixed some other subclassing issues with PackEntry and Vocabulary</li>
  <li><b>10/02/2011</b> Added PackRelationship to Packs module to allow relationship between items in Packs to be defined.  Moved Vocabularies from Contributions to Packs module to support PackRelationships.  Added Concepts to Packs module, as a specific form of SKOS Concept to define the predicate of a PackRelationship.</li>
  <li><b>25/11/2010</b> Added foaf:name to user as more commonly used than sioc:name and added restriction for sioc:has_member and sioc:member_of to Group and User respectively.</li>
  <li><b>15/09/2010</b> Added DBPedia's residence property as potential field(s) for a User.</li>
  <li><b>01/07/2010</b> Added various has-x properties to allow users in the Linked Data world to more easily navigate around myExperiment entities.</li>
  <li><b>01/07/2010</b> Migrated RDF to use Linked Data Non-Information Resource URIs.  This has also involved providing information about the RDF document itself and not just the entity it is describing.</li>
  <li><b>01/07/2010</b> Consensus seems that xsd:anyURI should only be used if the resource is non-resolvable therefore have switched to unrestricted ObjectProperty where URIs can be resolved.  Also removed mebase:human-start-page as it is covered by the foaf:homepage in the description of the graph.</li>
  <li><b>19/05/2010</b> Added ranges to properties reused from non-myExperiment ontologies.
  <li><b>05/04/2010</b> Made Vocabulary a subclass of SKOS ConceptScheme and Tag a subclass of Concept changing it's dcterms property to skos:prefLabel.</li>
  <li><b>31/03/2010</b> Removed accessed-from-site as this can be inferred from user-agent.  Also removed retroactive assignment of human-start-page as a property restriction for Policy as there is currently no such thing exists</li>
  <li><b>30/03/2010</b> Made Version a subclass of Interface rather than Contribution.</li>
  <li><b>24/03/2010</b> Removed Use AccessType and associated Accesses from specific module as they are unused.</li>
  <li><b>24/03/2010</b> Fixed syntax error changing foaf:based-near to foaf:based_near.  Added Commentable as a subclass to File and Commentable, Favouritable and Taggable to Pack and by inheritance Experiment.  Added Taggable and Commentable as a subclass to Group retroactively in the specific module.</li>
  <li><b>22/03/2010</b> Removed cc:License definition in the specific module as these have been replaced by myExperiment's License class for which instances can be found at <a href="http://rdf.myexperiment.org/Licenses">http://rdf.myexperiment.org/Licenses</a>.</li>
  <li><b>21/03/2010</b> Added dbpedia ontology's residence property to Users.  This essentially uses DBPedia URIs rather than text strings and should iin time replace the foaf:based_near and mebase:country properties.</li>
  <li><b>03/02/2010</b> Anonymised usage statistics (i.e. Viewings and Downloads) are to no longer be generated.  This is due to the excessive amount of processor time taken to keep this up to date versus their actual usefulness.</li>
  <li><b>12/01/2010</b> The Components module has been completly rewritten to encompass Dataflows and to represent extra data exposed through the new Taverna GEMs.</li> 
  <li><b>28/09/2009</b> Removed has-license property from Base module, cc:license should be used instead.</li>
  <li><b>26/08/2009</b> To improve Linked Data compliance object properties have been changed to anyURI typed datatype properties where the object of these properties is not intended to resolve to RDF. Also a file extension has been added to the filename property for workflows and workflow versions.</li>
  <li><b>22/06/2009</b> Converted mebase:uri from anyURI datatype property to an object property and replaced mepack:alternate-uri with rdfs:seeAlso.</li>
  <li><b>19/06/2009</b> Upgraded Jobs to Contributions to support ORE Aggregation capability.</li>
  <li><b>26/05/2009</b> Removed dcterms:type from Workflows, WorkflowVersions and Files to use mebase:has-content-type. ContentType contains dcterms:title as a human-readable label for the type and dcterms:format for the MIME type.</li>
</ul>

<?php include('footer.inc.php'); ?>
