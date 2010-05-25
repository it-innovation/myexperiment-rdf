<?php
include('include.inc.php');
include('4storefunc.inc.php');

handle_Request($_GET);
function handle_Request($params){
        # Dump the contents of the file
	$logtext="";
        if(file_exists($params['log'])){
                $handle = fopen($params['log'], "r");
                if($handle != false){
                        while(!feof($handle)){
                                $logtext.=fgets($handle);
                        }
                        fclose($handle);
                }
        }
	else{
		if ($params['op']=="reasonOntology") reasonOntology($params['name'],$params['url'],$params['ontology'],$params['log']);
		elseif ($params['op']=="cacheSpec") cacheSpec($params['name'],$params['url'],$params['ontology'],$params['log']);
		$logtext="Initializing - About to start printing logfile ".$params['log'];
	}
	if ($logtext){
		echo '<html><head>';
		if (!preg_match("/Finished/",$logtext)){
			echo '<meta http-equiv="refresh" content="5"/>';
		}
		echo "<pre>$logtext</pre>"; 
		echo '</head><body>';
	        echo '<a name="BOTTOM">&nbsp;</a>';
       	 	echo '</body></html>';
	}
//	else print_404();
}

function print_404(){
        header('HTTP/1.0 404 Not Found');

        echo '<!DOCTYPE HTML PUBLIC "-//IETF//DTD HTML 2.0//EN">';
        echo '<html><head>';
        echo '<title>404 Not Found</title>';
        echo '</head><body>';
        echo '<h1>Not Found</h1>';
        echo '</body></html>';

        exit(0);
}

?>
