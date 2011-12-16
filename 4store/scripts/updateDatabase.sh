#!/bin/bash
d=`dirname $0`
basedir=`cd ${d}; pwd`
source "$basedir/settings.sh"
echo "============== `date` =============="
cd $STORE4_PATH/scripts
scp backup@tents:/home/backup/www.myexperiment.org/latest_db.txt /tmp/
filepath=`cat /tmp/latest_db.txt`
filename=`cat /tmp/latest_db.txt | awk 'BEGIN{FS="/"}{ print $NF }'`
scp backup@tents:$filepath /tmp/
ls -t /tmp/$filename
echo "[`date +%T`] Downloaded Latest myExperiment Database Snapshot: $filename"
if [ ${#MYSQL_PASSWORD} -gt 0 ]; then
	zcat /tmp/$filename | grep -v 'INSERT INTO `sessions`' | grep -v 'INSERT INTO `viewings`' | grep -v 'INSERT INTO `downloads`' | grep -v 'INSERT INTO `pictures`' | grep -v 'INSERT INTO `content_blobs`' | mysql -u $MYSQL_USERNAME -p$MYSQL_PASSWORD m2_production 
else
	zcat /tmp/$filename | grep -v 'INSERT INTO `sessions`' | grep -v 'INSERT INTO `viewings`' | grep -v 'INSERT INTO `downloads`' | grep -v 'INSERT INTO `pictures`' | grep -v 'INSERT INTO `content_blobs`' | mysql -u $MYSQL_USERNAME m2_production
fi
echo "[`date +%T`] Uploaded SQL File ($filename) to MySQL"
rm -f /tmp/$filename
