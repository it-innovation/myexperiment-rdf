<?php
function sc400($path=''){
	header("HTTP/1.0 400 Bad Request");
	header("Content-type: text/html");
	$pagetitle="400 Bad Request";
        include($path.'inc/header.inc.php');
	echo "<p>The server could not understand this request</p>";
        include($path.'inc/footer.inc.php');
}
function sc401($path=''){
	header("HTTP/1.0 401 Unauthorized");
	header('Cache-Control: no-cache');
	header('WWW-Authenticate: Basic realm="myExperiment Protected RDF"');
	header('Status: 401 Unauthorized');
	header("Content-type: text/html");
	$pagetitle="401 Unauthorized";
	include($path.'inc/header.inc.php');
	echo "<p>You do not have authorization to view this URL</p>";
	include($path.'inc/footer.inc.php');
}
function sc403($path=''){
	header("HTTP/1.0 403 Forbidden");
        header("Content-type: text/html");
	$pagetitle="403 Forbidden";
        include('inc/header.inc.php');
	echo "<p>This URL is forbidden</p>";
	include($path.'inc/footer.inc.php');
}
function sc404($path=''){
        header("HTTP/1.0 404 Not Found");
        header("Content-type: text/html");
        $pagetitle="404 Not Found";
        include($path.'inc/header.inc.php');
        echo "<p>The URL could not be found</p>";
//        print_r($_GET);
        include($path.'inc/footer.inc.php');
}

?>
