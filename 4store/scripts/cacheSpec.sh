#!/bin/bash
source `dirname $BASH_SOURCE`/settings.sh
cd $STORE4_PATH/scripts
echo "============== `date` =============="
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
echo "[`date +%T`] Finished";
