#!/bin/bash
export LD_LIBRARY_PATH='/usr/local/lib'
export BANG='!'
/usr/local/bin/4s-query $1 "$2" -s $3 > $4
