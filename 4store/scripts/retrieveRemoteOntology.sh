#!/bin/bash
source `dirname $BASH_SOURCE`/settings.sh
triplestore="ontologies"
echo "============== `date` =============="
cd $STORE4_PATH/code/
wget -O $DATA_PATH/ontologies/remoteont/$3_$2.owl -q "$1"
lines=`wc -l $DATA_PATH/ontologies/remoteont/$3_$2.owl | awk '{print $1}'`
if [ $lines -gt 2 ]; then
	echo "[`date +%T`] Retrieved $1 and saved to $DATA_PATH/ontologies/remoteont/$3_$2.owl"
	$STORE4EXEC_PATH/4s-delete-model $triplestore file://$DATA_PATH/ontologies/remoteont/$3_$2.owl
	$STORE4EXEC_PATH/4s-import $triplestore $DATA_PATH/ontologies/remoteont/$3_$2.owl
	echo "[`date +%T`] (Re)loaded $2 into $triplestore Knowledge Base"
	wget -O $DATA_PATH/ontologies/cachedspec/$3_$2_spec.html -q "$HTTPRDF_PATH/generic/spec?ontology=$3&uncached=1"
	errors=`grep '<!-- Errors -->' $DATA_PATH/ontologies/cachedspec/$3_$2_spec.html`
	errors2=`grep 'XML error: Empty document at line ' $DATA_PATH/ontologies/cachedspec/$3_$2_spec.html`
	if [ ${#errors} -gt 0 ]; then
		echo "[`date +%T`] Cached spec of $1 at $DATA_PATH/ontologies/cachedspec/$3_$2_spec.html <b>with Query Failures</b>";
	elif [ ${#errors2} -gt 0 ]; then
		echo "[`date +%T`] XML Error prevented $1 from being cached properly";
	else 
		echo "[`date +%T`] Cached spec of $1 at $DATA_PATH/ontologies/cachedspec/$3_$2_spec.html";
	fi
else
	echo "[`date +%T`] Could not properly download ontology $1"
fi
echo "[`date +%T`] Finished";

