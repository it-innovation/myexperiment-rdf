<?php
function addAggregatedResource($aggres,$entity='',$url='',$format=''){
        global $aggregated_resources, $all_ars;
        if (!$format) return;
        if (!in_array($url,$all_ars)){
                $aggregated_resources[$entity][$url]="$aggres\n";
                $all_ars[]=$url;
//              echo "<!-- $entity - $url -->   \n";
//              if (!in_array($entity,$all_ars)) $all_ars[]=$entity;
        }
}
function clearAggregatedResources($entity){
        global $aggregated_resources;
        $aggregated_resources[$entity]="USED";
}
function retrieveAggregatedResources($entity){
        global $aggregated_resources;
        if (is_array($aggregated_resources[$entity])) return $aggregated_resources[$entity];
        return array();
}
function printAggregatedResources($entity){
///     $xml="<!-- $entity -->\n";
        $ars=retrieveAggregatedResources($entity);
//      print_r($ars);
        if ($ars=="USED" || !$ars) return $xml;
        foreach ($ars as $ar) $xml.="$ar";
        return $xml;
}
function addProxy($type,$id,$entity,$format=''){
        global $proxies, $mappings, $sql, $tables, $userid, $ingroups, $datauri;
        if (!$format) return;
        $psql=setRestrict($type,array("id","=",$id),$userid,$ingroups);
        $pres=mysql_query($psql);
//      echo "<!-- $entity -->";
        if ($proxies[$entity]!="USED") $proxies[$entity][]=printEntity(mysql_fetch_assoc($pres),$type);
//      echo "<!-- $entity -->\n";
//      echo "<!-- $psql -->\n\n";
}
function retrieveProxies($entity){
        global $proxies;
        if (is_array($proxies[$entity])) return $proxies[$entity];
        return array();
}
function printProxies($entity){
        $xml="";
        $proxies=retrieveProxies($entity);
        if ($proxies=="USED" || !$proxies) return $xml;
        foreach ($proxies as $proxy) $xml.="$proxy";
        return $xml;
}
function clearProxies($entity){
        global $proxies;
        $proxies[$entity]="USED";
}
function getResourceMapRDF($type,$id){
        global $datauri;
	$curtime=date("Y-m-d\TH:i:s\Z");
        return"  <rdf:Description rdf:about=\"".$datauri."ResourceMap/$type/$id\">
    <ore:describes rdf:resource=\"".$datauri."$type/$id\"/>
    <dcterms:creator rdf:parseType=\"Resource\">
      <foaf:name>myExperiment Mothership RDF Generator</foaf:name>
      <foaf:page rdf:resource=\"$datauri\" />
    </dcterms:creator>
    <dcterms:created rdf:datatype=\"&xsd;dateTime\">$curtime</dcterms:created>
    <dcterms:modified rdf:datatype=\"&xsd;dateTime\">$curtime</dcterms:modified>
    <dc:rights>This Resource Map is available under the Creative Commons Attribution-Noncommercial 2.5 Generic license</dc:rights>
    <dcterms:rights rdf:resource=\"http://creativecommons.org/licenses/by-nc/2.5/\"/>
  </rdf:Description>\n\n";
}
function getAggregatedResourceSQL($type,$id){
	global $sql;
        if ($type=="experiments"){
                return $sql['jobs']. " where experiment_id=$id";
        }
        if ($type=="packs"){
                return "select id as proxy_id, contributable_id as id, contributable_version as version, contributable_type as type, '' as uri, 'LocalPackEntry' as entry_type from pack_contributable_entries where pack_id=$id union select id as proxy_id, '' as id, '' as version, '' as type, uri, 'RemotePackEntry' as entry_type from pack_remote_entries where pack_id=$id";
        }
        return "";
}
function getOREAggregatedResources($entry,$type){
        global $datauri,$mappings, $sql,$tables,$userid,$ingroups,$ontent;
        if ($type=="experiments" || $type=="packs"){
                $arsql=getAggregatedResourceSQL($type,$entry['id']);
//              $xml="<!-- $arsql -->\n";
                $res=mysql_query($arsql);
                for ($i=0; $i<mysql_num_rows($res); $i++){
                        $row=mysql_fetch_assoc($res);
			if ($row['type']=="Blob")  $row['type']="File";
                        if (isset($row['runnable_id'])) $row['type']="Job";
			$etype=array_search($row['type'],$ontent);
			$entrytype=array_search($row['entry_type'],$ontent);
//                      print_r($row);
                        if ($row['1']) break;
                        if ($row['version']){
                                $row['id']=getVersionID($row);
                                if ($row['type']=="workflows") $row['type']="workflow_versions";
                        }
                        if ($et['type']=="blob")  $row['type']="file";
                        if (isset($row['runnable_id'])) $row['type']="jobs";
                        if ($entrytype=="remote_pack_entries") $fulluri=$row['uri'];
                        else $fulluri=$datauri.$etype."/".$row['id'];
                        if ($entrytype!='remote_pack_entries'){
//                              if ($type=="Experiment") echo "~~".$row['type']."~~<br/>\n";
                                $csql=setRestrict($etype,array("id","=",$row['id']),$userid,$ingroups);
//                              $xml.="<!-- $entry[format] -->\n";
                                $cres=mysql_query($csql);
                                if (mysql_num_rows($cres)==1){
                                        addAggregatedResource(printEntity(mysql_fetch_assoc($cres),$etype),$datauri.$type."/$entry[id]",$datauri.$etype."/$row[id]",$entry['format']);
                                }
                        }
                        else addAggregatedResource("    <rdf:Description rdf:about=\"$row[uri]\"/>\n",$datauri.$type."/$entry[id]",$row['uri'],$entry['format'],'hide');
                        if($row['entry_type']=='local_pack_entries' || $row['entry_type']=='remote_pack_entries') addProxy($row['entry_type'],$row['proxy_id'],$datauri.$type."/$entry[id]",$entry['format']);
                        $xml.="    <ore:aggregates rdf:resource=\"".str_replace("&","&amp;",$fulluri)."\"/>\n";
                }
        }
        return $xml;
}
function getResourceMapURI($entity,$type){
        return "resource_maps/$type/$entity[id]";
}
function getAtomEntryURI($entity,$type){
        return "atom_entries/$type/$entity[id]";
}
function getHTMLURI($entity,$type){
        global $myexp_inst, $hspent;
        if ($type=="workflow_versions") return $myexp_inst."workflows/$entity[workflow_id]?version=$entity[version]";
        if ($type=="jobs") return $myexp_inst."experiments/$entity[experiment_id]/jobs/$entity[id]";
        return $myexp_inst.$hspent[$type]."/$entity[id]";
}

?>
