<?php
function sc400(){
	header("HTTP/1.0 400 Bad Request");
	header("Content-type: text/html");
}
function sc401(){
	header("HTTP/1.0 401 Unauthorized");
	header('Cache-Control: no-cache');
	header('WWW-Authenticate: Basic realm="myExperiment Protected RDF"');
	header('Status: 401 Unauthorized');
}
function sc403(){
	header("HTTP/1.0 403 Forbidden");
}
function sc404(){
        header("HTTP/1.0 404 Not Found");
}

?>
