#!/bin/bash
source `dirname $BASH_SOURCE`/settings.sh
if [ -e $DATA_PATH/$TRIPLESTORE ]; then
	echo "Data folders have already been created!"
else
	cd $DATA_PATH
	mkdir $TRIPLESTORE
	mkdir $TRIPLESTORE/html
        mkdir $TRIPLESTORE/linksets
	mkdir tmp
	mkdir tmp/queries
	mkdir tmp/$TRIPLESTORE
	for e in ${ENTITIES[@]}; do
		mkdir "$TRIPLESTORE/$e"
		mkdir "tmp/$TRIPLESTORE/$e";
	done
	touch tmp/$TRIPLESTORE/delete_files
	mkdir dataflows/
	mkdir dataflows/dump
	mkdir dataflows/inc
	ln -s $DATA_PATH/dataflows/dump/ $TRIPLESTORE/dataflows
	echo "ln -s $DATA_PATH/dataflows/dump/ $TRIPLESTORE/dataflows"
	mkdir ontologies
	mkdir ontologies/remoteont
	mkdir ontologies/reasoned
	mkdir ontologies/cachedspec
	sudo chgrp -R $HTTPGROUP *
	sudo chmod -R g+w *
fi
