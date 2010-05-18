<?php
$pagetitle="Private RDF";
ini_set('include_path','../inc/:.');
require('data.inc.php');
$urlbase=$datauri."private/";
include('header.inc.php');
?>
<div class="hr"></div>
<div class="indent">
  <h3>One Specific myExperiment Object</h3>
  <div class="purple"><h4><?= $urlbase ?>&lt;type&gt;/&lt;id&gt;</h4></div>
  <p>Where <b>&lt;type&gt;</b> can be one of:</p>
  <p class="indent">Announcement, Attribution, Citation, Comment, ContentType, Creditation, Downloads, Experiment, Favourite, File, Friendship, FriendshipInvitation, Group, Job, LocalPackEntry, MappingType, Membership, MembershipInvitation, MergeRule, Message, NodeType, Pack, Parser, Parsing, Question, QuestionType, Rating, RemotePackEntry, Review, TavernaEnactor, Tag, Tagging, User, Viewings, Workflow, WorkflowVersion</p>
  <div class="green"><h4>E.g. <a href="<?= $urlbase ?>Workflow/7"><?= $urlbase ?>Workflow/7</a></h4></div>
</div>

<br/>
<div class="hr"></div>

<div class="indent"> 
  <h3>All myExperiment Objects of One Type</h3>
  <div class="purple"><h4><?= $urlbase ?>&lt;type&gt;</h4></div>
  <p>Where <b>&lt;type&gt;</b> can be one of:</p>
  <p class="indent">Announcements, Attributions, Citations, Comments, ContentTypes, Creditations, AllDownloads, Experiments, Favourites, Files, Friendships, FrienshipInvitations, Groups, Jobs, LocalPackEntries, MappingTypes, Memberships, MembershipInvitations, MergeRules, Messages, NodeTypes, Packs, Parsers, Parsings, Questions, QuestionTypes, Ratings, RemotePackEntries, Reviews, TavernaEnactors, Tags, Taggings, Users, Viewings, Workflows, WorkflowVersions</p>
  <div class="green"><h4>E.g. <a href="<?= $urlbase ?>Reviews"><?= $urlbase ?>Reviews</a></h4></div>

<br/>

<?php include('footer.inc.php');?>

