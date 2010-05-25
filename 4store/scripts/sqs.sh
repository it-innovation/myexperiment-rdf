#!/bin/bash
source `dirname $BASH_SOURCE`/settings.sh
cd $STORE4_PATH/code

echo "============== `date` =============="
check(){
	ps aux | grep 4s- | grep $1 | grep -v grep | wc -l
}	
check_triplestore(){
	case $1 in
          $TRIPLESTORE)
                ;;
          ontologies)
                ;;
          *)
                echo "[`date +%T`] Unrecognized SPARQL Query Server: $1"
                exit
                ;;
        esac
}
	
stop(){
	check_triplestore $1
	running=$(check $1)
	if [ $running -eq  0 ]; then
                echo "[`date +%T`] SPARQL Query Server $1 is not running"
	else
		for pid in `ps aux | grep myexp_ld | grep 4s- | awk 'BEGIN{FS=" "}{print $2}'`; do
			kill -9 $pid
		done	
		echo "[`date +%T`] Stopped SPARQL Query Server $1"
	fi
}
start(){
	check_triplestore $1
	running=$(check $1)
        if [ $running -gt 0 ]; then
		echo "[`date +%T`] Cannot start SPARQL Query Server $1 as is already running"
		exit
    	fi
	$STORE4EXEC_PATH/4s-backend $1
	echo "[`date +%T`] Started SPARQL Query Server using $1"
}
status(){
	check_triplestore $1
	running=$(check $1)
	updatetime=`head -n 1 $STORE4_PATH/log/$1_updated.log`
	updatetimef=`$PHPEXEC_PATH/php $STORE4_PATH/scripts/dateFormatter.php "$updatetime"`
	if [ $running -gt 0 ]; then  
		echo "[`date +%T`] SPARQL Query Server $1 running"
	else
		echo "[`date +%T`] SPARQL Query Server $1 is not running"
	fi
	echo "[`date +%T`] SPARQL Query Server $1 was updated with database snapshot from $updatetimef ($updatetime)"
}
test(){
	working=`$PHPEXEC_PATH/php $STORE4_PATH/scripts/test4store.php $1`
	if [ "$working" ]; then
		if [ $working -eq 1 ]; then
			echo "[`date +%T`] SPARQL Query Server $1 is functioning correctly"
		else
			echo "[`date +%T`] SPARQL Query Server $1 is not functioning correctly and will be restarted"
	                stop $1
        	        start $1
		fi
	else
		echo "[`date +%T`] SPARQL Query Server $1 is not functioning correctly and will be restarted"
		stop $1
		start $1
	fi
}
reason-ontology(){
	check_triplestore $1
	myexp_ts=`expr match "$1" 'myexp_[a-zA-Z0-9_]*'`
	if [ $myexp_ts -gt 0 ]; then
		reasoned_filename="$DATA_PATH/$1/$1_reasoned.owl"
		java -cp $JAVA_CP RDFSReasonerOntologyMerger $STORE4_PATH/config/$1_ontologies.txt > $reasoned_filename 2> /dev/null
		echo "[`date +%T`] Ontologies from $1_ontologies.txt successfully reasoned and written to $reasoned_filename"
	else
		echo "[`date +%T`] reason command cannot be used with $1 triplestore"
	fi
}
reason-file(){
	check_triplestore $1
        java -cp $JAVA_CP RDFSReasonerSingleFile $STORE4_PATH/config/$1_ontologies.txt $2 $3
	echo "[`date +%T`] Reasoned file $2 using $1 ontologies and saved to $3"
}
add(){
	error=`$STORE4EXEC_PATH/4s-import $1 $2 2>&1`
	errcount=1
	while [[ -n "$error" && $errcount -lt 3 ]]; do
		sleep 1;
		error=`$STORE4EXEC_PATH/4s-import $1 $2 2>&1`
		errcount=$errcount+1
	done	
	if [ -n "$error" ]; then 
		echo 0
	else
		echo 1
	fi
}
remove(){
	if [ ${2:0:7} == "http://" ]; then
		$STORE4EXEC_PATH/4s-delete-model $1 $2
	else
		$STORE4EXEC_PATH/4s-delete-model $1 file://$2
		if [[ "$3" == "delete" ]]; then
        		if [ -f "$2" ]; then
	        		echo $2 | grep -v '*' | grep -v '^-r' | xargs rm
        			echo "[`date +%T`] Deleted $2"
			fi
		fi
        fi
}
add-list(){
	for graph in `cat $2`; do
	 	added=`add $1 $graph`
	       	if [ $added -gt 0 ]; then
              		echo "[`date +%T`] Added $graph to $1 Knowledge Base"
        	else
                	echo "[`date +%T`] Could Not Add $graph to $1 Knowledge Base"
        	fi
        done
}
remove-list(){
        for graph in `cat $2`; do
                remove $1 $graph $3
		echo "[`date +%T`] Removed graph $graph from $1 Knowledge Base"
        done
}

update-cached-files(){	
        echo "[`date +%T`] Updating Cached Files for $1"
	$PHPEXEC_PATH/php $STORE4_PATH/scripts/changeddata.php $1
	echo "[`date +%T`] Updated Cached Files for $1"
}
update(){
	myexp_ts=`expr match "$1" 'myexp_[a-zA-Z0-9_]*'`
        if [ $myexp_ts -gt 0 ]; then
		get-dataflows
                reason-dataflows $1
		if [ -n "$2" ]; then 
			if [ $2 == "no-cache" ]; then
				echo "[`date +%T`] Not Updating Cached Files"
			else
				update-cached-files $1
			fi
		else
			update-cached-files $1
		fi
		entity_groups=( announcements attributions citations comments content_types creditations dataflows experiments favourites files friendships friendship_invitations groups group_announcements jobs licenses local_pack_entries memberships membership_invitations messages packs ratings remote_pack_entries reviews tags taggings taverna_enactors users vocabularies workflows workflow_versions )
		day=`date +%e`
                month=`date +%b`
		date +%s > $STORE4_PATH/log/$1_update_time.log
		stop $1
		start $1
		sleep 3
		for graph in `cat $DATA_PATH/tmp/$1/delete_files`; do
                        remove $1 $graph delete 
		done
		echo "[`date +%T`] Removed all deleted entities from $1"
		if `ls -l $DATA_PATH/$1/$1_reasoned.owl 2>/dev/null | awk -v month="$month" -v day="$day" '{if ($6 == month && $7 == day) print $9}'`; then
			remove $1 $DATA_PATH/$1/$1_reasoned.owl
			added=`add $1 $DATA_PATH/$1/$1_reasoned.owl`
			if [ $added -gt 0 ]; then
				echo "[`date +%T`] Added/Updated $DATA_PATH/$1/$1_reasoned.owl to $1 Knowledge Base"
			else
				echo "[`date +%T`] Could Not Add/Update $DATA_PATH/$1/$1_reasoned.owl to $1 Knowledge Base"
			fi
		fi
		ENTITIES=( "${ENTITIES[@]}" dataflows )
		for e in ${ENTITIES[@]}; do
			filepath="$DATA_PATH/$1/$e/"
			for graph in `ls -l $filepath* 2>/dev/null | awk -v month="$month" -v day="$day" '{if ($6 == month && $7 == day) print $9}'`; do
				remove $1 $graph keep-file
			 	added=`add $1 $graph`
                        	if [ $added -gt 0 ]; then
                                	echo "[`date +%T`] Added/Updated $graph to $1 Knowledge Base"
                        	else
                                	echo "[`date +%T`] Could Not Add/Update $graph to $1 Knowledge Base"
                        	fi
			done
                done
	fi
	count-triples $1
}	
list-graphs(){
	check_triplestore $1
	echo "[`date +%T`] Listing Graphs of $1 Triplestore:"
	$PHPEXEC_PATH/php $STORE4_PATH/scripts/listGraphs.php $1
}
count-triples(){
	check_triplestore $1
	notriples=`$STORE4EXEC_PATH/4s-size $1 | tail -3 | head -1 | awk 'BEGIN{FS=" "}{print $2}'`
	echo "[`date +%T`] $1 Triplestore has $notriples triples"
	echo $notriples > $STORE4_PATH/log/$1_triples.log
	echo "[`date +%T`] Printing number of triples to file log/$1_triples.log"
}
get-dataflows(){
	$PHPEXEC_PATH/php $STORE4_PATH/scripts/getNewWorkflowVersions.php | awk -v datapath="$DATA_PATH" -v httpwwwpath="HTTPWWW_PATH" 'BEGIN{FS=","}{ print " -O " datapath "/dataflows/xml/" $1 " -q " httpwwwpath "/workflow.xml?id=" $2 "&version=" $3 "&elements=components" }' > /tmp/dataflow_wgets.txt
	exec</tmp/dataflow_wgets.txt
	while read line
	do
        	wget $line
	        echo "[`date +%T`] Executed wget $line"
#	        sleep 2
	done
	rm /tmp/dataflow_wgets.txt

}
reason-dataflows(){
	echo "[`date +%T`] Generating Dataflow RDF"
	$PHPEXEC_PATH/php $STORE4_PATH/scripts/generateDataflowRDF.php
	echo "[`date +%T`] Reasoning Dataflow RDF"
	nographs=`cat /tmp/dataflows.txt | wc -l`
	if [ $nographs -gt 0 ]; then
		echo "[`date +%T`] Reasoning Dataflow RDF"
		java -cp $JAVA_CP RDFSReasonerMultiFile $STORE4_PATH/config/"$TRIPLESTORE"_ontologies.txt /tmp/dataflows.txt $DATA_PATH/dataflows/reasoned/
	else
		echo "[`date +%T`] No Workflow Dataflow RDF to reason"
	fi
	rm /tmp/dataflows.txt
}
reason-files(){
	check_triplestore $1
	java -cp $JAVA_CP RDFSReasonerMultiFile $STORE4_PATH/config/$1_ontologies.txt $2 $3
	echo "java RDFSReasonerMultiFile $STORE4_PATH/config/$1_ontologies.txt $2 $3"
	echo "[`date +%T`] Reasoned files in $2 using $1 ontologies and saved to $3"
}
generate-spec(){
	if [ $1 == $TRIPLESTORE ]; then
		wget -O $STORE4_PATH/$1/html/spec.html -q $HTTPRDF_PATH/current/spec
		echo "[`date +%T`] Retrieved specification document for $1 and saved to $DATA_PATH/$1/html/spec.html"
	else
		echo "[`date +%T`] Specification document can not be generated for $1"
	fi
}
graph-size(){
	check_triplestore $1
	notriples=`$STORE4EXEC_PATH/4s-query $1 "SELECT * WHERE { GRAPH <$2> { ?s ?p ?o }}" | grep "<result>" | wc -l`	
	echo "There are $notriples triples in $2"
}
data-dump(){
	check_triplestore $1
	$PHPEXEC_PATH/php $STORE4_PATH/scripts/datadump.php $1
}
	
case "$2" in
  start)
	start $1
	;;
  stop)
	stop $1
	;;
  status)
        status $1
	;;
  restart)
	stop $1
	start $1
	;;
  test)
	test $1
	;;
  update)
	update $1 $3
	;;
  reason-ontology)
	reason-ontology $1
	;;
  reason-file)
	reason-file $1 $3 $4
	;;
  reason-files)
        reason-files $1 $3 $4
	;;
  add)
	added=`add $1 $3`
        if [ $added -gt 0 ]; then
        	echo "[`date +%T`] Added $3 to $1 Knowledge Base"
        else
        	echo "[`date +%T`] Could Not Add $3 to $1 Knowledge Base"
        fi
	count-triples $1
  	;;
  add-list)
	add-list $1 $3
	count-triples $1 
	;;
  remove)
	remove $1 $3 $4
	echo "[`date +%T`] Removed graph $3 from $1 Knowledge Base"
	count-triples $1
	;;
  remove-list)
	remove-list $1 $3 $4
	count-triples $1
	;;
  reload)
	remove $1 $3 keep-file
	echo "[`date +%T`] Removed graph $3 from $1 Knowledge Base"
	added=`add $1 $3`
        if [ $added -gt 0 ]; then
                echo "[`date +%T`] Added $3 to $1 Knowledge Base"
        else
                echo "[`date +%T`] Could Not Add $3 to $1 Knowledge Base"
        fi

	count-triples $1
  	;;
  reload-list)
	remove-list $1 $3 keep-file
	add-list $1 $3
	count-triples $1
	;;
  list-graphs)
	list-graphs $1
	;;
  count-triples)
	count-triples $1
	;;
  generate-spec)
	generate-spec $1
        ;;
  graph-size)
	graph-size $1 $3
        ;;
  data-dump)
	data-dump $1
	;;
  *)
	$STORE4_PATH/scripts/sqs_help.sh
	;;
esac
exit 1
