<?php
	ini_set('include_path','inc/:.');
	$pagetitle="URL Encoder/Decoder";
	include('header.inc.php');
	include('miscfunc.inc.php');
	if ($_POST['encode']){
		$string=urlencode($_POST['string']);
		print_message("String Encoded",'center');
	}
	elseif ($_POST['decode']){
		$string=urldecode($_POST['string']);
		print_message("String Decoded",'center');
	}	
?>
<form name="urlendec" method="post">
<p style="text-align: center;">
  <b>String to Encode/Decode:</b>
  <br/>
  <textarea name="string" cols="80" rows="8"><?=$string ?></textarea>
  <br/>
  <input type="submit" name="encode" value="Encode">&nbsp;&nbsp;
  <input type="submit" name="decode" value="Decode">
</p>

<?php include('footer.inc.php');
