<?php

function getAtomEntry($type,$id,$userid='0',$ingroups='0'){
	global $domain,$mappings,$tables,$sql,$datauri,$hspent;
	$urlval=array_search('url',$mappings[$type]);
	$tag="atom:entry";
        $entsql=setRestrict($type,array("id","=",$id),$userid,$ingroups);
	$entres=mysql_query($entsql);
	if (mysql_num_rows($entres)==0){
		sc404();
		return;
	}
        $entity=mysql_fetch_assoc($entres);
	if ($entity['contributor_id']) $uid=$entity['contributor_id'];
	else $uid=$entity['user_id']; 
	$authorsql="select profiles.email, users.name from users inner join profiles on users.id=profiles.user_id where users.id=$uid";
        $authorres=mysql_query($authorsql);
        $author=mysql_fetch_assoc($authorres);
        $published=1238670631;
        $created=strtotime($entity['created_at']);
        $updated=strtotime($entity['updated_at']);
        if ($created>$published) $published=$created;
        if ($updated>$published) $rmupdated=$updated;
        else $rmupdated=$published;

	$urlval=array_search('url',$mappings[$type]);
	$aggsql=setRestrict($type,array("id","=",$id),$userid,$ingroups);
	$ares=mysql_query($aggsql);
	$arow=mysql_fetch_assoc($ares);
	$entity_xml=printEntity($arow,$type,'ore');
	//getOREAggregatedResources(array('id'=>$id,'format'=>'ore'),$type);
	$ars=retrieveAggregatedResources($datauri.$type."/$id");
	$proxies=retrieveProxies($datauri.$type."/$id");

/*      $arsql=getAggregatedResourceSQL($type,$id);
        $arres=mysql_query($arsql);
	$ars=array();
        for ($r=0; $r<mysql_num_rows($arres); $r++){
                $ars[]=mysql_fetch_assoc($arres);
        }*/
?>
<<?=$tag?>

    xmlns:dcterms="http://purl.org/dc/terms/"
    xmlns:cc="http://web.resource.org/cc/"
    xmlns:sioc="http://rdfs.org/sioc/ns#"
    xmlns:rdf="http://www.w3.org/1999/02/22-rdf-syntax-ns#"
    xmlns:rdfs="http://www.w3.org/2000/01/rdf-schema#"
    xmlns:atom="http://www.w3.org/2005/Atom"
    xmlns:ore="http://www.openarchives.org/ore/terms/"
    xmlns:oreatom="http://www.openarchives.org/ore/atom/"
    xmlns:foaf="http://xmlns.com/foaf/0.1/"
    xmlns:snarm="&snarm;"
    xmlns:mebase="&mebase;"
    xmlns:meac="&meac;"
    xmlns:meannot="&meannot;"
    xmlns:mepack="&mepack;"
    xmlns:meexp="&meexp;"
    xmlns:mecontrib="&mecontrib;"
    xmlns:mevd="&mevd;"
    xmlns:mecomp="&mecomp;"
    xmlns:mespec="&mespec;"
    xmlns:grddl="http://www.w3.org/2003/g/data-view#"
    grddl:transformation="http://www.openarchives.org/ore/atom/atom-grddl.xsl">

  <atom:id>tag:<?=substr($datauri,7,-1)?>,<?=date('Y',$published).":$type:$id"?></atom:id>

  <atom:link href="<?=$datauri."Aggregation/$type/$id"?>" rel="http://www.openarchives.org/ore/terms/describes"/>

  <atom:author>
    <atom:name><?=$author['name']?></atom:name>
<?php if ($author['email']) echo "    <atom:email>$author[email]</atom:email>\n"; ?>
  </atom:author>

  <atom:title><?=$entity['title']?></atom:title>

  <atom:summary type="html"><?=htmlentities($entity['description_html'])?></atom:summary>
 
  <atom:link href="<?=$datauri."ResourceMap/$type/$id"?>" rel="http://www.openarchives.org/ore/terms/isDescribedBy" type="application/rdf+xml"/>

  <atom:link href="<?=$datauri."SplashPage/$type/$id"?>" rel="alternate"/>

  <atom:category term="http://www.openarchives.org/ore/terms/Aggregation" scheme="http://www.openarchives.org/ore/terms/" label="Aggregation"/>

  <atom:category term="<?=str_replace(" ","T",$entity['created_at'])."Z"?>" scheme="http://www.openarchives.org/ore/atom/created"/>
  <atom:category term="<?=str_replace(" ","T",$entity['updated_at'])."Z"?>" scheme="http://www.openarchives.org/ore/atom/modified"/>

  <atom:link href="<?=$datauri."AtomEntry/$type/$id"?>" rel="self" type="application/atom+xml"/>

  <atom:published><?=str_replace(" ","T",$entity['created_at'])."Z"?></atom:published>
  <atom:updated><?=str_replace(" ","T",$entity['updated_at'])."Z"?></atom:updated>

  <atom:link href="http://creativecommons.org/licenses/by-nc/2.5/" rel="license" type="application/rdf+xml"/>
  <atom:rights>This Resource Map is available under the Creative Commons Attribution-Noncommercial 2.5 Generic license</atom:rights>

  <atom:source>
    <atom:author>
      <atom:name>myExperiment Mothership RDF Generator</atom:name>
      <atom:uri><?=$datauri?></atom:uri>
    </atom:author>
    <atom:id><?=substr($datauri,7,-1).",".date('Y').":$type"?></atom:id>
    <atom:link href="<?=$datauri."AtomFeed/$type"?>" rel="self" type="application/atom+xml"/>
    <atom:updated><?date('Y-m-d\TH:i:s\Z')?></atom:updated>
    <atom:title>myExperiment <?=$type?> Feed</atom:title>
  </atom:source>
  <atom:link href="<?=getHTMLURI($entity,$type) ?>/edit" rel="edit"/>

<?php
foreach ($ars as $ar_uri => $ar){
?>
  <atom:link href="<?=$ar_uri?>"
        rel="http://www.openarchives.org/ore/terms/aggregates"
        title=""
        type="" hreflang="en"/>
<?php } 
$urlval=array_search('url',$mappings[$type]);

$aggsql=setRestrict($type,array("id","=",$id),$userid,$ingroups);
$ares=mysql_query($aggsql);
$arow=mysql_fetch_assoc($ares);
?>
  <oreatom:triples>
<?= 
	$entity_xml;
?>
<?php foreach ($proxies as $proxy) echo $proxy."\n"; ?>
<?php foreach ($ars as $aggres) echo $aggres."\n"; ?>
  </oreatom:triples>

</<?=$tag?>>
<? }?>
