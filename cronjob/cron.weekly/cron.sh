#!/bin/bash

GIT_DIR = /tmp/fellows

REPO_DIR = "visualisation"

DATA_DIR = /Public/Data/Fellows


#making Data directory
if [ ! -d "$DATA_DIR" ]; then mkdir $GIT_DIR; f

#Delete the repository if it exist
cd $GIT_DIR
rm -rf $REPO_DIR


#making repos directory
if [ ! -d "$GIT_DIR" ]; then mkdir $GIT_DIR; fi

if [ ! -d "$GIT_DIR/$REPO_DIR" ]; then
    init=true
    cd $GIT_DIR && pwd && git clone https://github.com/softwaresaved/visualisation.git && cd $REPO_DIR
#else
#    cd $GIT_DIR/$REPO_DIR
#    git checkout master && git up

fi

echo pwd

#Run the shell scripts in git repository

# run consultancy shell script
source get_consultancy_data.sh 1Jmq6ongECyJih7HbroltnAGZOecWOsl1qFdemw8Hj8A $DATA_DIR

# run swc shell script
source get_swc_data.sh 1xna48IRFl-lLJrPhlI7vC-1TWnl1kICxzEfrvpIKLp8 $DATA_DIR


#Delete the repository
cd $GIT_DIR
rm -rf $REPO_DIR
