#!/bin/bash
source settings.sh
cd $STORE4_PATH/code
LC_ALL=c

echo "[`date +%T`] Generating Dataflow RDF"
$PHPEXEC_PATH/php $STORE4_PATH/scripts/generateDataflowRDF.php $1
if [ `cat /tmp/dataflows.txt | wc -l` -gt 0 ]; then
	echo "[`date +%T`] Reasoning Dataflow RDF"
	java -cp $JAVA_CP RDFSDataReasoner $STORE4_PATH/config/myexp_public_ontologies.txt /tmp/dataflows.txt $DATA_PATH/dataflows/reasoned/
	echo "[`date +%T`] Reasoned Dataflow RDF"
else
	echo "[`date +%T`] No Dataflow RDF to reason"
fi

