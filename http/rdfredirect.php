<?php
	include('include.inc.php');
	include('data.inc.php');
	include('myexpconnect.inc.php');
	include('functions.inc.php');
	
	//Singulars
        $singulars['Announcements']="Announcement";
        $singulars['Attributions']="Attribution";
        $singulars['Citations']="Citation";
        $singulars['Comments']="Comment";
        $singulars['CsingularsTypes']="CsingularsType";
        $singulars['Creditations']="Creditation";
        $singulars['Experiments']="Experiment";
        $singulars['Favourites']="Favourite";
        $singulars['Files']="File";
        $singulars['Friendships']="Friendship";
        $singulars['FriendshipInvitations']="FriendshipInvitation";
        $singulars['Jobs']="Job";
        $singulars['Groups']="Group";
        $singulars['GroupAnnouncements']="GroupAnnouncement";
        $singulars['Licenses']="License";
        $singulars['Local_pack_entries']="LocalPackEntry";
        $singulars['Memberships']="Membership";
        $singulars['MembershipInvitations']="MembershipInvitation";
        $singulars['Messages']="Message";
        $singulars['Packs']="Pack";
        $singulars['Ratings']="Rating";
        $singulars['RemotePackEntries']="RemotePackEntry";
        $singulars['Reviews']="Review";
        $singulars['TavernaEnactors']="TavernaEnactor";
        $singulars['Tags']="Tag";
        $singulars['Taggings']="Tagging";
        $singulars['Users']="User";
        $singulars['Vocabularies']="Vocabulary";
        $singulars['Workflows']="Workflow";
        $singulars['WorkflowVersions']="WorkflowVersion";

	//New URI type
        $newuritype['Announcement']="announcements";
        $newuritype['Attribution']="attributions";
        $newuritype['Citation']="citations";
        $newuritype['Comment']="comments";
        $newuritype['CnewuritypeType']="cnewuritype_types";
        $newuritype['Creditation']="creditations";
        $newuritype['Experiment']="experiments";
        $newuritype['Favourite']="favourites";
        $newuritype['File']="files";
        $newuritype['Friendship']="friendships";
        $newuritype['FriendshipInvitation']="friendship_invitations";
        $newuritype['Job']="jobs";
        $newuritype['Group']="groups";
        $newuritype['GroupAnnouncement']="group_announcements";
        $newuritype['License']="licenses";
        $newuritype['LocalPackEntry']="local_pack_entries";
        $newuritype['Membership']="memberships";
        $newuritype['MembershipInvitation']="membership_invitations";
        $newuritype['Message']="messages";
        $newuritype['Pack']="packs";
        $newuritype['Rating']="ratings";
        $newuritype['RemotePackEntry']="remote_pack_entries";
        $newuritype['Review']="reviews";
        $newuritype['TavernaEnactor']="taverna_enactors";
        $newuritype['Tag']="tags";
        $newuritype['Tagging']="taggings";
        $newuritype['User']="users";
        $newuritype['Vocabulary']="vocabularies";
        $newuritype['Workflow']="workflows";
        $newuritype['WorkflowVersion']="workflow_versions";

	
	$uribits=explode("/",substr($_SERVER['REQUEST_URI'],1));
	if ($singulars[$uribits[0]]) $uribits[0]=$singulars[$uribits[0]];
	$uribits[0]=$newuritype[$uribits[0]];
	if ($nesting[$uribits[0]]){
		if (stripos($sql[$uribits[0]],'where')>0) $esql=$sql[$uribits[0]]." and ".$tables[$uribits[0]].".id=$uribits[1]";
		else $esql=$sql[$uribits[0]]." where ".$tables[$uribits[0]].".id=$uribits[1]";
		$eres=mysql_query($esql);	
		$newuri=getEntityURI($uribits[0],$uribits[1],mysql_fetch_assoc($eres)).".rdf";
	}
	elseif ($uribits[0]=="group_announcements" and $uribits[1]){
		$cursql=$sql[$uribits[0]]." where id=$uribits[1]";
		$res=mysql_query($cursql);
		if (mysql_num_rows($res)==0){
			header("HTTP/1.1 404 Not Found");	
			exit();
		}
		$id=$uribits[1];
		$uribits[0]="groups";
		$uribits[1]=mysql_result($res,0,'network_id');
		$uribits[2]="announcements";
		$uribits[3]=$id;
	}
	elseif ($uribits[0]=="workflow_versions" and $uribits[1]){	
		$cursql=$sql[$uribits[0]]." and $uribits[0].id=$uribits[1]";
                $res=mysql_query($cursql);
                if (mysql_num_rows($res)==0){
                        header("HTTP/1.1 404 Not Found");
                        exit();
                }
                $uribits[0]="workflows";
                $uribits[1]=mysql_result($res,0,'workflow_id');
                $uribits[2]="versions";
                $uribits[3]=mysql_result($res,0,'version');
	}
	if (!$newuri) $newuri=$datauri.implode("/",$uribits).".rdf";

	header("HTTP/1.1 301 301 Moved Permanently");
	header("Location: $newuri");
?>
