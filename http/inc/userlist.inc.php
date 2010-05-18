<?php
  require_once('connect.inc.php');
  $sql="select id, name from users order by name";
  $res=mysql_query($sql);
  echo "      <select name=\"user\">\n";
  for ($u=0; $u<mysql_num_rows($res); $u++){
  	$row=mysql_fetch_array($res);
  	echo "        <option value=\"".$row['id']."\">".$row['name']."</option>\n";
  }
  echo "      </select>\n";
?>


