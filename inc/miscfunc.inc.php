<?php
function format_message($msg,$align=""){
	if (!$align) $align="style=\"text-align: center;\"";
	return "<br/><div class=\"green\" $align><b>$msg</b></div>";
}
function format_error($err,$align=""){
	if (!$align) $align="style=\"text-align: center;\"";
	return "<br/><div class=\"red\" $align><b>$err</b></div>";
}
function print_message($msg,$align=""){
	echo format_message($msg,$align);
}
function print_error($err,$align=""){
	echo format_error($err,$align);
}
function array_in_array($needles, $haystack) {
    foreach ($needles as $needle) {
        if ( in_array($needle, $haystack) ) {
            return true;
        }
    }
    return false;
}
function array_combinations($tail){
	$tkeys=array_keys($tail);
	$headkey=$tkeys[sizeof($tkeys)-1];
        $head=$tail[$headkey];
	unset($tail[$headkey]);
	$combs=array();
        if (sizeof($tail)==0){
                foreach ($head as $element){
                        $combs[]=array($headkey=>$element);
                }
                return $combs;
        }
        $combs=array_combinations($tail);
	if (sizeof($combs)>0){
	        foreach ($head as $e => $element){
        	        foreach ($combs as $comb){
                	        $comb[$headkey]=$element;
	                        $ncombs[]=$comb;
        	        }
	        }
		return $ncombs;
	}
	return array();
}
function getUsefulPrefixesArray($domain,$merge=false){
	$myexp=array();
	$other=array();
	$other['rdf']='http://www.w3.org/1999/02/22-rdf-syntax-ns#';
        $other['rdfs']='http://www.w3.org/2000/01/rdf-schema#';
        $other['owl']='http://www.w3.org/2002/07/owl#';
        $other['xsd']='http://www.w3.org/2001/XMLSchema#';
        $other['dc']='http://purl.org/dc/elements/1.1/';
        $other['dcterms']='http://purl.org/dc/terms/';
	if (trim($domain)=="private" || trim($domain)=="public"){
		$myexp['snarm']='http://rdf.myexperiment.org/ontologies/snarm/';
		$myexp['mebase']='http://rdf.myexperiment.org/ontologies/base/';
		$myexp['meannot']='http://rdf.myexperiment.org/ontologies/annotations/';
		$myexp['mepack']='http://rdf.myexperiment.org/ontologies/packs/';
		$myexp['meexp']='http://rdf.myexperiment.org/ontologies/experiments/';
		$myexp['mecontrib']='http://rdf.myexperiment.org/ontologies/contributions/';
		$myexp['mevd']='http://rdf.myexperiment.org/ontologies/viewings_downloads/';
		$myexp['mecomp']='http://rdf.myexperiment.org/ontologies/components/';
		$myexp['mespec']='http://rdf.myexperiment.org/ontologies/specific/';
	        $other['foaf']='http://xmlns.com/foaf/0.1/';
        	$other['sioc']='http://rdfs.org/sioc/ns#';
	        $other['ore']='http://www.openarchives.org/ore/terms/';
        	$other['cc']='http://web.resource.org/cc/';
	}
	if (trim($domain)=="private" ){
		$myexp['mewn']='http://rdf.myexperiment.org/ontologies/wordnet/';
		$myexp['meq']='http://rdf.myexperiment.org/ontologies/questions/';
	}
	if ($merge) return array_merge($myexp,$other);
	return array($myexp,$other);
}
function getUsefulPrefixes($domain){
	global $datauri;
	list($myexp,$other)=getUsefulPrefixesArray($domain);
	if (sizeof($myexp)>0){
		$usepref='    <table class="borders">
      <tr><th>myExperiment</th><th>Other</th></tr>
      <tr>
        <td><ul class="nonesmall">
            <li class="prefix"><a onclick="addPrefixToQuery(this.parentNode.innerHTML)">BASE &lt;'.$datauri.'&gt;</a></li>
';
		foreach ($myexp as $pref => $ns){
			$usepref.="          <li class=\"prefix\"><a onclick=\"addPrefixToQuery(this.parentNode.innerHTML)\">PREFIX $pref: &lt;$ns&gt;</a></li>\n";
		}
		$usepref.='        </ul></td>
        <td><ul class="nonesmall">
';		
		foreach ($other as $pref => $ns){
		  	$usepref.="          <li class=\"prefix\"><a onclick=\"addPrefixToQuery(this.parentNode.innerHTML)\">PREFIX $pref: &lt;$ns&gt;</a></li>\n";
                }
                $usepref.="        </ul></td>\n      </tr>\n    </table>\n";
		return $usepref;
	}
	$usepref='    <ul class="none">
';
	foreach ($other as $pref => $ns){
                          $usepref.="      <li class=\"prefix\"><a onclick=\"addPrefixToQuery(this.parentNode.innerHTML)\">PREFIX $pref: &lt;$ns&gt;</a></li>\n";
        }
        $usepref.="    </ul>\n";
        return $usepref;
}
function sc404($path=''){
        header("HTTP/1.0 404 Not Found");
        header("Content-type: text/html");
        $pagetitle="404 Not Found";
        include('header.inc.php');
        echo "<p>The URL could not be found</p>";
        include('footer.inc.php');
	exit();
}

?>