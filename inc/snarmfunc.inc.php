<?php
	
	function getContributorPermissions($contrib_type,$contrib_id,$policy_url){
		global $datauri, $urient, $modelalias;
		if (isset($modelalias[$contrib_type])) $contrib_type=$modelalias[$contrib_type];
		$contributor=$urient[$contrib_type]."/".$contrib_id;
		return "    <snarm:has-access>\n      <snarm:RestrictedAccess>\n        <snarm:has-accesser rdf:resource=\"".$datauri."$contributor\"/>\n        <snarm:has-access-type rdf:resource=\"&mespec;View\"/>\n      </snarm:RestrictedAccess>\n    </snarm:has-access>\n    <snarm:has-access>\n      <snarm:RestrictedAccess>\n        <snarm:has-accesser rdf:resource=\"".$datauri."$contributor\"/>\n        <snarm:has-access-type rdf:resource=\"&mespec;Download\"/>\n      </snarm:RestrictedAccess>\n    </snarm:has-access>\n    <snarm:has-access>\n      <snarm:RestrictedAccess>\n        <snarm:has-accesser rdf:resource=\"".$datauri."$contributor\"/>\n        <snarm:has-access-type rdf:resource=\"&mespec;Edit\"/>\n      </snarm:RestrictedAccess>\n    </snarm:has-access>\n";
	}
	function getShareModeAccesses($sm){
		switch ($sm){
		case 0:
			return "    <snarm:has-access rdf:resource=\"&mespec;PublicView\"/>\n    <snarm:has-access rdf:resource=\"&mespec;PublicDownload\"/>\n";
		case 1:  
			return "    <snarm:has-access rdf:resource=\"&mespec;PublicView\"/>\n    <snarm:has-access rdf:resource=\"&mespec;FriendsDownload\"/>\n";
		case 2:
			return "    <snarm:has-access rdf:resource=\"&mespec;PublicView\"/>\n";
		case 3:
			return "    <snarm:has-access rdf:resource=\"&mespec;FriendsView\"/>\n    <snarm:has-access rdf:resource=\"&mespec;FriendsDownload\"/>\n";
		case 4;
                        return "    <snarm:has-access rdf:resource=\"&mespec;FriendsView\"/>\n";
		default: 
			return "";
		}
		return "";
	}
	function getUpdateModeAccesses($um,$sm){
		switch ($um){
		case 0:
			if ($sm==0 && is_int($sm)) return "    <snarm:has-access rdf:resource=\"&mespec;PublicEdit\"/>\n";
			elseif ($sm==1 || $sm==3) return "    <snarm:has-access rdf:resource=\"&mespec;FriendsEdit\"/>\n";
		case 1:
			return "    <snarm:has-access rdf:resource=\"&mespec;FriendsEdit\"/>\n";
		default:
			return "";
		}
		return "";
	}
			
	function getPermissions($policy){
		$permsql="select * from permissions where policy_id=".$policy;
		$permres=mysql_query($permsql);
		$perms=array();
		for ($p=0; $p<mysql_num_rows($permres); $p++){
			$perms[$p]=mysql_fetch_array($permres);
		}
		return $perms;
	}
	function addShareAndUpdateMode($contrib){
		$policysql="select share_mode, update_mode from policies where id =".$contrib['policy_id'];
		$pres=mysql_query($policysql);
		$contrib['share_mode']=mysql_result($pres,0,'share_mode');
	        $contrib['update_mode']=mysql_result($pres,0,'update_mode');
		return $contrib;
	}
	function getPermissionAccesses($perms,$policy_url){
		global $datauri, $urient, $modelalias;
		$accesses="";
		$a=3;
		for ($p=0; $p<sizeof($perms); $p++){
			if ($alias=array_search($perms[$p]['contributor_type'],$modelalias)) $perms[$p]['contributor_type']=$alias;
			if ($perms[$p]['view']){
				$a++;
				$accesses.="    <snarm:has-access>\n      <snarm:RestrictedAccess>\n        <snarm:has-accesser rdf:resource=\"".$datauri.$urient[$perms[$p]['contributor_type']]."/".$perms[$p]['contributor_id']."\"/>\n        <snarm:has-access-type rdf:resource=\"&mespec;View\"/>\n      </snarm:RestrictedAccess>\n    </snarm:has-access>\n";
			}
			if ($perms[$p]['download']){
				$a++;
 				$accesses.="    <snarm:has-access>\n      <snarm:RestrictedAccess>\n        <snarm:has-accesser rdf:resource=\"".$datauri.$urient[$perms[$p]['contributor_type']]."/".$perms[$p]['contributor_id']."\"/>\n        <snarm:has-access-type rdf:resource=\"&mespec;Download\"/>\n      </snarm:RestrictedAccess>\n    </snarm:has-access>\n";
			}
			if ($perms[$p]['edit']){
				$a++;
 				$accesses.="    <snarm:has-access>\n      <snarm:RestrictedAccess>\n        <snarm:has-accesser rdf:resource=\"".$datauri.$urient[$perms[$p]['contributor_type']]."/".$perms[$p]['contributor_id']."\"/>\n        <snarm:has-access-type rdf:resource=\"&mespec;Edit\"/>\n      </snarm:RestrictedAccess>\n    </snarm:has-access>\n";
			}
		}
		return $accesses;
	}
	function getPolicy($contrib,$type=''){
		$policy_url=getEntityURI("policies",$contrib['policy_id'],$contrib);
		$policy="";
	        if ($type!="policies") $policy.="<snarm:Policy rdf:about=\"$policy_url\">\n";
	        $perms=getPermissions($contrib['policy_id']);
		if (!isset($contrib['share_mode'])) $contrib=addShareAndUpdateMode($contrib);
	        $policy.=getContributorPermissions($contrib['contributor_type'],$contrib['contributor_id'],$policy_url);
	        $policy.=getShareModeAccesses($contrib['share_mode']);
	        $policy.=getUpdateModeAccesses($contrib['update_mode'],$contrib['share_mode'],$perms);
	        $policy.=getPermissionAccesses($perms,$policy_url);
	        if ($type!="policies") $policy.="      </snarm:Policy>";
		return $policy;
	}
?>
