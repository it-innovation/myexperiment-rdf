<?php
function makeHTTPRequest($url,$method,$command,$parameters='',$body='',$accept='',$contenttype='',$username='',$password='',$authmethod='Basic',$cname='',$cid='',$additional=''){
//	ini_set('include_path','.:../inc/');
	require_once('Zend/Http/Client.php');
        if (!$method || !$command) return "";
	$fullurl=$url."/".$command;
//	echo $fullurl."<br />";
        $client = new Zend_Http_Client($fullurl);
	if ($username){
		if ($authmethod='Basic'){
//			echo "#$username#$password#";
			$client->setAuth($username, $password, Zend_Http_Client::AUTH_BASIC);
		}
	}
	if ($cname){
		$client->setCookieJar(true);
		$client->setCookie($cname,$cid);
	}
        if ($parameters){
                if ($method=="POST") $client->setParameterPost($parameters);
                else $client->setParameterGet($parameters);
        }
//	print_r($client);
        $response = $client->request($method,$body,$accept,$contenttype,$additional);
        return $response;
}
//Function borrowed from http://www.sitepoint.com/forums/showthread.php?t=438748
/*function object_2_array($result){
    $array = array();
    foreach ($result as $key=>$value){
        if (is_object($value)){
            $array[$key]=object_2_array($value);
        }
        if (is_array($value)){
            $array[$key]=object_2_array($value);
        }
        else{
            $array[$key]=$value;
        }
    }
    return $array;
} 
*/
?>
