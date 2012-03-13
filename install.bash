#!/bin/bash
d=`dirname $0`
basedir=`cd $d; pwd`
BANG='$'
echo "+------------------------------------------------+"
echo "| Installer for myExperiment RDF Generation Tool |"
echo "+------------------------------------------------+"
echo ""
source ${basedir}/settings.bash || { echo "Could not source settings.bash. Aborting ..."; exit 1; }
echo "Using apt-get to install package dependencies"
sudo apt-get install -y php5 php5-cli php5-mysql php5-dev php5-uuid || { echo "Could not install required packages using apt-get install. Aborting ..."; exit 2; }
echo "Deploying PHP settings file for RDF Generation Tool"
cat ${basedir}/inc/defaultsettings_installer.inc.php | sed "s!THIS_DIR!$basedir/!" | sed "s!DATAURI!$datauri!" | sed "s!MYEXP_PATH!$myexp_path!" | sed "s/MYSQL_USERNAME/$mysql_user/" | sed "s/MYSQL_PASSWORD/$mysql_password/" | sed "s/DATABASE/$database/" > ${basedir}/inc/settings.inc.php || { echo "Could not create inc/settings.inc.php. Aborting ..."; exit 3; }
echo "Updating myExperiment's config/settings.yml file"
sudo cat ${myexp_path}config/settings.yml | sed "s/^rdfgen_enable: false$BANG/rdfgen_enable: true/" | sed "s!^rdfgen_tool:$BANG!rdfgen_tool: ${basedir}/rdfgen/rdfgencli.php!" > /tmp/myexp_settings.yml || { echo "Could not amend myExperiment's config/settings.yml to enable RDF tool. Aborting ..."; exit 4; }
sudo cp /tmp/myexp_settings.yml ${myexp_path}/config/settings.yml || { echo "Could not overwrite myExperiment's config/settings.yml. Aborting ..."; exit 5; }
if [ -e $basedir/data/dataflows ]; then
        echo "Data folders have already been created for caching RDF for workflow components!"
else
	echo "Creating data folders to cache RDF for workflow components"
        cd $basedir/data/ || { echo "Could not find $basedir/data/ directory. Aborting ..."; exit 6; }
        mkdir dataflows/ || { echo "Could not create directory $basedir/data/dataflows/. Aborting ..."; exit 7; }  
        mkdir dataflows/inc || { echo "Could not create directory $basedir/data/dataflows/. Aborting ..."; exit 8; }
        sudo chmod -R a+w * || { echo "Could not give write access to all for $basedir/data/. Aborting ..."; exit 9; }
fi

echo ""
echo "====================================================="
echo "Installation complete! Please restart myExperiment   "
echo "Ruby-on-Rails instance to enable RDF generation tool."
echo "====================================================="
