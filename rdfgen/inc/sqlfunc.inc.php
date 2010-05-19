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

