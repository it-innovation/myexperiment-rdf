#!/bin/bash
bashsource=`dirname $BASH_SOURCE`
if [ "${bashsource:0:1}" == "/" ]; then
  source "$bashsource/settings.sh"
else
  source "`pwd`/$bashsource/settings.sh"
fi
$STORE4EXEC_PATH/4s-query $5 $1 "$2" -s $3 > $4
echo "$STORE4EXEC_PATH/4s-query $5 $1 \"$2\" -s $3 > $4"
