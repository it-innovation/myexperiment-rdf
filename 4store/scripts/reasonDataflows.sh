#!/bin/bash
cd /var/4store/code
LC_ALL=c
JENALIB="/var/4store/lib";
JAVA_CP=".:$JENALIB/jena.jar:$JENALIB/iri.jar:$JENALIB/arq.jar:$JENALIB/xercesImpl.jar:$JENALIB/xml-apis.jar:$JENALIB/icu4j_3_4.jar:$JENALIB/json.jar:$JENALIB/concurrent.jar:$JENALIB/antlr-2.7.5.jar:$JENALIB/junit.jar:$JENALIB/log4j-1.2.12.jar:$JENALIB/wstx-asl-3.0.0.jar:$JENALIB/stax-api-1.0.jar:$JENALIB/mysql-connector-java-5.0.8-bin.jar:$JENALIB/commons-logging-1.1.1.jar:$JENALIB/commons-logging-1.1.1-javadoc.jar:$JENALIB/commons-logging-1.1.1-sources.jar:$JENALIB/commons-logging-adapters-1.1.1.jar:$JENALIB/commons-logging-api-1.1.1.jar:$JENALIB/commons-logging-tests.jar:$JENALIB/axis.jar:$JENALIB/bcpg-jdk15-139.jar:$JENALIB/bcprov-jdk15-139.jar:$JENALIB/commons-lang-2.0.jar:$JENALIB/grddl.jar:$JENALIB/hsqldb-1.8.0.7.jar:$JENALIB/jakarta-oro-2.0.5.jar:$JENALIB/nekohtml.jar:$JENALIB/ng4j.jar:$JENALIB/saxon8.jar:$JENALIB/tagsoup-1.0.4.jar:";

echo "[`date +%T`] Generating Dataflow RDF"
/usr/bin/php ../scripts/generateDataflowRDF.php $1
if [ `cat /tmp/dataflows.txt | wc -l` -gt 0 ]; then
	echo "[`date +%T`] Reasoning Dataflow RDF"
	java -cp $JAVA_CP RDFSDataReasoner ../config/myexp_public_ontologies.txt /tmp/dataflows.txt /var/data/dataflows/reasoned/
	echo "[`date +%T`] Reasoned Dataflow RDF"
else
	 echo "[`date +%T`] No Dataflow RDF to reason"
fi

