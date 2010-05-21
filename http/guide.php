<?php
	ini_set('include_path','inc/:.');
	$pagetitle="RDF Guide";
	include('header.inc.php');
	include('settings.inc.php');
?>
  <p>Below is a guide on how to retrieve RDF for one or for myExperiment entities.  It should be noted that if you request a URI that does not exist you will receive a 404 response.  When requesting one or more entities if none of them are publicly available you will be prompted for your myExperiment username and password.  However if at least one entity is public you will be returned with the RDF for all public entities that meet your request.</p>

  <p>If you wish to retrieve all entities that you have permission to view for a particular request you must first log in at <a href="<?= $guidedatauri ?>"><?= $guidedatauri ?></a>.</p>

  <p>If you wish to retrieve private RDF permissable to you using wget, curl, etc.  you must specify your username and password in the command, e.g.
  <pre>
wget --header "Accept: application/rdf+xml"
     --http-user=yourusername
     --http-password=yourpassword
     <?= $guidedatauri ?>announcements/1
  </pre>
  </p>

  <p>If you want all dump of all myExperiment's public RDF data you can download <a href="data.rdf.gz">a gzipped file of it</a>.</p>

  <div class="hr"></div>
  <div class="indent">
    <h3>One Specific myExperiment Entity</h3>
    <div class="purple"><h4><?= $guidedatauri ?>&lt;type&gt;/&lt;id&gt;</h4></div>
    <p>Where <b>&lt;type&gt;</b> can be one of:</p>
    <p class="indent">announcements, attributions, citations, comments, content_types, creditations, experiments, favourites, files, friendships, friendship_invitations, groups, group_announcements, jobs, licenses, local_pack_entries, memberships, membership_invitations, messages, packs, ratings, remote_pack_entries, reviews, taverna_enactors, tags, taggings, users, workflows, workflow_versions</p>
    <div class="green"><h4>E.g. <a href="<?= $guidedatauri ?>workflows/7"><?= $guidedatauri ?>workflows/7</a></h4></div>
  </div>
  <br/>
  <div class="hr"></div>
  <div class="indent">
    <h3>All myExperiment Entities of One Type</h3>
    <p>This is returned as a GZipped File.</p>
    <div class="purple"><h4><?= $guidedatauri ?>&lt;type&gt;</h4></div>
    <p>Where <b>&lt;type&gt;</b> can be one of:</p>
    <p class="indent">announcements, attributions, citations, comments, content_types, creditations, experiments, favourites, files, friendships, friendship_invitations, groups, group_announcements, jobs, licenses, local_pack_entries, memberships, membership_invitations, messages, packs, ratings, remote_pack_entries, reviews, taverna_enactors, tags, taggings, users, workflows, workflow_versions</p>
    <div class="green"><h4>E.g. <a href="<?= $guidedatauri ?>reviews"><?= $guidedatauri ?>reviews</a></h4></div>
  </div>
  <br/>
  <div class="hr"></div>
  <div class="indent">
    <h3>Components of a Workflow <br/><small>(Currently Public Taverna 1 and Taverna 2 Workflows only)</small></h3>
    <div class="purple"><h4><?= $guidedatauri ?>workflow_version/&lt;id&gt;/dataflow[/1]</h4></div>
    <p>Where <b>&lt;id&gt;</b> is the ID of the Workflow version</p>
    <div class="green"><h4>E.g. For Taverna 1 Workflows: <a href="<?= $guidedatauri ?>workflow_versions/24/dataflow"><?= $guidedatauri ?>workflow_versions/24/dataflow</a></h4>
  <h4>E.g. For Taverna 2 Workflows: <a href="<?= $guidedatauri ?>WorkflowVersion/997/dataflow/1"><?= $guidedatauri ?>workflow_versions/997/dataflow/1</a></h4>
</div>
  </div>
  <br/>
<?php include('footer.inc.php'); ?>
