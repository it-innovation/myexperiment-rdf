#!/bin/bash
JENALIB="/var/4store/lib";
JAVA_CP=".:$JENALIB/jena.jar:$JENALIB/iri.jar:$JENALIB/arq.jar:$JENALIB/xercesImpl.jar:$JENALIB/xml-apis.jar:$JENALIB/icu4j_3_4.jar:$JENALIB/json.jar:$JENALIB/concurrent.jar:$JENALIB/antlr-2.7.5.jar:$JENALIB/junit.jar:$JENALIB/log4j-1.2.12.jar:$JENALIB/wstx-asl-3.0.0.jar:$JENALIB/stax-api-1.0.jar:$JENALIB/mysql-connector-java-5.0.8-bin.jar:$JENALIB/commons-logging-1.1.1.jar:$JENALIB/commons-logging-1.1.1-javadoc.jar:$JENALIB/commons-logging-1.1.1-sources.jar:$JENALIB/commons-logging-adapters-1.1.1.jar:$JENALIB/commons-logging-api-1.1.1.jar:$JENALIB/commons-logging-tests.jar:$JENALIB/axis.jar:$JENALIB/bcpg-jdk15-139.jar:$JENALIB/bcprov-jdk15-139.jar:$JENALIB/commons-lang-2.0.jar:$JENALIB/grddl.jar:$JENALIB/hsqldb-1.8.0.7.jar:$JENALIB/jakarta-oro-2.0.5.jar:$JENALIB/nekohtml.jar:$JENALIB/ng4j.jar:$JENALIB/saxon8.jar:$JENALIB/tagsoup-1.0.4.jar:";

cd /var/4store/scripts
echo "============== `date` =============="
wget -O /var/data/ontologies/cachedspec/$3_$2_spec.html -q "http://rdf.myexperiment.org/generic/spec?ontology=$3&uncached=1"
errors=`grep '<!-- Errors -->' /var/data/ontologies/cachedspec/$3_$2_spec.html`
errors2=`grep 'XML error: Empty document at line ' /var/data/ontologies/cachedspec/$3_$2_spec.html`
if [ ${#errors} -gt 0 ]; then
        echo "[`date +%T`] Cached spec of $1 at /var/data/ontologies/cachedspec/$3_$2_spec.html <b>with Query Failures</b>";
elif [ ${#errors2} -gt 0 ]; then
        echo "[`date +%T`] XML Error prevented $1 from being cached properly";
else
        echo "[`date +%T`] Cached spec of $1 at /var/data/ontologies/cachedspec/$3_$2_spec.html";
fi
echo "[`date +%T`] Finished";
