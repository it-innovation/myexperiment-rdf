#!/bin/bash
echo "============== `date` =============="
cd /var/4store/scripts
scp backup@tents:/home/backup/www.myexperiment.org/latest_db.txt /tmp/
filepath=`cat /tmp/latest_db.txt`
filename=`cat /tmp/latest_db.txt | awk 'BEGIN{FS="/"}{ print $NF }'`
scp backup@tents:$filepath /tmp/
ls -t /tmp/$filename
echo "[`date +%T`] Downloaded Latest myExperiment Database Snapshot: $filename"
zcat /tmp/$filename | grep -v 'INSERT INTO `sessions`' | mysql -u root m2_production
echo "[`date +%T`] Uploaded SQL File ($filename) to MySQL"
rm -f /tmp/$filename
