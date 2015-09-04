#!/bin/bash

COMPOSER=''

PROJECT=$1

#need composer check

#check for PHP version and grep for PHP 5.x

# if symfony is installed
symfony new ${PROJECT}

# else if PHP 5.3 (Centos!)
composer create-project symfony/framework-standard-edition $PROJECT "2.6.3"

#generate the SSI bundle for the code
php app/console generate:bundle --namespace=SSI/DataBundle --dir=src/ --no-interaction

