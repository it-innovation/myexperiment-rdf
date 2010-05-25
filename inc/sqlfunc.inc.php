<?php
function entityExists($type,$ids=array()){
        global $tables;
        if (!$tables[$type]) return 0;
        if (!is_array($ids)) $ids=array($ids);
        $cursql="select * from ".$tables[$type];
        if (sizeof($ids)>0 && $ids[0]>0){
                $idstr="";
                foreach($ids as $id) $idstr.="$id,";
                $cursql.=" where id in (".substr($idstr,0,-1).")";
        }
	else{
		$res=mysql_query($cursql);
		if ($res) return 1;
	}
	$res=mysql_query($cursql);
        return mysql_num_rows($res);
}
function setUserAndGroups($sql,$userid=0,$ingroups=0){
        return str_replace(array('?','~'),array($userid,$ingroups),$sql);
}
function setRestrict($type,$wclause=array(),$userid=0,$ingroups=0){
        global $mappings, $tables, $sql, $domain;
        for ($w=0; $w<sizeof($wclause); $w=$w+3){
                if (!strpos($wclause[$w],'.')) $wclause[$w]=$tables[$type].".".$wclause[$w];
                if ($wclause[$w+1]=="=" || $wclause[$w+1]=="like") $wclause[$w+2]="'".$wclause[$w+2]."'";
                else if ($wclause[$w+1]=="in") $wclause[$w+2]="(".$wclause[$w+2].")";
                $whereclause.=" ".$wclause[$w]." ".$wclause[$w+1]." ".$wclause[$w+2];
                if ($w<sizeof($wclause)-3) $whereclause.=" and";
        }
        $cursql=$sql[$type];
        $csqlbits=spliti("group by",$cursql);
        if (stripos($cursql,"where")) $whereand="and";
        else $whereand="where";
        if (sizeof($csqlbits) > 1) $csqlbits[1]="group by ".$csqlbits[1];
        if ($whereclause) $retsql=$csqlbits[0]." $whereand $whereclause ".$csqlbits[1];
        else $retsql=$cursql;
        if ($domain!="private") $retsql=setUserAndGroups($retsql,$userid,$ingroups);
        return $retsql;
}

