#!/bin/bash
source settings.sh
cd $STORE4_PATH/log
touch database.log
touch $TRIPLESTORE"_4store.log"
touch $TRIPLESTORE"_triples.log"
touch $TRIPLESTORE"_sparql.log"
touch $TRIPLESTORE"_updatedlog"
touch $TRIPLESTORE"_update_time.log"
touch ontologies_4store.log
touch ontologies_triples.log
touch ontologies_sparql.log
touch ontologies_updated.log
touch ontologies_update_time.log
