echo "Usage: triplestore <triplestore_name> {start|stop|restart|status|update|add|remove|test|reason-ontology|reason-file|reason-files|list-graphs|count-triples|generate-spec|graph-size|data-dump|generate-linksets|generate-voidspec|run-diagnostic|check-versions|help} [OPTIONS]

Examples

./sqs.sh <triplestore> start
  <triplestore> = [ $TRIPLESTORE, ontologies ]

./sqs.sh <triplestore> stop
  <triplestore> = [ $TRIPLESTORE, ontologies ]

./sqs.sh <triplestore> restart
  <triplestore> = [ $TRIPLESTORE, ontologies ]

./sqs.sh <triplestore> test
  <triplestore> = [ $TRIPLESTORE, ontologies ]

./sqs.sh <triplestore> update [ no-cache ]
  <triplestore> = [ $TRIPLESTORE ]

./sqs.sh <triplestore> reason-ontology
  <triplestore> = [ $TRIPLESTORE ]

./sqs.sh <triplestore> reason-file <filepath> <reasonedpath>
  <triplestore> = [ $TRIPLESTORE ]
  E.g. <filepath> = $DATA_PATH/$TRIPLESTORE/workflows/12
  <reasonedpath> = $DATA_PATH/$TRIPLESTORE/reasoned/

./sqs.sh <triplestore> reason-files <filespath> <reasonedpath>
  <triplestore> = [ $TRIPLESTORE ]
  E.g. <filespath> = /home/user/files_to_reasoned.txt
  <reasonedpath> = $DATA_PATH/$TRIPLESTORE/reasoned/

./sqs.sh <triplestore> add <filename> 
  <triplestore> = [ $TRIPLESTORE, ontologies ]
  E.g. <filename> = $DATA_PATH/$TRIPLESTORE/workflows/12

./sqs.sh <triplestore> add-list <filename>
  <triplestore> = [ $TRIPLESTORE, ontologies ]
  E.g. <filename> = /tmp/graphs_to_add.txt

./sqs.sh <triplestore> remove <filename> <option>
  <triplestore> = [ $TRIPLESTORE, ontologies ]
  E.g. <filename> = $DATA_PATH/$TRIPLESTORE/workflows/12
  <option> = [ delete, NULL ]

./sqs.sh <triplestore> remove-list <filename> <option>
  <triplestore> = [ $TRIPLESTORE, ontologies ]
  E.g. <filename> = /tmp/graphs_to_remove.txt
  <option> = [ delete, NULL ]

./sqs.sh <triplestore> list-graphs
  <triplestore> = [ $TRIPLESTORE, ontologies ]

./sqs.sh <triplestore> count-triples
  <triplestore> = [ $TRIPLESTORE, ontologies ]

./sqs.sh <triplestore> generate-spec
  <triplestore> = [ $TRIPLESTORE ]

./sqs.sh <triplestore> graph-size <graphuri>
  <triplestore> = [ $TRIPLESTORE ]
  E.g. <graphuri> = file://$DATA_PATH/$TRIPLESTORE/workflows/12

./sqs.sh <triplestore> data-dump
  <triplestore> = [ $TRIPLESTORE ]

./sqs.sh <triplestore> generate-linksets
  <triplestore> = [ $TRIPLESTORE ]

./sqs.sh <triplestore> generate-voidspec
  <triplestore> = [ $TRIPLESTORE ]

./sqs.sh <triplestore> run-diagnostic
  <triplestore> = [ $TRIPLESTORE ]

./sqs.sh <triplestore> check-versions
  <triplestore> = [ $TRIPLESTORE ]

./sqs.sh <triplestore> help
  <triplestore> = [ $TRIPLESTORE ontologies ]"

