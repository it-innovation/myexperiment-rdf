<?php
	include('inc/redirectfunc.inc.php');
	if ($_GET['type']=="Experiment" || $_GET['type']=="Pack" || $_GET['type']=="Workflow" || $_GET['type']=="WorkflowVersion"){
		header("HTTP/1.0 303 See Other");
		if($_SERVER['HTTP_ACCEPT']=="application/rdf+xml") header("Location: http://$_SERVER[SERVER_NAME]/ResourceMap/$_GET[type]/$_GET[id]");
		elseif ($_SERVER['HTTP_ACCEPT']=="application/atom+xml") header("Location: http://$_SERVER[SERVER_NAME]/AtomEntry/$_GET[type]/$_GET[id]");
		else{
			session_start();
			$_SESSION['redirect']='true';
			header("Location: http://$_SERVER[SERVER_NAME]/SplashPage/$_GET[type]/$_GET[id]");
		}
	}
	else sc404();
?>
