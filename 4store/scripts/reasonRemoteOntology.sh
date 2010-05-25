#!/bin/bash
source `pwd`/`dirname $BASH_SOURCE`/settings.sh
triplestore="ontologies"
echo "============== `date` =============="
cd $STORE4_PATH/code/
wget -O $DATA_PATH/ontologies/remoteont/$3_$2.owl -q "$1"
lines=`wc -l $DATA_PATH/ontologies/remoteont/$3_$2.owl | awk '{print $1}'`
if [ $lines -gt 2 ]; then
	echo "[`date +%T`] Retrieved $1 and saved to $DATA_PATH/ontologies/remoteont/$3_$2.owl"
	cat /dev/null > /tmp/reasoning_errors.txt
	java -cp $JAVA_CP RDFSReasonerRemoteImporter $DATA_PATH/ontologies/remoteont/$3_$2.owl $DATA_PATH/ontologies/reasoned/$3_$2_reasoned.owl 2> /tmp/reasoning_errors.txt
	re_length=`wc -l /tmp/reasoning_errors.txt | awk '{print $1}'`
	if [ $re_length -gt 0 ]; then 
		echo "[`date +%T`] Failed to reason $2 with the following errors:"
		cat /tmp/reasoning_errors.txt
	else 
		echo "[`date +%T`] Reasoned $2 and saved reasoned ontology to $DATA_PATH/reasoned/$3_$2_reasoned.owl"
		$STORE4EXEC_PATH/4s-delete-model $triplestore file://$DATA_PATH/ontologies/reasoned/$3_$2_reasoned.owl
		$STORE4EXEC_PATH/4s-import $triplestore $DATA_PATH/ontologies/reasoned/$3_$2_reasoned.owl
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
	fi	
else
	echo "[`date +%T`] Could not properly download ontology $1"
fi
echo "[`date +%T`] Finished";

