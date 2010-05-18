<?php
function whoami($username,$password){
	ini_set('include_path','.:../inc/:inc/');
        require_once("HttpClientFunc.php");
        $response=makeHTTPRequest("http://www.myexperiment.org","GET","whoami.xml",'','','','',$username,$password);
        if ($response->getStatus()==200){
                $pxml=parseXML($response->getBody());
                foreach ($pxml[0]['children'] as $field){
                        $vals[strtolower($field['name'])]=$field['tagData'];
                }
                return array($vals['id'],$vals);
        }
        return array(-1,array('status'=>$response->getStatus()));
}
?>
