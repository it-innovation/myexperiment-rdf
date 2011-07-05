<?php
	include('../include.inc.php');
	$pagetitle="Ontology";
	include('header.inc.php');
?>
<p><a href="http://www.myexperiment.org/" title="myExperiment Homepage">myExperiment</a> is a collaborative environment where scientists can safely publish their workflows and experiment plans, share them with groups and find those of others.  (Please see <a href="http://wiki.myexperiment.org/index.php/Main_Page" title="myExperiment Wiki Homepage">the myExperiment Wiki</a> for more detailed information).  This results in the myExperiment data model having three main underlying features:</p>
<ul>
  <li><a href="#ContentManagement">Content Management</a></li>
  <li><a href="#SocialNetworking">Social Networking</a></li>
  <li><a href="#ObjectAnnotation">Object Annotation</a></li>
</ul>
<p>These features are the main focus of the myExperiment Ontology. Fig.1 gives a rough outline of how the main entities of myExperiment interact.</p>
<div align="center">
  <img src="/img/base_module_relationships.png" title="myExperiment's Main Entities Diagram"/>
  <p><b>Fig.1</b> The Main Entities of the myExperiment</p>
</div>

<p>The myExperiment Ontology borrows terms from a number of well-known ontologies/schemas to foster reuse be simplifying ontology alignment:</p>
<ul>
  <li><a href="http://dublincore.org/" title="Dublin Core Project Homepage">Dublin Core</a> to provide common metadata properties.
  <li><a href="http://www.foaf-project.org/" title="FOAF Project Homepage">FOAF</a> and <a href="http://www.sioc-project.org/" title="SIOC Project Homepage">SIOC</a> to describe the social network.</li>
  <li><a href="http://creativecommons.org/ns#" title="Creative Commons Namespace Page">Creative Commons</a> to define licenses for shared objects.</li>
  <li><a href="http://www.openarchives.org/ore/" title="OAI-ORE Homepage">OAI-ORE</a> to support the aggregation of resources.</li>
  <li><a href="http://dbpedia.org/About" title="DBPedia Homepage">DBPedia</a> so users can specify where they are resident as a URI rather than a literal.</li>
</ul>
<p>myExperiment borrows terms in a number of ways:</p>
<ul>
  <li>Equivalence relationships for properties or classes, (e.g. Group is equivalent to SIOC's UserGroup).</li>
  <li>Subclass/subproperty relationships, (e.g. email property is a subproperty of FOAF's mbox).
  <li>Required properties in class restrictions, (e.g. an uploaded Contribution must have a Creative Commons license property).
</ul>

<p>It was decided to construct the ontology in a modularized way to further encourage reuse, where others could pick and choose the modules they wanted to use.  The myExperiment ontology currently has 10 modules:</p>
<ul>
  <li><b><a href="http://rdf.myexperiment.org/ontologies/snarm/">SNARM</a></b> or Simple Network Access Rights Management defines Policies for describing who can do what with certain objects.</li>
  <li><b><a href="http://rdf.myexperiment.org/ontologies/base/">Base</a></b> provides the base elements required by myExperiment for content management, social networking and object annotation.)</li>
  <li><b><a href="http://rdf.myexperiment.org/ontologies/attrib_credit/">Attributions &amp; Credit</a></b> allows contributions to give attribution to earlier contributions and pay credit to users and groups involved in their creation.</li>
  <li><b><a href="http://rdf.myexperiment.org/ontologies/annotations/">Annotations</a></b> provides the different types of annotations used in myExperiment.</li>
  <li><b><a href="http://rdf.myexperiment.org/ontologies/packs/">Packs</a></b> facilitates the use of packs to aggregate contributions and remote urls together. (<a href="#Packs">More Information</a>)</li>
  <li><b><a href="http://rdf.myexperiment.org/ontologies/experiments/">Experiments</a></b> contains the classes required to create experiments and annotate them with jobs that have been or are scheduled to run. (<a href="#Experiments">More Information</a>)</li>
  <li><b><a href="http://rdf.myexperiment.org/ontologies/viewings_downloads/">Viewings &amp; Downloads</a></b> allows usage statistics on the viewings and downloads of contributions to be recorded.</li>
  <li><b><a href="http://rdf.myexperiment.org/ontologies/contributions/">Contributions</a></b> provides the different types of contributions used in myExperiment.</li>
  <li><b><a href="http://rdf.myexperiment.org/ontologies/components/">Components</a></b> allows components within workflows to be represented.  (<a href="#Components">More Information)</li>
  <li><b><a href="http://rdf.myexperiment.org/ontologies/specific/">Specific</a></b> provides classes and objects specific to myExperiment, including SNARM AccessTypes, CreativeCommons Licenses, the TavernaEnactor (a Taverna specific Runner) and the myExperiment AnonymousUser.</li>
</ul>
<p>Fig.2 is a class hierarchy diagram for all the classes used in the myExperiment ontology, colour-coded by the module they belong to.</p>
<div align="center">
  <img src="/img/class_hierarchy.png" title="myExperiment Ontology Class Hierarchy Diagram (Colour-coded)"/>
   <table class="key">
<tr><td><img src="/img/oval.png" alt="Oval"/></td><td>Abstract Class</td><td>&nbsp;</td><td><img src="/img/rectangle.png" alt="Rectangle"/></td><td>myExperiment Object Class</td></tr>
  </table>
  <table class="key">
<tr><td class="box" style="background-color: DarkKhaki;">&nbsp;</td><td>SNARM&nbsp;</td><td class="box" style="background-color: LightGreen;">&nbsp;</td><td>Base&nbsp;</td><td class="box" style="background-color: Khaki;">&nbsp;</td><td>Attributions &amp; Creditations&nbsp;</td><td class="box" style="background-color: LightSalmon">&nbsp;</td><td>Annotations</td><td class="box" style="background-color: MediumSlateBlue;">&nbsp;</td><td>Experiments&nbsp;</td></tr>
  </table>
  <table class="key">
<tr><td class="box" style="background-color: PaleTurquoise;">&nbsp;</td><td>Packs&nbsp;</td><td class="box" style="background-color: RosyBrown;">&nbsp;</td><td>Viewings &amp; Creditations&nbsp;</td><td class="box" style="background-color: LightPink">&nbsp;</td><td>Contributions</td><td class="box" style="background-color: Crimson;">&nbsp;</td><td>Components&nbsp;</td><td class="box" style="background-color: Silver">&nbsp;</td><td>Specific</td></tr>
  </table>
  <br/>
  <p><b>Fig.2</b> Class Hierarchy Diagram for Ontology Modules</p>
</div>
<p>For most of the classes described in Fig.2 <a href="/examples/" title="RDF Examples">RDF examples</a> are available.  Modules reuse terms from other modules as well as terms borrowed for the ontologies/schemas previously described.  Fig.3 diagrams of how modules borrow from each other with each arrow going from the borrowed-from module/ontology/schema to the borrowing module. The &quot;<a href="specific/" title="Base Ontology Module">Base</a>&quot; module is built open by all the other modules (except SNARM). The &quot;<a href="specific/" title="Specific Ontology Module">Specific</a>&quot; module is slightly different as it imports all the other modules below it.  This allows a single URI to be referred to when importing the myExperiment ontology set. Documentation explaining how all the ontology's classes and properties from the ontology can be used is available <a href="http://rdf.myexperiment.org/ontologies/specification" title="Specification of Modularized Ontology Set">here</a>.</p>
<div align="center">
  <img src="/img/ontology_modules_architecture.png" title="myExperiment Ontology Modules Archetecture"/>
  <br/><br/>
  <p><b>Fig.3</b> Ontology Modules Architecture</p>
</div>

<div class="hr"></div>

<h3><a name="ContentManagement">Content Management</a></h3>
<p>The <a href="base/">Base</a> module contains two classes <a href="specification#mebase:Contribution">Contribution</a> and its subclass <a href="specification#mebase:Upload">Upload</a> to represent content objects that can be managed.  Contribution is a superclass to describe any content that can be contributed by a User.  Upload is designed to only represent content that has been that is uploaded not created online.  Upload therefore has additional properties for filename, file URL, file type and licensing.</p>
<p>During the course of the myExperiment project one of the key challenges was providing a flexible and user-friendly interface for user to define who can do what with their contributions.  This led to the data model becoming slightly convuluted in this area.  Therefore the <a href="snarm/" title="SNARM Ontology Module">SNARM ontology</a> was defined to try to rationalise how this information is stored in RDF form.</p>

<p>SNARM uses policies which contain one or more <a href="specification#snarm:Access">Access</a> objects.  Access objects can be unrestricted or restricted to an <a href="specification#snarm:Accesser">Accesser</a> that could be a single user, a single group or a more abstract concept such as all the friends or the content owner.  The second component of an Access object is the <a href="specification#snarm:AccessType">AccessType</a> they provide, e.g. view, edit, download, etc.  These types are often quite particular and therefore <a href="specific/">Specific</a> is used to store these along with abstract Accessers (e.g. Friends) and abstract/unrestricted Access objects (e.g. FriendsEdit, PublicView).  A more detailed explanation of SNARM can be found <a href="/snarm_explained" title="SNARM Explained">here</a>.</p>

<div class="hr"></div>

<h3><a name="SocialNetworking">Social Networking</a></h3>
<p>The <a href="base/">Base</a> module contains three abstract classes (<a href="specification#mebase:Actor">Actor</a>, <a href="specification#mebase:Request">Request</a> and <a href="specification#mebase:Invitation">Invitation</a>) for social network building.  From these six object classes are derived:
<ul>
  <li><a href="specification#mebase:User">User</a></li>
  <li><a href="specification#mebase:Group">Group</a></li>
  <li><a href="specification#mebase:Friendship">Friendship</a></li>
  <li><a href="specification#mebase:Membership">Membership</a></li>
  <li><a href="specification#mebase:FriendshipInvitation">FriendshipInvitation</a></li>
  <li><a href="specification#mebase:MembershipInvitation">MembershipInvitation</a></li>
</ul>
<p>Users can request friendships with other users, which can then be accepted.  Users can request membership of a group which may be accepted by the group's owner.  A group owner may also request for a user to join a group which the user may then choose to join.  All information about the friends a user has and groups they are a member of are store within the Friendship and Membership objects.  FriendshipInvitation and MembershipInvitation are similar to their non-invitation counterpart except the request is made to an external party via an email address.</p>

<p>Friendships and Memberships are separate objects from the User or Group.  However, it is possible to write <a href="http://www.w3.org/Submission/SWRL/" title="SWRL W3C Member Submission Page">SWRL</a> rules to infer <a href="specification#mebase:is-friends-with">is-friends-with</a> and (<a href="specification#mebase:is-member">is</a>/<a href="specification#mebase:has-member">has</a>)-member triples that can be stored as properties of the User or Group object.</p>

<p>The Base module also contains a <a href="specification#mebase:Message">Message</a> class for sending messages between users within myExperiment.</p>

<div class="hr"></div>

<h3><a name="ObjectAnnotation">Object Annotation</a></h3>
<p>The <a href="base/">Base</a> module contains an abstract class <a href="specification#mebase:Annotation">Annotation</a>.   The <a href="annotations/">Annotations</a> module contains types of Annotation object used in myExperiment including:</p>
<ul>
  <li><a href="specification#meannot:Citation">Citation</a></li>
  <li><a href="specification#meannot:Comment">Comment</a></li>
  <li><a href="specification#meannot:Favourite">Favourite</a></li>
  <li><a href="specification#meannot:Rating">Rating</a></li>
  <li><a href="specification#meannot:Review">Review</a></li>
  <li><a href="specification#meannot:Tagging">Tagging</a></li>
</ul>
<p>No data for the Annotation is stored as part of the Contribution rather the Contribution is pointed to by the Annotation.  Fig.4 is an RDF graph of an example Annotation:</p>
<div align="center">
  <img src="/img/example_tagging.png" title="Example Annotation (A Tagging)" alt="Example Annotation"/>
  <p><b>Fig.4</b> Example Annotation (A Tagging)</p>
</div>

<div class="hr"></div>

<h3><a name="Packs">Packs</a></h3>
<p>The Packs module provides a way for aggregating myExperiment <a href="specification#mebase:Contribution">Contributions</a> and external URLs within a single object and implements the <a href="http://www.openarchives.org/ore/" title="OAI-ORE Abstract Data Model">OAI-ORE Specification</a> (see <a href="packs/example">example</a>).  <a href="specification#mepack:Pack">Packs</a> are a subclass of <a href="http://rdf.myexperiment.org/ore/specification" title="OAI-ORE Specification Document">OAI-ORE schema</a>'s <a href="http://rdf.myexperiment.org/ore/specification#Aggregation">Aggregation</a> class.  To allow metadata to be associated only with an <a href=http://rdf.myexperiment.org/ore/specificationAggregatedResource">AggregatedResource</a> when it occurs within a particular Aggregation, the Packs module implements two ORE  <a href="http://rdf.myexperiment.org/ore/specification#Proxy">Proxy</a> classes:</p>
<ul>
  <li><a href="specification#mepack:LocalPackEntry">LocalPackEntry</a></li>
  <li><a href="specification#mepack:RemotePackEntry">RemotePackEntry</a></li>
</ul>
<p>Remote Pack Entries are pointers to URLs (see <a href="/remote_pack_entries/example">example</a>) whereas Local Pack Entries have a pointer (<a href="specification#mepack:requires">mepack:requires</a>) to specify the myExperiment Contribution they represent (see <a href="/local_pack_entries/example">example</a>).  Packs contain the property  <a href="http://rdf.myexperiment.org/ore/specification#isDescribedBy">ore:isDescribedBy</a> to allow the discovery of the associated <a href="http://rdf.myexperiment.org/ore/specification#ResourceMap">ResourceMaps</a> allowing the Pack to be exported to another repository (see <a href="/pack_resource_maps/example">example</a>).

<div class="hr"></div>

<h3><a name="PackRelationships">Pack Relationships</a></h3>
<p>As well as describing items in a Pack it is possible to describe the relationships between them.  A <a href="specification#mepack:PackRelationship">PackRelationship</a> allows two items to be link together with a predicate.  These predicates can be described in <a href="../examples/ontologies">user-defined ontologies</a>.  A PackRelationship is similar to a LocalPackEntry or RemotePackEntry in that it refers to a <a href="specification#mepack:Relationship">Relationship</a> and allows its use it with the context of a particular Pack.  It does this by using a ore:proxyFor to the Relationship and an ore:ProxyIn to the Pack, (see Fig.5).</p>

<div align="center">
  <img src="/img/RO_structure.png" title="Example Structure of a Pack (Research Object)" alt="Example Pack Structure"/>
  <p><b>Fig.5</b> Example Structure of a Pack (Research Object)</p>
</div>

<p>A Relationship is made up of an RDF, subject, predicate and object. The hashed part of the URI is an SHA1 hash of the concatenated subject, predicate and object URIs.  This ensures that all identical relationships have the same URI, essentially indexing relationships to make it easier to find packs that share the same relationships.</p>


<div class="hr"></div>

<h3><a name="Experiments">Experiments</a></h3>
<p>myExperiment provides cloud services to run <a href="specification#meexp:Runnable">Runnable</a> <a href="specification#mebase:Contribution">Contributions</a> (e.g. <a href="specification#mecontrib:Workflow">Workflows</a>) in remote <a href="specification#meexp:Runner">Runners</a>.  These cloud services are provided by the <a href="experiments/">Experiments</a> module. An <a href="specification#meexp:Experiment">Experiment</a> is a specialisation of a <a href="specification#mepack:Pack">Pack</a> that aggregates <a href="specification#meexp:Job">Jobs</a>, (making Jobs <a href=http://rdf.myexperiment.org/ore/specification#AggregatedResource">AggregatedResources</a>). Jobs are enacted workflows on a remote runner.  A Job contains all the information about the Workflow being run, the Runner being used, the inputs/outputs of the Job and status information.  The inputs and outputs of a Job have their own URIs so they can be referenced separately.  (See an <a href="/jobs/example" title="Example RDF for a Job">example of a Job</a>). Like Packs, Experiments have an <a href="http://rdf.myexperiment.org/ore/specification#isDescribedBy">ore:isDescribedBy</a> property to allow the discovery of the associated <a href="http://rdf.myexperiment.org/ore/specification#ResourceMap">ResourceMaps</a> allowing them exported to another repository.</p>

<div class="hr"></div>

<h3><a name="Components">Components</a></h3>
<p>Workflows in essence are a number of interlinked processes that have initial inputs and final outputs.  Using Taverna workflows as an exemplar six types of <a href="specification#mecomp:WorkflowComponent">Workflow Components</a> have been defined.</p>
<ul>
  <li><b><a href="specification#mecomp:Processor">Processor</a></b> A service that performs some processing on one or more Inputs and produces one or more Ouputs.</li>
  <li><b><a href="specification#mecomp:Source">Source</a></b> An initial piece of data to be processed by the Workflow</li>
  <li><b><a href="specification#mecomp:Sink">Sink</a></b> A final piece of data produced by the Workflow</li>
  <li><b><a href="specification#mecomp:Input">Input</a></b> A piece of data going into a Processor</li>
  <li><b><a href="specification#mecomp:Output">Output</a></b> A piece of data coming out of a Processor</li>
  <li><b><a href="specification#mecomp:Link">Link</a></b> A connection between an Output of a Source of Processor to the Input for another Processor or Sink</li>
</ul>
<p>All these Workflow Components are encompassed within a <a href="specification#mecomp:Dataflow">Dataflow</a>. Fig.6 shows how these Workflow Components interlink to form a Dataflow.  Currently every publicly available Taverna workflow has an <a href="specification#mecomp:executes-dataflow">executes-dataflow</a> property to it's Dataflow. </p>
<div align="center">
  <img src="/img/components.png" title="Overview of Organisation of Components into a Workflow" alt="Workflow Components Overview"/>
  <p><b>Fig.6</b> Overview of Organisation of Components into a Workflow</p>
</div>
<p>A Workflow may have many Processors, each must be one of the following types:</p>
<ul>
   <li><b><a href="specification#mecomp:BeanshellProcessor">BeanshellProcessor</a></b> This executes a Beanshell script</li>
   <li><b><a href="specification#mecomp:ConstantProcessor">ConstantProcessor</a></b> This produces a constant output, e.g. echoes a particular string</li>
   <li><b><a href="specification#mecomp:DataflowProcessor">DataflowProcessor</a></b> This executes another Dataflow allowing workflows to be nested.</li>
   <li><b><a href="specification#mecomp:WSDLProcessor">WSDLProcessor</a></b> This calls a remote WSDL service </li>
   <li><b><a href="specification#mecomp:OtherProcessor">OtherProcessor</a></b> Represent all other type of Processor.  All Processor have a <a href="specification#mecomp:processor-type">processor-type</a> property that gives a more specific categorisation for the Processor</li>
</ul>
<p>Due to it being possible to nest one Dataflow within another each Workflow Component has a <a href="specification#mecomp:belongs-to-workflow">belongs-to-workflow</a> that makes its possible to execute a simple SPARQL query to find all the components of a paticular workflow.</p>

<?php include('footer.inc.php'); ?>
