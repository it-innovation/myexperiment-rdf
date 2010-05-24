#!/bin/bash
source settings.sh
cd $DATA_PATH
mkdir $TRIPLESTORE
mkdir $TRIPLESTORE/tmp
for e in ${ENTITIES[@]}; do
	mkdir "$TRIPLESTORE/$e"
	mkdir "$TRIPLESTORE/tmp/$e";
done
mkdir dataflows/
mkdir dataflows/xml
mkdir dataflows/rdf
mkdir dataflows/reasoned
ln -s $DATA_PATH/dataflows/reasoned/ $TRIPLESTORE/dataflows
mkdir ontologies
mkdir ontologies/remoteont
mkdir ontologies/reasoned
mkdir ontologies/cachedspec
