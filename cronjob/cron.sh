#!/bin/bash

GIT_DIR = /tmp/visualisation_repo
DATA_DIR = /var/www/html/ssi/drupal/sites/default/files/dashboard

#Delete the repository directory and recreate
rm -rf $GIT_DIR
mkdir -p $GIT_DIR

cd $GIT_DIR
git clone https://github.com/softwaresaved/visualisation.git
cd visualisation

#Run the shell scripts in git repository

# run consultancy shell script
source get_consultancy_data.sh 1Jmq6ongECyJih7HbroltnAGZOecWOsl1qFdemw8Hj8A $DATA_DIR

# run swc shell script
source get_swc_data.sh 1xna48IRFl-lLJrPhlI7vC-1TWnl1kICxzEfrvpIKLp8 $DATA_DIR
