#!/bin/bash
source settings.sh
cd $STORE4_PATH/config/
touch $TRIPLESTORE"_ontologies.txt"
touch $TRIPLESTORE"_files.txt"

echo "$HTTPRDF_PATH/ontologies/snarm/
$HTTPRDF_PATH/ontologies/base/
$HTTPRDF_PATH/ontologies/attrib_credit/
$HTTPRDF_PATH/ontologies/annotations/
$HTTPRDF_PATH/ontologies/packs/
$HTTPRDF_PATH/ontologies/experiments/
$HTTPRDF_PATH/ontologies/viewings_downloads/
$HTTPRDF_PATH/ontologies/contributions/
$HTTPRDF_PATH/ontologies/components/
$HTTPRDF_PATH/ontologies/specific/" > $TRIPLESTORE"_files.txt"

echo "$DATA_PATH/reasoned/myexp_public_reasoned.owl";

