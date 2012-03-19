#!/bin/bash
d=`dirname $0`
basedir=`cd ${d}; pwd`
source "$basedir/settings.sh"
echo "============== `date` =============="
cd $STORE4_PATH/scripts
scp backup@tents:/home/backup/www.myexperiment.org/latest_db.txt /tmp/  || { echo "Could not SSH to tents" 1>&2; echo "[`date +%T`] Could not download latest myExperiment database snapshot. No SSH to tents. Aborting ..."; exit 1; }
filepath=`cat /tmp/latest_db.txt`
filename=`cat /tmp/latest_db.txt | awk 'BEGIN{FS="/"}{ print $NF }'`
scp backup@tents:$filepath /tmp/
ls -t /tmp/$filename
echo "[`date +%T`] Downloaded Latest myExperiment Database Snapshot: $filename"
passline=""
if [ ${#MYSQL_PASSWORD} -gt 0 ]; then
	passline="-p$MYSQL_PASSWORD" 
fi
zcat /tmp/$filename | egrep -v '^INSERT INTO `(sessions|viewings|downloads|pictures|content_blobs)`' | mysql -u $MYSQL_USERNAME $passline m2_production
echo "[`date +%T`] Uploaded SQL File ($filename) to MySQL"
rm -f /tmp/$filename
