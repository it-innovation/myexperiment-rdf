#!/bin/bash
source settings.sh
$STORE4EXEC_PATH/4s-query $1 "$2" -s $3 > $4
