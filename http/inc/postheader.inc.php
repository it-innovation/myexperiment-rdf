  <hr/>
  <h2>
<?php
if ($headerimg){
	if ($headerimg!="none") echo "    <img src=\"$headerimg\" alt=\"$headername Logo\"/>";
	echo $pagetitle;
}
else{
	echo "<img src=\"http://www.myexperiment.org/images/logo.png\" alt=\"myExperiment Logo\"/>$pagetitle";
}

?>
  </h2>
