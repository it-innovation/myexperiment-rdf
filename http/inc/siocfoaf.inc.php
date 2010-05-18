<?php
	function siocAccount($user_id,$account_type,$account_url,$profile_url,$username){
                $siocacc="      <sioc:User>\n        <foaf:accountServiceHomepage rdf:resource=\"$account_url\"/>\n";
                if ($profile_url) $siocacc.="        <foaf:accountProfilePage rdf:resource=\"$profile_url\"/>\n";
                $siocacc.="        <foaf:accountName>[hidden]</foaf:accountName>\n      </sioc:User>\n";
                return $siocacc;
        }
        function siocGroup($group_id,$group_name){
                global $datauri;
                $siocgroup="      <sioc:UserGroup rdf:about=\"".$datauri."Group/$group_id\">\n        <sioc:name><![CDATA[$group_name]]></sioc:name>\n  <rdfs:seeAlso rdf:resource=\"http://www.myexperiment.org/groups/$group_id\"/>\n      </sioc:UserGroup>\n";
                return $siocgroup;
        }
	function foafProfile($user_id){
                global $sql, $tables, $foafmap, $datauri, $myexp_inst, $foafurl;
                $type="User";
                $cursql=setRestrict($type,array("id","=",$user_id));
                $res=mysql_query($cursql);
                $row=mysql_fetch_array($res);
                echo foafheader($user_id);
                echo "  <foaf:Person rdf:about=\"".$datauri."Person/$user_id\">\n";
                foreach($row as $field => $data){
//                      echo $field."=$data<br/>";
                        if ($foafmap[$field]) $curmaps[$foafmap[$field]]=$data;
                }
//              print_r($curmaps);
                foreach($curmaps as $field => $data){
                        if (substr($field,0,1)=="@"){
                                $field=substr($field,1);
                //              echo $field."<br/>";
                                $mapbits=explode("|",$field);
                                if ($data) $data=call_user_func($mapbits[0],$data);
                                $field=$mapbits[1];
                        }
                        if (is_array($data)){
                                foreach ($data as $num => $val){
                                        if ($val) echo "    <$field>$val</$field>\n";
                                }
                        }
                        elseif ($data && $field!="username" && $field!="openid"){
                                if (substr($data,0,7)=="http://" || substr($data,0,7)=="mailto:") echo "    <$field rdf:resource=\"$data\" />\n";
                                else echo "    <$field>$data</$field>\n";
                        }
                }
                if ($curmaps['username'])echo "    <foaf:holdsAccount>". siocAccount($user_id,"myexperiment",$datauri,$myexp_inst."users/$user_id",$curmaps['username'])."</foaf:holdsAccount>\n";
                $tlds=array("co","com","org","gov","ac","edu","me");
                if ($curmaps['openid']){
                        $print=0;
                        $oidbits=explode(".",$curmaps['openid']);
                        $account_url="http://";
                        for ($o=0; $o<sizeof($oidbits); $o++){
                                if (in_array(str_replace("/","",$oidbits[$o+1]),$tlds)) $print=1;
                                        if ($print) $account_url.=$oidbits[$o].".";
                        }
                        $account_url=substr($account_url,0,-1);
                        echo "    <foaf:holdsAccount>".siocAccount($user_id,"openid",$account_url,"",$curmaps['openid'])."</foaf:holdsAccount>\n";
                }
                $curgsql="select distinct networks.* from networks inner join memberships on networks.id=memberships.network_id where memberships.user_id=$user_id and network_established_at IS NOT NULL and user_established_at IS NOT NULL";
                //echo $curgsql;
                $gres=mysql_query($curgsql);
                for ($g=0; $g<mysql_num_rows($gres); $g++){
                        echo "    <sioc:member_of>\n".siocGroup(mysql_result($gres,$g,'id'),mysql_result($gres,$g,'title'))."    </sioc:member_of>\n";
                }
		$curfsql="select users.id as Person, users.name, users.email as mbox_sha1sum, profiles.email as mbox, profiles.website as homepage from users inner join profiles on users.id=profiles.user_id inner join friendships on friendships.user_id=users.id or friendships.friend_id=users.id where friendships.accepted_at IS NOT NULL and (friendships.user_id=$user_id or friendships.friend_id=$user_id) and users.id!=$user_id";
                $fres=mysql_query($curfsql);
                $fnum=mysql_num_rows($fres);
                for ($f=0; $f<$fnum; $f++){
                        $friend=mysql_fetch_array($fres);
                        echo "    <foaf:knows>\n      <foaf:Person>\n";
                        foreach ($friend as $field => $data){
                                if (!is_numeric($field) && $data && $field!="Person"){
                                         if (substr($data,0,7)=="http://") echo "        <foaf:$field rdf:resource=\"$data\" />\n";
                                         elseif ($field=="mbox") echo "<foaf:$field rdf:resource=\"mailto:$data\" />\n";
                                         elseif ($field=="mbox_sha1sum")  echo "        <foaf:$field>".sha1($data)."</foaf:$field>\n";
                                         else echo "        <foaf:$field>$data</foaf:$field>\n";
                                }
                        }
                        echo "        <foaf:seeAlso rdf:resource=\"".$foafurl.$friend['Person']."\"/>\n      </foaf:Person>\n    </foaf:knows>\n";
                }
                echo "  </foaf:Person>\n\n";
                echo pagefooter();
        }
?>
