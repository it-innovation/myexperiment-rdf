<?php
$pagetitle="OAI-ORE Export";
ini_set('include_path','inc/:.');
include('header.inc.php');
include('settings.inc.php');
?>
<p>myExperiment <a href="ontologies#Packs">Packs</a> and <a href="ontologies#Experiments">Experiments</a> can be exported as <a href="http://www.openarchives.org/ore/">OAI-ORE</a> resource maps.  Currently myExperiment supports two formats of resource maps, RDF/XML and Atom.</p>
<p>To retrieve a resource map of the appropriate format you can either specify the &quot;Accept&quot; in the HTTP request, e.g.
  <pre>wget --header "Accept: application/rdf+xml"  <?= $guidedatauri ?>Aggregation/Pack/1
wget --header "Accept: application/atom+xml"  <?= $guidedatauri ?>Aggregation/Pack/1</pre>
Alternatively, you can use a web browser, which will automatically perform a 303 redirect you to a splash page, e.g.
<pre><a href="<?= $guidedatauri ?>Aggregation/Pack/1"><?= $guidedatauri ?>Aggregation/Pack/1</a> 
&nbsp;&nbsp;-&gt; <a href="<?= $guidedatauri ?>SplashPage/Pack/1"><?= $guidedatauri ?>SplashPage/Pack/1</a></pre>
This splash page provides links to the two different formats of the resource map for the particular pack/experiment.</p>
<p>If a pack/experiment is private, if you are attempting to retrieve it using a web browser you will be prompted for your myExperiment username and password.  If you are trying to retrieve it using wget, curl, etc. you will need to send the username and password as part of the header request, e.g.:
<pre>
wget --header "Accept: application/rdf+xml" 
     --http-user=yourusername 
     --http-password=yourpassword 
     <?= $guidedatauri ?>Aggregation/Experiment/33
</pre>
</p>
<p>As well as retrieving individual resource maps it is possibly to get at Atom Feed for packs and experiments:
<pre>
<a href="<?= $guidedatauri ?>AtomFeed/Packs"><?= $guidedatauri?>AtomFeed/Packs</a>
<a href="<?= $guidedatauri ?>AtomFeed/Experiments"><?= $guidedatauri ?>AtomFeed/Experiments</a>
</pre>
These feeds will only contain public entries unless you have &quot;logged in&quot; at <a href="<?= $guidedatauri ?>"><?= $guidedatauri ?></a>, which will allow you to view private entries you have permission to see.  Like most feeds they are ordered with the most recently modified entries first.</p>
<br/><br/>
<?php include('footer.inc.php'); ?>
