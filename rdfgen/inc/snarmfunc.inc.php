<?php

	function getContributorPermissions($contrib_type,$contrib_id,$policy_url){
		global $datauri;
		$access_ent=get_access_entity();
		$contrib_type_fpr_uris=array('Group'=>'groups','User'=>'users');
		if ($contrib_type=="Network") $contrib_type="Group";
		$contributor=$contrib_type_fpr_uris[$contrib_type]."/".$contrib_id;
		return "        <snarm:has-access>\n          <snarm:RestrictedAccess>\n            <snarm:has-accesser rdf:resource=\"".$datauri."$contributor\"/>\n            <snarm:has-access-type rdf:resource=\"".$access_ent."View\"/>\n          </snarm:RestrictedAccess>\n        </snarm:has-access>\n        <snarm:has-access>\n          <snarm:RestrictedAccess>\n            <snarm:has-accesser rdf:resource=\"".$datauri."$contributor\"/>\n            <snarm:has-access-type rdf:resource=\"".$access_ent."Download\"/>\n          </snarm:RestrictedAccess>\n        </snarm:has-access>\n        <snarm:has-access>\n          <snarm:RestrictedAccess>\n            <snarm:has-accesser rdf:resource=\"".$datauri."$contributor\"/>\n            <snarm:has-access-type rdf:resource=\"".$access_ent."Edit\"/>\n          </snarm:RestrictedAccess>\n        </snarm:has-access>\n";
	}
	function getShareModeAccesses($sm){
		$access_ent=get_access_entity();
//		return "<myexp:sharemode>$sm</myexp:sharemode>";
		switch ($sm){
		case 0:
			return "        <snarm:has-access rdf:resource=\"".$access_ent."PublicView\"/>\n        <snarm:has-access rdf:resource=\"".$access_ent."PublicDownload\"/>\n";
		case 1:  
			return "        <snarm:has-access rdf:resource=\"".$access_ent."PublicView\"/>\n        <snarm:has-access rdf:resource=\"".$access_ent."FriendsDownload\"/>\n";
		case 2:
			return "        <snarm:has-access rdf:resource=\"".$access_ent."PublicView\"/>\n";
		case 3:
			return "        <snarm:has-access rdf:resource=\"".$access_ent."FriendsView\"/>\n        <snarm:has-access rdf:resource=\"".$access_ent."FriendsDownload\"/>\n";
		case 4:
                        return "        <snarm:has-access rdf:resource=\"".$access_ent."FriendsView\"/>\n";
		default: 
			return "";
		}
		return "";
	}
	function getUpdateModeAccesses($um,$sm){
		$access_ent=get_access_entity();
//		return "<myexp:updatemode>$um</myexp:updatemode>";
		switch ($um){
		case 0:
			if ($sm==0 && is_int($sm)) return "        <snarm:has-access rdf:resource=\"".$access_ent."PublicEdit\"/>\n";
			elseif ($sm==1 || $sm==3) return "        <snarm:has-access rdf:resource=\"".$access_ent."FriendsEdit\"/>\n";
		case 1:
			return "        <snarm:has-access rdf:resource=\"".$access_ent."FriendsEdit\"/>\n";
		default:
			return "";
		}
		return "";
	}
			
	function getPermissions($policy){
		$permsql="select * from permissions where policy_id=".$policy;
		//echo "<!-- $permsql -->\n";
		$permres=mysql_query($permsql);
		for ($p=0; $p<mysql_num_rows($permres); $p++){
			$perms[$p]=mysql_fetch_array($permres);
		}
		return $perms;
	}
	function getPermissionAccesses($perms,$policy_url){
		global $datauri;
		$access_ent=get_access_entity();
		$accesses="";
		$a=3;
		for ($p=0; $p<sizeof($perms); $p++){
			if ($perms[$p]['contributor_type']=="Network") $perms[$p]['contributor_type']="groups";
			if ($perms[$p]['view']){
				$a++;
				$accesses.="      <snarm:has-access>\n        <snarm:RestrictedAccess rdf:about=\"$policy_url/accesses/View".$perms[$p]['contributor_type'].$perms[$p]['contributor_id']."\">\n          <snarm:has-accesser rdf:resource=\"".$datauri.$perms[$p]['contributor_type']."/".$perms[$p]['contributor_id']."\"/>\n          <snarm:has-access-type rdf:resource=\"".$access_ent."View\"/>\n        </snarm:RestrictedAccess>\n      </snarm:has-access>\n";
			}
			if ($perms[$p]['download']){
				$a++;
 				$accesses.="      <snarm:has-access>\n        <snarm:RestrictedAccess rdf:about=\"$policy_url/accesses/Download".$perms[$p]['contributor_type'].$perms[$p]['contributor_id']."\">\n          <snarm:has-accesser rdf:resource=\"".$datauri.$perms[$p]['contributor_type']."/".$perms[$p]['contributor_id']."\"/>\n          <snarm:has-access-type rdf:resource=\"".$access_ent."Download\"/>\n        </snarm:RestrictedAccess>\n      </snarm:has-access>\n";
			}
			if ($perms[$p]['edit']){
				$a++;
 				$accesses.="      <snarm:has-access>\n        <snarm:RestrictedAccess rdf:about=\"$policy_url/accessed/Edit".$perms[$p]['contributor_type'].$perms[$p]['contributor_id']."\">\n          <snarm:has-accesser rdf:resource=\"".$datauri.$perms[$p]['contributor_type']."/".$perms[$p]['contributor_id']."\"/>\n          <snarm:has-access-type rdf:resource=\"".$access_ent."Edit\"/>\n        </snarm:RestrictedAccess>\n      </snarm:has-access>\n";
			}
		}
		return $accesses;
	}
	function getPolicy($contrib,$type=''){
		global $datauri, $unmodularized,$myexp_inst;
//        	$policy="<!-- POLICY: $contrib[policy_id] -->\n";
		//print_r($contrib);
		$policy_url=$datauri."policies/".$contrib['policy_id'];
	        if ($type!="policies") $policy.="<snarm:Policy rdf:about=\"".$datauri."policies/".$contrib['policy_id']."\">\n";
	        $perms=getPermissions($contrib['policy_id']);
	        $policy.=getContributorPermissions($contrib['contributor_type'],$contrib['contributor_id'],$policy_url);
	        $policy.=getShareModeAccesses($contrib['share_mode']);
	        $policy.=getUpdateModeAccesses($contrib['update_mode'],$contrib['share_mode'],$perms);
	        $policy.=getPermissionAccesses($perms,$policy_url);
	        if ($type!="policies") $policy.="      </snarm:Policy>";
		return $policy;
	}
	function get_access_entity(){
		global $unmodularizedf;
		if ($unmodularized) return "&medata;";
		return "&mespec;";
	}
	
?>
