<?php
	include('include.inc.php');
	include('xmlfunc.inc.php');
	if ($_POST['filename']){
		$fh=fopen($_POST['filename'],'r');
		while ($line = fgets($fh, 4096)) {
                	$fdata.=$line;
	        }
		print_r($fdata);
        	$pxml=parseXML($fdata);
	}
?>
<html>
<head></head>
<body>
	<form method="POST">
          <p>Filename: <input type="text" name="filename" value="<?=$_POST['filename']?>"/> <input type="submit" name="submit" value="Submit"/></p>
       </form>
<?php print_r($pxml); ?>
</body>
</html>
