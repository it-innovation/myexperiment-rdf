<?php
	include('include.inc.php');
	$pagetitle="<small>Semantic Web Browsers</small>";
	include('header.inc.php');
	require('miscfunc.inc.php');
	include('data.inc.php');
	$swbrowsers=array("Raw myExperiment RDF/XML"=>"","Disco"=>"http://www4.wiwiss.fu-berlin.de/rdf_browser/?browse_uri=","Marbles"=>"http://beckr.org/marbles?uri=", "Zitgist RDF Browser"=>"http://dataviewer.zitgist.com/?uri=", "SIOC Browser"=>"http://sparql.captsolo.net/browser/browser.py?url=");
	$types=array("announcements","content_types","files","experiments","groups","licenses","messages","packs","policies","users","workflows");
	if ($_POST){
		$type=$_POST['type'];
		$id=$_POST['id'];
		if ($type && $id){
			$uri=$datauri.$type."/".$id;
			$swbrowser=$_POST['swbrowser'];
			if ($swbrowsers[$swbrowser]){
				$url=$swbrowsers[$swbrowser].urlencode($uri);	
			}
			else $url=$uri;	
			if ($_POST['currentpage']) header("Location: $url");
		}
		else $err="ERROR: No Type or ID";
	}
?>
<p>There is now a fair amount of clients around for browsing the Semantic Web.  This page allows you to send myExperiment RDF to this browsers to see how it renders it.</p>
<?php if ($url){ ?>
<div class="green" style="text-align: center;">
  <b>SW Browser URL:</b> <a target="_blank" id="swbrowser_url" href="<?= $url ?>"><?= $url ?></a>
  <script language="JavaScript1.2" type="text/javascript">
<!-- Hide me from the HTML Validator
	window.open(document.getElementById('swbrowser_url').innerHTML);
-->
</script>
</div>
<br/>
<?php 
} 
else if ($err){
	print_error($err);
	echo "<br/>";
}
?>

<form name="swbrowse" method="post" action="">
  <div class="yellow" align="center">
    <table>
      <tr>
        <td style="text-align: right;"><b>myExperiment URI:</b></td>
        <td>
          <?= $datauri ?>
          <select name="type">
            <option value=""></option>
<?php 
	foreach ($types as $t){
		echo "            <option ";
		if ($t==$type) echo "selected=\"selected\" ";
		echo "value=\"$t\">$t</option>\n";
	}
?>
          </select>
          <input type="text" name="id" size="4" value="<?= $id ?>"/>
        </td>
      </tr>
      <tr>
        <td style="text-align: right;"><b>SW Browser:</b></td>
        <td>
          <select name="swbrowser">
<?php
        foreach (array_keys($swbrowsers) as $swb){
                echo "          <option ";
                if ($swb==$swbrowser) echo "selected=\"selected\" ";
                echo "value=\"$swb\">$swb</option>\n";
        }
?>
          </select>
        </td>
      </tr>
      <tr>
        <td colspan="2" style="text-align: center;">
          <br/>
          <input type="submit" name="newpage" value="Open in New Page"/>
          &nbsp;&nbsp;
          <input type="submit" name="currentpage" value="Open in Current Page"/>
        </td>
      </tr>
    </table>
  </div>
</form>

<?php include('footer.inc.php');
