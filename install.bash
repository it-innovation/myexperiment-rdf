#!/bin/bash
d=`dirname $0`
basedir=`cd $d; pwd`
source ${basedir}/settings.bash || { echo "Could not source settings.bash. Aborting ..."; exit 1; }
sudo apt-get install -y php5 php5-cli php5-mysql php5-dev php5-uuid || { echo "Could not install required packages using apt-get install. Aborting ..."; exit 2; }
cat ${basedir}/inc/defaultsettings_installer.inc.php | sed "s/THIS_DIR/$basedir" | sed "s/DATAURI/$datauri" | sed "s/MYEXP_PATH/$myexp_path/" | sed "s/MYSQL_USERNAME/$mysql_username/" | sed "s/MYSQL_PASSWORD/$mysql_password/" sed "s/DATABASE/$database" > ${basedir}/inc/settings.inc.php || { echo "Could not create inc/settings.inc.php. Aborting ..."; exit 3; }
