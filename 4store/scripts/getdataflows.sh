#!/bin/bash
source settings.sh
cd $STORE4_PATH/scripts
/usr/bin/php getNewWorkflowVersions.php | awk -v datapath="$DATA_PATH" -v httpwwwpath="$HTTPWWW_PATH" 'BEGIN{FS=","}{ print " -O " datapath "/dataflows/xml/" $1 " -q " httpwwwpath "/workflow.xml?id=" $2 "&version=" $3 "&elements=components" }' > /tmp/wgets.txt
exec</tmp/wgets.txt
while read line
do
	wget $line
	echo "[`date +%T`] Executed wget $line"
done	

