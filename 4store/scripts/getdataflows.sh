#!/bin/bash
cd /var/jena/code/
/usr/bin/php ../scripts/getNewWorkflowVersions.php | awk 'BEGIN{FS=","}{ print " -O /var/data/dataflows/xml/" $1 " -q http://www.myexperiment.org/workflow.xml?id=" $2 "&version=" $3 "&elements=components" }' > /tmp/wgets.txt
exec</tmp/wgets.txt
while read line
do
	wget $line
	echo "[`date +%T`] Executed wget $line"
	#sleep 2 
done	

