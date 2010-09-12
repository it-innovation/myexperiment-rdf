#!/bin/bash
source `dirname $BASH_SOURCE`/settings.sh
if [ -e $STORE4_PATH/log/database.log ]; then
	echo "Log files have already been created!"
else
	cd $STORE4_PATH/log
	touch database.log
	touch dataflows.log
	touch $TRIPLESTORE"_4store.log"
	touch $TRIPLESTORE"_triples.log"
	touch $TRIPLESTORE"_sparql.log"
	touch $TRIPLESTORE"_updated.log"
	touch $TRIPLESTORE"_update_time.log"
	touch $TRIPLESTORE"_datadump_triples.log"
	touch ontologies_4store.log
	touch ontologies_triples.log
	touch ontologies_sparql.log
	touch ontologies_updated.log
	touch ontologies_update_time.log
fi
