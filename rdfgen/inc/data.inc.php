<?php
	include('settings.inc.php');
        include('datatypes.inc.php');

	//Get types for datatype properties
	$datatypes=getDatatypes();

	//ORE Stuff
	$all_ars=array();
	$aggregateprops=array("mecontrib:preview","mecontrib:thumbnail","mecontrib:thumbnail-big","mecontrib:svg","mebase:has-version","mebase:has-current-version","mebase:content-url","foaf:homepage","meexp:has-input","meexp:has-output","meexp:has-runner","meexp:has-runnable","mebase:uri");
	$aggregateclasses=array("files","workflows","workflow_versions","packs","experiments","jobs");

	//Entities to SQL table mappings	
	$tables['announcements']="announcements";
	$tables['attributions']="attributions";
	$tables['citations']="citations";
 	$tables['comments']="comments";
	$tables['content_types']="content_types";
	$tables['creditations']="creditations";
	$tables['experiments']="experiments";
	$tables['favourites']="bookmarks";
	$tables['files']="blobs";
	$tables['friendships']="friendships";
	$tables['friendship_invitations']="pending_invitations";
	$tables['jobs']="jobs";
	$tables['groups']="networks";
	$tables['group_announcements']="group_announcements";
	$tables['licenses']="licenses";
	$tables['local_pack_entries']="pack_contributable_entries";
	$tables['memberships']="memberships";
	$tables['membership_invitations']="pending_invitations";
	$tables['messages']="messages";
	$tables['packs']="packs";
	$tables['policies']="policies";
	$tables['ratings']="ratings";
	$tables['remote_pack_entries']="pack_remote_entries";
	$tables['reviews']="reviews";
	$tables['runners']="taverna_enactors";
	$tables['tags']="tags";
	$tables['taggings']="taggings";
	$tables['users']="users";
	$tables['vocabularies']="vocabularies";
	$tables['workflows']="workflows";
	$tables['workflow_versions']="workflow_versions";


	//Ontology entities
	$ontent['announcements']="Announcement";
        $ontent['attributions']="Attribution";
        $ontent['citations']="Citation";
        $ontent['comments']="Comment";
        $ontent['content_types']="ContentType";
        $ontent['creditations']="Creditation";
        $ontent['experiments']="Experiment";
        $ontent['favourites']="Favourite";
        $ontent['files']="File";
        $ontent['friendships']="Friendship";
        $ontent['friendship_invitations']="FriendshipInvitation";
        $ontent['jobs']="Job";
        $ontent['groups']="Group";
        $ontent['group_announcements']="GroupAnnouncement";
        $ontent['licenses']="License";
        $ontent['local_pack_entries']="LocalPackEntry";
        $ontent['memberships']="Membership";
        $ontent['membership_invitations']="MembershipInvitation";
        $ontent['messages']="Message";
        $ontent['packs']="Pack";
	$ontent['policies']="Policy";
        $ontent['ratings']="Rating";
        $ontent['remote_pack_entries']="RemotePackEntry";
        $ontent['reviews']="Review";
        $ontent['runners']="TavernaEnactor";
        $ontent['tags']="Tag";
        $ontent['taggings']="Tagging";
        $ontent['users']="User";
        $ontent['vocabularies']="Vocabulary";
        $ontent['workflows']="Workflow";
        $ontent['workflow_versions']="WorkflowVersion";

	//Mapping of Entities to the namespaces they belong to (Modularized Only)
	$entityns['announcements']="mebase";
	$entityns['attributions']="meac";
	$entityns['citations']="meannot";
	$entityns['comments']="meannot";
	$entityns['content_types']="mebase";
	$entityns['creditations']="meac";
	$entityns['experiments']="meexp";
	$entityns['favourites']="meannot";
	$entityns['files']="mecontrib";
	$entityns['friendships']="mebase";
	$entityns['friendship_invitations']="mebase";
	$entityns['jobs']="meexp";
	$entityns['groups']="mebase";
	$entityns['group_announcements']="mebase";
	$entityns['licenses']="mebase";
	$entityns['local_pack_entries']="mepack";
	$entityns['memberships']="mebase";
	$entityns['membership_invitations']="mebase";
	$entityns['messages']="mebase";
	$entityns['packs']="mepack";
	$entityns['policies']="snarm";
	$entityns['ratings']="meannot";
	$entityns['remote_pack_entries']="mepack";
	$entityns['reviews']="meannot";
	$entityns['runners']="mespec";
	$entityns['tags']="meannot";
	$entityns['taggings']="meannot";
	$entityns['users']="mebase";
	$entityns['vocabularies']="mecontrib";
	$entityns['workflows']="mecontrib";
	$entityns['workflow_versions']="mecontrib";

	$prefixes="PREFIX snarm: <http://rdf.myexperiment.org/ontologies/snarm/>
PREFIX mebase: <http://rdf.myexperiment.org/ontologies/base/>
PREFIX meannot: <http://rdf.myexperiment.org/ontologies/annotations/>
PREFIX meac: <http://rdf.myexperiment.org/ontologies/attrib_credit/>
PREFIX meexp: <http://rdf.myexperiment.org/ontologies/experiments/>
PREFIX mepack: <http://rdf.myexperiment.org/ontologies/packs/>
PREFIX mecontrib: <http://rdf.myexperiment.org/ontologies/contributions/>
PREFIX mevd: <http://rdf.myexperiment.org/ontologies/viewings_downloads/>
PREFIX mecomp: <http://rdf.myexperiment.org/ontologies/components/>
PREFIX mespec: <http://rdf.myexperiment.org/ontologies/specific/>
PREFIX rdf: <http://www.w3.org/1999/02/22-rdf-syntax-ns#>
PREFIX rdfs: <http://www.w3.org/2000/01/rdf-schema#>
PREFIX owl: <http://www.w3.org/2002/07/owl#>
PREFIX xsd: <http://www.w3.org/2001/XMLSchema#>
PREFIX dc: <http://purl.org/dc/elements/1.1/>
PREFIX dcterms: <http://purl.org/dc/terms/>
PREFIX foaf: <http://xmlns.com/foaf/0.1/>
PREFIX sioc: <http://rdfs.org/sioc/ns#>
PREFIX ore: <http://www.openarchives.org/ore/terms/>";


        //SQL
	if ($domain=="public"){
		$pubcond="policies.share_mode in (0,1,2)";
	       	$sql['announcements']="select * from announcements";
        	$sql['attributions']="select attributions.* from attributions inner join contributions on attributions.attributor_id=contributions.contributable_id and attributions.attributor_type=contributions.contributable_type inner join policies on contributions.policy_id=policies.id where ($pubcond)";
	        $sql['comments']="select comments.* from comments left join contributions on comments.commentable_id=contributions.contributable_id and comments.commentable_type=contributions.contributable_type left join policies on contributions.policy_id=policies.id where ($pubcond or comments.commentable_type='Network') and comments.commentable_type in ('Workflow','Pack','Blob','Network')";
        	$sql['citations']="select citations.* from citations inner join contributions on citations.workflow_id=contributions.contributable_id and contributions.contributable_type='Workflow' inner join policies on contributions.policy_id=policies.id where ($pubcond)";
	        $sql['content_types']="select * from content_types";
        	$sql['creditations']="select creditations.* from creditations inner join contributions on creditations.creditable_id=contributions.contributable_id and creditations.creditable_type=contributions.contributable_type inner join policies on contributions.policy_id=policies.id where ($pubcond)";
        	$sql['experiments']="select * from experiments where 1=2";
	        $sql['favourites']="select bookmarks.* from bookmarks inner join contributions on bookmarks.bookmarkable_id=contributions.contributable_id and bookmarks.bookmarkable_type=contributions.contributable_type inner join policies on contributions.policy_id=policies.id where ($pubcond)";
        	$sql['files']="select blobs.*, contributions.id as contribution_id, contributions.viewings_count, contributions.downloads_count, policies.id as policy_id, policies.update_mode, policies.share_mode from blobs inner join contributions on contributions.contributable_id=blobs.id inner join policies on contributions.policy_id=policies.id where contributable_type='Blob' and ($pubcond)";
	        $sql['friendships']="select * from friendships";
        	$sql['friendship_invitations']="select id, request_for as user_id, null as friend_id, created_at, null as accepted_at, message, email, requested_by, token from pending_invitations where request_type='friendship'";
	        $sql['group']="select * from networks";
        	$sql['group_announcements']="select * from group_announcements where (public=1)";
	        $sql['jobs']="select jobs.* from jobs inner join contributions on jobs.runnable_id=contributions.contributable_id and jobs.runnable_type=contributions.contributable_type inner join policies on contributions.policy_id=policies.id where ($pubcond and 1=2)";
        	$sql['licenses']="select * from licenses";
	        $sql['local_pack_entries']="select pack_contributable_entries.* from pack_contributable_entries inner join packs on pack_contributable_entries.pack_id=packs.id inner join contributions on packs.id=contributions.contributable_id and contributions.contributable_type='Pack' inner join policies on contributions.policy_id=policies.id where ($pubcond)";
        	$sql['memberships']="select * from memberships";
	        $sql['membership_invitations']="select id, null as user_id, request_for as network_id, created_at, null as user_established_at, created_at as network_established_at, message, email, requested_by, token from pending_invitations where request_type='membership'";
        	$sql['messages']="select * from messages where 1=2";
	        $sql['packs']="select packs.*, contributions.viewings_count, contributions.downloads_count, policies.id as policy_id, policies.update_mode, policies.share_mode from packs inner join contributions on packs.id=contributions.contributable_id and contributions.contributable_type='Pack' inner join policies on contributions.policy_id=policies.id where ($pubcond)";
		$sql['policies']="select contributions.*, policies.id as policy_id from policies inner join contributions on policies.id=contributions.policy_id where ($pubcond)";
        	$sql['ratings']="select ratings.* from ratings inner join contributions on ratings.rateable_id=contributions.contributable_id and ratings.rateable_type=contributions.contributable_type inner join policies on contributions.policy_id=policies.id where ($pubcond)";
	        $sql['remote_pack_entries']="select pack_remote_entries.* from pack_remote_entries inner join packs on pack_remote_entries.pack_id=packs.id inner join contributions on packs.id=contributions.contributable_id and contributions.contributable_type='Pack' inner join policies on contributions.policy_id=policies.id where ($pubcond)";
        	$sql['reviews']="select reviews.* from reviews inner join contributions on reviews.reviewable_id=contributions.contributable_id and reviews.reviewable_type=contributions.contributable_type inner join policies on contributions.policy_id=policies.id where ($pubcond)";
	        $sql['runners']="select * from taverna_enactors where 1=2";
        	$sql['taggings']="select taggings.* from taggings left join contributions on taggings.taggable_id=contributions.contributable_id and taggings.taggable_type=contributions.contributable_type left join policies on contributions.policy_id=policies.id where ($pubcond or taggings.taggable_type='Network')";
	        $sql['tags']="select * from tags";
        	$sql['users']="select users.*, profiles.picture_id, profiles.email as profile_email, profiles.website, profiles.body_html, profiles.field_or_industry, profiles.occupation_or_roles, profiles.organisations, profiles.location_city, profiles.location_country, profiles.interests, profiles.contact_details, pictures.id as avatar_id from users inner join profiles on users.id=profiles.user_id left join pictures on profiles.picture_id=pictures.id";
        	$sql['vocabularies']="select * from vocabularies where 1=2";
	        $sql['workflows']="select workflows.*, contributions.id as contribution_id, contributions.viewings_count, contributions.downloads_count, policies.id as policy_id, policies.update_mode, policies.share_mode from contributions inner join workflows on contributions.contributable_id=workflows.id inner join policies on contributions.policy_id=policies.id where contributable_type='Workflow' and ($pubcond)";
        	$sql['workflow_versions']="select workflow_versions.*, workflows.license_id, contributions.id as contribution_id, policies.id as policy_id, policies.update_mode, policies.share_mode from contributions inner join workflows on contributions.contributable_id=workflows.id inner join workflow_versions on workflows.id=workflow_versions.workflow_id inner join policies on contributions.policy_id=policies.id where contributable_type='Workflow' and ($pubcond)";
	}
	else{
		$sql['announcements']="select announcements.*, users.name from announcements inner join users on announcements.user_id=users.id";
        	$sql['attributions']="select attributions.* from attributions";
	        $sql['comments']="select comments.* from comments where comments.commentable_type in ('Workflow','Pack','Blob','Network')";
        	$sql['citations']="select citations.* from citations";
	        $sql['content_types']="select * from content_types";
        	$sql['creditations']="select creditations.* from creditations";
        	$sql['experiments']="select experiments.* from experiments";
	        $sql['favourites']="select bookmarks.* from bookmarks";
        	$sql['files']="select blobs.*, contributions.id as contribution_id, contributions.viewings_count, contributions.downloads_count, policies.id as policy_id, policies.update_mode, policies.share_mode from blobs inner join contributions on contributions.contributable_id=blobs.id inner join policies on contributions.policy_id=policies.id where contributable_type='Blob'";
	        $sql['friendships']="select friendships.* from friendships";
        	$sql['friendship_invitations']="select id, request_for as user_id, null as friend_id, created_at, null as accepted_at, message, email, requested_by, token from pending_invitations where request_type='friendship'";
	        $sql['groups']="select networks.* from networks";
        	$sql['group_announcements']="select group_announcements.* from group_announcements";
	        $sql['jobs']="select jobs.* from jobs";
        	$sql['licenses']="select * from licenses";
	        $sql['local_pack_entries']="select pack_contributable_entries.* from pack_contributable_entries";
        	$sql['memberships']="select memberships.* from memberships";
	        $sql['membership_invitations']="select id, null as user_id, request_for as network_id, created_at, null as user_established_at, created_at as network_established_at, message, email, requested_by, token from pending_invitations where request_type='membership'";
        	$sql['messages']="select messages.* from messages";
	        $sql['packs']="select packs.*, contributions.viewings_count, contributions.downloads_count, policies.id as policy_id, policies.update_mode, policies.share_mode from packs inner join contributions on packs.id = contributions.contributable_id and contributions.contributable_type='Pack' inner join policies on contributions.policy_id=policies.id";
        	$sql['policies']="select contributions.*, policies.id as policy_id from policies inner join contributions on policies.id=contributions.policy_id";
		$sql['ratings']="select ratings.* from ratings";
	        $sql['remote_pack_entries']="select pack_remote_entries.* from pack_remote_entries";
        	$sql['reviews']="select reviews.* from reviews";
	        $sql['runners']="select taverna_enactors.* from taverna_enactors";
        	$sql['taggings']="select taggings.* from taggings";
	        $sql['tags']="select tags.* from tags";
        	$sql['users']="select users.*, profiles.picture_id, profiles.email as profile_email, profiles.website, profiles.body_html, profiles.field_or_industry, profiles.occupation_or_roles, profiles.organisations, profiles.location_city, profiles.location_country, profiles.interests, profiles.contact_details, pictures.id as avatar_id from users inner join profiles on users.id=profiles.user_id left join pictures on profiles.picture_id=pictures.id";
        	$sql['vocabularies']="select * from vocabularies";
	        $sql['workflows']="select workflows.*, contributions.id as contribution_id, contributions.viewings_count, contributions.downloads_count, policies.id as policy_id, policies.update_mode, policies.share_mode from contributions inner join workflows on contributions.contributable_id=workflows.id inner join policies on contributions.policy_id=policies.id where contributable_type='Workflow'";
        	$sql['workflow_versions']="select workflow_versions.*, workflows.license_id, contributions.id as contribution_id, policies.id as policy_id, policies.update_mode, policies.share_mode from contributions inner join workflows on contributions.contributable_id=workflows.id inner join workflow_versions on workflows.id=workflow_versions.workflow_id inner join policies on contributions.policy_id=policies.id where contributable_type='Workflow'";
	}
        //SQL to RDF mappings
	$mappings['announcements']=array('id'=>'url','title'=>'dcterms:title', 'user_id'=>'&User|mebase:has-announcer', 'created_at'=>'dcterms:created', 'updated_at'=>'dcterms:modified', 'body'=>'mebase:text');
        $mappings['attributions']=array('id'=>'url', 'attributor_type'=>'+attributor_id|meac:attributes', 'attributable_type'=>'+attributable_id|meac:has-attributable', 'created_at'=>'dcterms:created', 'updated_at'=>'dcterms:modified');
       	$mappings['citations']=array('id'=>'url','user_id'=>'&User|mebase:has-annotator','workflow_id'=>'+workflow_version|mebase:annotates','authors'=>'meannot:authors','title'=>'dcterms:title','publication'=>'meannot:publication','published_at'=>'meannot:published-at','accessed_at'=>'meannot:accessed-at','url'=>'meannot:citation-url','meannot:isbn'=>'isbn','issn'=>'meannot:issn','created_at'=>'dcterms:created', 'updated_at'=>'dcterms:modified');
        $mappings['comments']=array('id'=>'url','user_id'=>'&User|mebase:has-annotator','commentable_type'=>'+commentable_id|mebase:annotates','title'=>'dcterms:title','comment'=>'mebase:text','created_at'=>'dcterms:created');
       	$mappings['content_types']=array('id'=>'url','user_id'=>'&User|sioc:has_owner','title'=>'dcterms:title','created_at'=>'dcterms:created','updated_at'=>'dcterms:modified','description'=>'dcterms:description','mime_type'=>'dcterms:type');
        $mappings['creditations']=array('id'=>'url','creditor_type'=>'+creditor_id|meac:credits','creditable_type'=>'+creditable_id|meac:has-creditable','created_at'=>'dcterms:created','updated_at'=>'dcterms:modified');
        $mappings['experiments']=array('id'=>'url','described_by'=>'@&getResourceMapURI|ore:isDescribedBy','described_by_atom'=>'@&getAtomEntryURI|ore:isDescribedBy','title'=>'dcterms:title', 'description'=>'dcterms:description', 'contributor_type'=>'+contributor_id|sioc:has_owner','experiment_manifest'=>'@&getOREAggregatedResources','created_at'=>'dcterms:created', 'updated_at'=>'dcterms:modified');
       	$mappings['favourites']=array('id'=>'url','bookmarkable_type'=>'+bookmarkable_id|mebase:annotates','user_id'=>'&User|mebase:has-annotator','title'=>'dcterms:title','created_at'=>'dcterms:created');
        $mappings['files']=array('id'=>'url','content-url'=>'@-getFileDownloadURL|mebase:content-url','local_name'=>'mebase:filename','contributor_type'=>'+contributor_id|sioc:has_owner','content_type_id'=>'&ContentType|mebase:has-content-type', 'license_id'=>'&License|mebase:has-license', 'created_at'=>'dcterms:created', 'updated_at'=>'dcterms:modified','title'=>'dcterms:title', 'body'=>'dcterms:description','viewings_count'=>'mevd:viewed','downloads_count'=>'mevd:downloaded', 'policy_id'=>'&Policy|mebase:has-policy');
       	$mappings['friendships']=array('id'=>'url','user_id'=>'&User|mebase:has-requester','friend_id'=>'&User|mebase:has-accepter','created_at'=>'dcterms:created','accepted_at'=>'mebase:accepted-at','message'=>'mebase:text');
        $mappings['friendship_invitations']=array('id'=>'url','user_id'=>'&User|mebase:has-requester','friend_id'=>'&User|mebase:has-accepter','created_at'=>'dcterms:created','accepted_at'=>'mebase:accepted-at','message'=>'mebase:text','email'=>'@-mailto_hash|mebase:email','token'=>'@request_token|mebase:request-token');
       	$mappings['groups']=array('id'=>'url','user_id'=>'&User|sioc:has_owner', 'created_at'=>'dcterms:created', 'updated_at'=>'dcterms:modified','title'=>'sioc:name','description'=>'dcterms:description', 'auto_accept'=>'mebase:auto-accept');
        $mappings['group_announcements']=array('id'=>'url','title'=>'dcterms:title','network_id'=>'&Group|mebase:announced-to','user_id'=>'&User|mebase:has-announcer','public'=>'mebase:public-announcement', 'created_at'=>'dcterms:created', 'updated_at'=>'dcterms:modified','body'=>'mebase:text');
       	$mappings['jobs']=array('id'=>'url','described_by'=>'@&getResourceMapURI|ore:isDescribedBy','described_by_atom'=>'@&getAtomEntryURI|ore:isDescribedBy','title'=>'dcterms:title','description'=>'dcterms:description','experiment_id'=>'&Experiment|ore:isAggregatedBy','user_id'=>'&User|sioc:has_owner','runnable'=>'@getRunnable|meexp:has-runnable','runner'=>'@getRunner|meexp:has-runner','submitted_at'=>'meexp:submitted-at','started_at'=>'meexp:started-at','completed_at'=>'meexp:completed-at','last_status'=>'meexp:last-status','last_status_at'=>'meexp:last-status-at','job_uri'=>'@getURI|mebase:uri','job-manifest'=>'meexp:job-manifest','inputs'=>'@%getInput|meexp:has-input','outputs'=>'@%getOutput|meexp:has-output','created_at'=>'dcterms:created','updated_at'=>'dcterms:modified','parent_job_id'=>'&Job|meexp:has-parent-job');
        $mappings['local_pack_entries']=array('id'=>'url','pack_id'=>'&Pack|ore:proxyIn','proxy_for'=>'@getProxyFor|ore:proxyFor','comment'=>'dcterms:description','user_id'=>'&User|sioc:has_owner','created_at'=>'dcterms:created', 'updated_at'=>'dcterms:modified');
       	$mappings['licenses']=array('id'=>'url','unique_name'=>'dcterms:identifier','title'=>'dcterms:title','description'=>'dcterms:description','url'=>'owl:sameAs','user_id'=>'&User|sioc:has_owner','attributes'=>'@getLicenseAttributes|','created_at'=>'dcterms:created','updated_at'=>'dcterms:modified');
       	$mappings['memberships']=array('id'=>'url','requester'=>'@getRequester|mebase:has-requester','accepter'=>'@-getAccepter|mebase:has-accepter','requested_at'=>'@getRequesterTime|dcterms:created','accepted_at'=>'@getAccepterTime|mebase:accepted-at','message'=>'mebase:text');
       	$mappings['membership_invitations']=array('id'=>'url','requester'=>'@getRequester|mebase:has-requester','accepter'=>'@-getAccepter|mebase:has-accepter','created_at'=>'dcterms:created','accepted_at'=>'mebase:accepted-at','message'=>'mebase:text','email'=>'@-mailto_hash|mebase:email','token'=>'@request_token|mebase:request-token');
       	$mappings['messages']=array('id'=>'url','from'=>'&User|mebase:from','to'=>'&User|mebase:to','subject'=>'mebase:subject','body'=>'mebase:text','created_at'=>'dcterms:created','read_at'=>'mebase:read-at','deleted_by_sender'=>'mebase:deleted-by-sender','deleted_by_recepient'=>'mebase:deleted-by-recepient');
        $mappings['packs']=array('id'=>'url','described_by'=>'@&getResourceMapURI|ore:isDescribedBy','described_by_atom'=>'@&getAtomEntryURI|ore:isDescribedBy','manifest'=>'@&getOREAggregatedResources|','contributor_type'=>'+contributor_id|sioc:has_owner','title'=>'dcterms:title', 'description'=>'dcterms:description','created_at'=>'dcterms:created','updated_at'=>'dcterms:modified','viewings_count'=>'mevd:viewed','downloads_count'=>'mevd:downloaded','policy_id'=>'&Policy|mebase:has-policy');
	$mappings['policies']=array('policy_id'=>'url','policy'=>'@&getPolicy|');
       	$mappings['ratings']=array('id'=>'url','rateable_type'=>'+rateable_id|mebase:annotates','rating'=>'meannot:rating-score','user_id'=>'&User|mebase:has-annotator','created_at'=>'dcterms:created');
       	$mappings['reviews']=array('id'=>'url','title'=>'dcterms:title','review'=>'mebase:text','reviewable_type'=>'+reviewable_id|mebase:annotates','user_id'=>'&User|mebase:has-annotator','created_at'=>'dcterms:created','updated_at'=>'dcterms:modified');
        $mappings['remote_pack_entries']=array('id'=>'url','pack_id'=>'&Pack|ore:proxyIn','title'=>'dcterms:title','proxy_for'=>'@%getProxyFor|ore:proxyFor','alternate_uri'=>'rdfs:seeAlso', 'comment'=>'dcterms:description','user_id'=>'&User|sioc:has_owner', 'created_at'=>'dcterms:created', 'updated_at'=>'dcterms:modified');
       	$mappings['runners']=array('id'=>'url','title'=>'dcterms:title','description'=>'dcterms:description','contributor_type'=>'+contributor_id|sioc:has_owner','url'=>'meexp:runner-url','username'=>'mebase:username','created_at'=>'dcterms:created','updated_at'=>'dcterms:modified');
        $mappings['taggings']=array('id'=>'url','tag_id'=>'&Tag|meannot:uses-tag','taggable_type'=>'+taggable_id|mebase:annotates','user_id'=>'&User|mebase:has-annotator','created_at'=>'dcterms:created');
       	$mappings['tags']=array('id'=>'url','name'=>'dcterms:title','created_at'=>'dcterms:created','updated_at'=>'dcterms:modified');
        $mappings['users']=array('id'=>'url','created_at'=>'dcterms:created','updated_at'=>'dcterms:modified','name'=>'sioc:name', 'openid_url'=>'@openid_url|mebase:openid-url','username'=>'@username|mebase:username','body'=>'dcterms:description','avatar_id'=>'@-pictureURL|sioc:avatar','location_city'=>'foaf:based-near', 'location_country'=>'mebase:country','residence'=>'@&getResidence|','website'=>'foaf:homepage', 'last_seen_at'=>'mebase:last-seen-at','email_confirmed_at'=>'mebase:email-confirmed-at','activated_at'=>'mebase:activated-at','receive_notifications'=>'mebase:receive-notifications','profile_email'=>'@-mailto_profile|foaf:mbox','organisation'=>'mebase:organisation','field_or_industry'=>'mebase:field','occupations_or_roles'=>'mebase:occuptation','interests'=>'mebase:interests','contact_details'=>'mebase:contact-details');
        $mappings['vocabularies']=array('id'=>'url','title'=>'dcterms:title','description'=>'dcterms:description','user_id'=>'&User|sioc:has_owner','created_at'=>'dcterms:created','updated_at'=>'dcterms:modified');
       	$mappings['workflows']=array('id'=>'url','title'=>'dcterms:title','body'=>'dcterms:description', 'content_type_id'=>'&ContentType|mebase:has-content-type', 'contributor_type'=>'+contributor_id|sioc:has_owner','created_at'=>'dcterms:created','updated_at'=>'dcterms:modified','filename'=>'@&-getFilename|mebase:filename','described_by'=>'@&getResourceMapURI|ore:isDescribedBy','described_by_atom'=>'@&getAtomEntryURI|ore:isDescribedBy','content-url'=>'@-getWorkflowDownloadURL|mebase:content-url','preview'=>'@-getPreview|mecontrib:preview','thumbnail'=>'@-getThumbnail|mecontrib:thumbnail', 'thumbnail_big'=>'@-getThumbnailBig|mecontrib:thumbnail-big','svg'=>'@-getSVG|mecontrib:svg','current_version'=>'@getCurrentWorkflowVersion|mebase:has-current-version','other_versions'=>'@-getWorkflowVersions|','license_id'=>'&License|mebase:has-license','last_edited_by'=>'&User|mebase:last-edited-by','viewings_count'=>'mevd:viewed','downloads_count'=>'mevd:downloaded','policy_id'=>'&Policy|mebase:has-policy','dataflow'=>'@&-getDataflow|mecomp:executes-dataflow');
       	$mappings['workflow_versions']=array('id'=>'url','title'=>'dcterms:title','body'=>'dcterms:description','content_type_id'=>'&ContentType|mebase:has-content-type', 'workflow_id'=>'&Workflow|dcterms:isVersionOf','version'=>'mebase:version-number','currentversion'=>'@isCurrentVersion|mebase:is-current-version','contributor_type'=>'+contributor_id|sioc:has_owner', 'created_at'=>'dcterms:created', 'updated_at'=>'dcterms:modified','filename'=>'@&-getFilename|mebase:filename','described_by'=>'@&getResourceMapURI|ore:isDescribedBy','described_by_atom'=>'@&getAtomEntryURI|ore:isDescribedBy','content-url'=>'@-getWorkflowDownloadURL|mebase:content-url','preview'=>'@-getPreview|mecontrib:preview', 'thumbnail'=>'@-getThumbnail|mecontrib:thumbnail', 'thumbnail_big'=>'@-getThumbnailBig|mecontrib:thumbnail-big', 'svg'=>'@-getSVG|mecontrib:svg','license_id'=>'&License|mebase:has-license','last_edited_by'=>'&User|mebase:last-edited-by','policy_id'=>'&Policy|mebase:has-policy','dataflow'=>'@&-getDataflow|mecomp:executes-dataflow');
	
/*
	$assocents['announcements']=array('users');
        $assocents['attributions']=array();
        $assocents['citations']=array();
        $assocents['comments']=array();
        $assocents['content_types']=array();
        $assocents['creditations']=array();
        $assocents['experiments']=array('attributions','citations','creditations','favourites','ratings','reviews','taggings');
        $assocents['favourites']=array();
        $assocents['files']=array('attributions','citations','creditations','favourites','ratings','reviews','taggings');
        $assocents['friendships']=array();
        $assocents['friendship_invitations']=array();
        $assocents['jobs']=array();
        $assocents['groups']=array('memberships','membership_invitations','taggings','comments','group_announcements');
        $assocents['group_announcements']=array();
        $assocents['licenses']=array();
        $assocents['local_pack_entries']=array();
        $assocents['memberships']=array();
        $assocents['membership_invitations']=array();
        $assocents['messages']=array();
        $assocents['packs']=array('local_pack_entries','remote_pack_entries','attributions','citations','creditations','favourites','ratings','reviews','taggings');
	$assocents['policies']=array();
        $assocents['ratings']=array();
        $assocents['remote_pack_entries']=array();
        $assocents['reviews']=array();
        $assocents['runners']=array();
        $assocents['tags']=array();
        $assocents['taggings']=array();
        $assocents['users']=array('friendships');
        $assocents['vocabularies']=array('tags');
        $assocents['workflows']=array('attributions','citations','creditations','favourites','ratings','reviews','taggings');
        $assocents['workflow_versions']=array();
*/
?>
