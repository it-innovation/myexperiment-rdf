#!/bin/bash
source `dirname $BASH_SOURCE`/settings.sh
cd $DATA_PATH
mkdir $TRIPLESTORE
mkdir $TRIPLESTORE/html
mkdir tmp
mkdir tmp/$TRIPLESTORE
for e in ${ENTITIES[@]}; do
	mkdir "$TRIPLESTORE/$e"
	mkdir "tmp/$TRIPLESTORE/$e";
done
touch tmp/$TRIPLESTORE/delete_files
mkdir dataflows/
mkdir dataflows/xml
mkdir dataflows/rdf
mkdir dataflows/reasoned
ln -s $DATA_PATH/dataflows/reasoned/ $TRIPLESTORE/dataflows
mkdir ontologies
mkdir ontologies/remoteont
mkdir ontologies/reasoned
mkdir ontologies/cachedspec
sudo chgrp -R $HTTPGROUP *
sudo chown -R g+w *
