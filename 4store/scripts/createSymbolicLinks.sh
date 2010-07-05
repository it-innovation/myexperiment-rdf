#!/bin/bash
source `dirname $BASH_SOURCE`/settings.sh
if [ -e $LD_PATH/http/myExperiment.rdf.gz ]; then
	echo "Symbolic Links have already been created!"
else
	ln -s $DATA_PATH/$TRIPLESTORE/myexperiment.rdf.gz  $LD_PATH/http/myexperiment.rdf.gz
	ln -s $DATA_PATH/$TRIPLESTORE/html/spec.html  $LD_PATH/http/ontologies/spec.html
	ln -s $DATA_PATH/$TRIPLESTORE/$TRIPLESTORE"_reasoned.owl" $LD_PATH/http/ontologies/myexp_reasoned.owl
fi

