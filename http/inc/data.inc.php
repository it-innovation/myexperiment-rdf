<?php
	include('settings.inc.php');
	include('datatypes.inc.php');
	require('privatedata.inc.php');
	require('publicdata.inc.php');

	//Get types for datatype properties
        $datatypes=array_merge(getExternalDatatypeProperties(),getInternalDatatypeProperties());

	//ORE Stuff
	$all_ars=array();
	$aggregateprops=array("mecontrib:preview","mecontrib:thumbnail","mecontrib:thumbnail-big","mecontrib:svg","mebase:has-version","mebase:has-current-version","mebase:content-url","mebase:human-start-page","meexp:has-input","meexp:has-output","meexp:has-runner","meexp:has-runnable","mebase:uri");
	$aggregateclasses=array("File","Workflow","WorkflowVersion","Pack","Experiment","Job");

	//Clauses for SQL Statements
        $pubcond="policies.share_mode in (0,1,2)";
        $protcond="or ((permissions.contributor_id=? and permissions.contributor_type='User' and permissions.view=1) or (permissions.contributor_id in (~) and permissions.contributor_type='Network' and permissions.view=1) or (contributions.contributor_id=? and contributions.contributor_type='User'))";
        $policy_join="join policies on contributions.policy_id=policies.id left join permissions on policies.id=permissions.policy_id";

	//Add SQL and Mappings for Appropriate Domain
	if ($domain=="private"){
		$sql=getPrivateSQL();
		$mappings=getPrivateMappings();
		$prefixes=getPrivatePrefixes();
	}
	elseif($domain=="protected"){
		$sql=getPrivateSQL();
		$mappings=getPublicMappings();
		$prefixes=getPublicPrefixes();
		include('protecteddata.inc.php');
	}
	else{
		$sql=getPublicSQL();
		$mappings=getPublicMappings();
	}
	
	//Entities to SQL table mappings	
	$tables['announcements']="announcements";
	$tables['attributions']="attributions";
	$tables['citations']="citations";
 	$tables['comments']="comments";
	$tables['content_types']="content_types";
	$tables['creditations']="creditations";
	$tables['downloads']="downloads";
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
	$tables['ratings']="ratings";
	$tables['remote_pack_entries']="pack_remote_entries";
	$tables['reviews']="reviews";
	$tables['runners']="taverna_enactors";
	$tables['tags']="tags";
	$tables['taggings']="taggings";
	$tables['users']="users";
	$tables['viewings']="viewings";
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
        $ontent['downloads']="Downloads";
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
        $ontent['ratings']="Rating";
        $ontent['remote_pack_entries']="RemotePackEntry";
        $ontent['reviews']="Review";
        $ontent['runners']="TavernaEnactor";
        $ontent['tags']="Tag";
        $ontent['taggings']="Tagging";
        $ontent['users']="User";
        $ontent['viewings']="Viewings";
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
	$entityns['downloads']="mevd";
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
	$entityns['ratings']="meannot";
	$entityns['remote_pack_entries']="mepack";
	$entityns['reviews']="meannot";
	$entityns['runners']="mespec";
	$entityns['tags']="meannot";
	$entityns['taggings']="meannot";
	$entityns['users']="mebase";
	$entityns['viewings']="mevd";
	$entityns['vocabularies']="mecontrib";
	$entityns['workflows']="mecontrib";
	$entityns['workflow_versions']="mecontrib";

	//SPARQL query triples for describe queries
	$desqs['Components']= "<~> mecomp:has-component ?x";
        $desqs['Output']="<~> meexp:has-output ?x";
        $desqs['Input']="<~> meexp:has-input ?x";
	$desqs['Questions']="?x rdf:type <".$datauri."ontologies/questions/Question>";
	$desqs['QuestionTypes']="?x rdf:type <".$datauri."ontologies/questions/QuestionType>";
	$desqs['Parsers']="?x rdf:type <".$datauri."ontologies/questions/Parser>";
	$desqs['Parsings']="?x rdf:type <".$datauri."ontologies/questions/Parsing>";
	$desqs['NodeTypes']="?x rdf:type <".$datauri."ontologies/wordnet/NodeType>";
	$desqs['MappingTypes']="?x rdf:type <".$datauri."ontologies/wordnet/MappingType>";
	$desqs['MergeRules']="?x rdf:type <".$datauri."ontologies/questions/MergeRule>";
	$desqs['WordMappings']="{ ?x rdf:type mewn:Noun } UNION {?x rdf:type mewn:Adjective } UNION {?x rdf:type mewn:Verb } UNION {?x rdf:type mewn:Adverb }";

	$desqs['Question']="?x rdf:type <".$datauri."ontologies/questions/Question> . FILTER(REGEX(STR(?x),'~'))";
	$desqs['QuestionRO']="?x ore:aggregates ?y . ?y mewn:has-network-component ?z . FILTER(REGEX(STR(?x),'~'))";
	$desqsvars['QuestionRO']="?x ?y ?z";
        $desqs['QuestionType']="?x rdf:type <".$datauri."ontologies/questions/QuestionType> . FILTER(REGEX(STR(?x),'~'))";
        $desqs['Parser']="?x rdf:type <".$datauri."ontologies/questions/Parser> . FILTER(REGEX(STR(?x),'~'))";
        $desqs['Parsing']="?x rdf:type <".$datauri."ontologies/questions/Parsing> . FILTER(REGEX(STR(?x),'~'))";
        $desqs['NodeType']="?x rdf:type <".$datauri."ontologies/wordnet/NodeType> . FILTER(REGEX(STR(?x),'~'))";
        $desqs['MappingType']="?x rdf:type <".$datauri."ontologies/wordnet/MappingType> . FILTER(REGEX(STR(?x),'~'))";
        $desqs['MergeRule']="?x rdf:type <".$datauri."ontologies/questions/MergeRule> . FILTER(REGEX(STR(?x),'~'))";
	$desqs['WordMapping']="{?x rdf:type mewn:WordMapping}  UNION {?x rdf:type mewn:MappedNetwork }  UNION {?x rdf:type mewn:Node} UNION {?x rdf:type mewn:Link} . FILTER(REGEX(STR(?x),'~'))";


	//Special types that do not originate from the mysql database and their grouping values
	$specialtypes=array("Question","QuestionRO","QuestionType","MappingType","Parser","PartOfSpeech","NodeType","MergeRule","WordMapping","WordMappingTemplate");
	$groups['Questions']="Question";
	$groups['QuestionTypes']="QuestionType";
	$groups['MappingTypes']="MappingType";
	$groups['Parsers']="Parser";
	$groups['PartsOfSpeech']="PartOfSpeech";
	$groups['NodeTypes']="NodeType";
	$groups['MergeRules']="MergeRule";
	$groups['WordMappings']="WordMapping";
	$groups['WordMappingTemplates']="WordMappingTemplate";
	
?>
