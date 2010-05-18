<?php
	//Extensions to Private SQL statements required to make them Protetected (i.e. accesible to those with permission)
        $protectedex['announcements']="";
        $protectedex['attributions']=" inner join contributions on attributions.attributor_id=contributions.contributable_id and attributions.attributor_type=contributions.contributable_type inner $policy_join where ($pubcond $protcond)";
        $protectedex['citations']=" inner join contributions on citations.workflow_id=contributions.contributable_id and contributions.contributable_type='Workflow' inner $policy_join where ($pubcond $protcond)";
	$protectedex['content_types']="";
        $protectedex['creditations']=" inner join contributions on creditations.creditable_id=contributions.contributable_id and creditations.creditable_type=contributions.contributable_type inner $policy_join where ($pubcond $protcond)";
        $protectedex['experiments']=" where ((contributor_id=? and contributor_type='User') or (contributor_id in (~) and contributor_type='Network'))";
        $protectedex['favourites']=" inner join contributions on bookmarks.bookmarkable_id=contributions.contributable_id and bookmarks.bookmarkable_type=contributions.contributable_type inner $policy_join where ($pubcond $protcond)";
        $protectedex['friendships']="";
        $protectedex['groups']="";
        $protectedex['group_announcements']=" where (public=1 or network_id in (~) or user_id=?)";
	$protectedex['licenses']="";
        $protectedex['jobs']=" inner join experiments on jobs.experiment_id=experiments.id where ((experiments.contributor_id=? and experiments.contributor_type='User') or (experiments.contributor_id in (~) and experiments.contributor_type='Network'))";
        $protectedex['local_pack_entries']=" inner join packs on pack_contributable_entries.pack_id=packs.id inner join contributions on packs.id=contributions.contributable_id and contributions.contributable_type='Pack' inner $policy_join where ($pubcond $protcond)";
        $protectedex['memberships']="";
        $protectedex['messages']=" where (`to`=? or `from`=?)";
        $protectedex['packs']=" left join permissions on policies.id=permissions.policy_id where ($pubcond $protcond)";
        $protectedex['ratings']=" inner join contributions on ratings.rateable_id=contributions.contributable_id and ratings.rateable_type=contributions.contributable_type inner $policy_join where ($pubcond $protcond)";
        $protectedex['remote_pack_entries']=" inner join packs on pack_remote_entries.pack_id=packs.id inner join contributions on packs.id=contributions.contributable_id and contributions.contributable_type='Pack' inner $policy_join where ($pubcond $protcond)";
        $protectedex['reviews']=" inner join contributions on reviews.reviewable_id=contributions.contributable_id and reviews.reviewable_type=contributions.contributable_type inner $policy_join where ($pubcond $protcond)";
        $protectedex['runners']=" where ((contributor_id=? and contributor_type='User') or (contributor_id in (~) and contributor_type='Network'))";
        $protectedex['taggings']="  left join contributions on taggings.taggable_id=contributions.contributable_id and taggings.taggable_type=contributions.contributable_type left $policy_join where ($pubcond $protcond or taggings.taggable_type='Network')";
        $protectedex['tags']="";
        $protectedex['users']="";
	$protectedex['vocabularies']=" where user_id=?";
        $protectedex['workflows']="";

	//Protected SQL statements (Where Private SQL cannot be extended.  Later used to hold all Protected SQL statements)
        $protectedsql['comments']="select distinct comments.* from comments left join contributions on comments.commentable_id=contributions.contributable_id and comments.commentable_type=contributions.contributable_type left $policy_join where ($pubcond or comments.commentable_type='Network' $protcond) and comments.commentable_type in ('Workflow','Pack','Blob','Network')";
        $protectedsql['files']="select distinct blobs.*, contributions.id as contribution_id, contributions.viewings_count, contributions.downloads_count, policies.id as policy_id, policies.update_mode, policies.share_mode from blobs inner join contributions on contributions.contributable_id=blobs.id inner $policy_join where contributable_type='Blob' and ($pubcond $protcond)";
        $protectedsql['downloads']="select distinct count(*) as downloads_count, downloads.user_id, downloads.id, downloads.user_agent, downloads.accessed_from_site, downloads.contribution_id, contributions.contributable_type, contributions.contributable_id, TIMESTAMP(MIN(downloads.created_at)) as created_at,  TIMESTAMP(MAX(downloads.created_at)) as updated_at from downloads inner join contributions on downloads.contribution_id=contributions.id inner $policy_join where ($pubcond $protcond) group by contribution_id, user_id, user_agent, accessed_from_site";
        $protectedsql['viewings']="select distinct count(*) as viewings_count, viewings.user_id, viewings.id, viewings.user_agent, viewings.accessed_from_site, viewings.contribution_id, contributions.contributable_type, contributions.contributable_id, TIMESTAMP(MIN(viewings.created_at)) as created_at,  TIMESTAMP(MAX(viewings.created_at)) as updated_at from viewings inner join contributions on viewings.contribution_id=contributions.id inner $policy_join where ($pubcond $protcond) group by contribution_id, user_id, user_agent, accessed_from_site";
        $protectedsql['workflows']="select distinct workflows.*, contributions.id as contribution_id, contributions.viewings_count, contributions.downloads_count, policies.id as policy_id, policies.update_mode, policies.share_mode from contributions inner join workflows on contributions.contributable_id=workflows.id inner $policy_join where contributable_type='Workflow' and ($pubcond $protcond)";
        $protectedsql['workflow_versions']="select distinct workflow_versions.*, workflows.license_id, contributions.id as contribution_id, policies.id as policy_id, policies.update_mode, policies.share_mode from contributions inner join workflows on contributions.contributable_id=workflows.id inner join workflow_versions on workflows.id=workflow_versions.workflow_id inner $policy_join where contributable_type='Workflow' and ($pubcond $protcond)";
function addUserAndGroupsToSQL($cursql,$userid,$ingroups){
	return str_replace(array('?','~'),array($userid,$ingroups),$cursql);
}
function expandProtectedSQL($protectedsql,$privatesql,$protectedex){	
        $enttypes=array_keys($privatesql);
        foreach($enttypes as $etype){
                if (!$protectedsql[$etype]){
                        $protectedsql[$etype]=str_replace("select","select distinct",$privatesql[$etype]).$protectedex[$etype];
                }
        }
        return $protectedsql;
}
$sql=expandProtectedSQL($protectedsql,$sql,$protectedex);

function getProtectedPrefixes(){
        return "PREFIX snarm: <http://rdf.myexperiment.org/ontologies/snarm/>
PREFIX mebase: <http://rdf.myexperiment.org/ontologies/base/>
PREFIX meannot: <http://rdf.myexperiment.org/ontologies/annotations/>
PREFIX meac: <http://rdf.myexperiment.org/ontologies/attrib_credit/>
PREFIX meexp: <http://rdf.myexperiment.org/ontologies/experiments/>
PREFIX mepack: <http://rdf.myexperiment.org/ontologies/packs/>
PREFIX mecontrib: <http://rdf.myexperiment.org/ontologies/contributions/>
PREFIX mevd: <http://rdf.myexperiment.org/ontologies/viewings_downloads/>
PREFIX mecomp: <http://rdf.myexperiment.org/ontologies/components/>
PREFIX mewn: <http://rdf.myexperiment.org/ontologies/wordnet/>
PREFIX meq: <http://rdf.myexperiment.org/ontologies/questions/>
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
}

?>
