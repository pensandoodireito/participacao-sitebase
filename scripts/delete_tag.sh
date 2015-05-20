#!/bin/bash

if [ $# -eq 0  ]; then
    echo "No arguments supplied, exiting script"
    echo "Please provide tag name as input parameter"
    exit 0
fi


function delete_tag {

	git fetch --all

	git tag -d $1
	
	git push composer :refs/tags/$1

	git push --tags	

}

cd ..

delete_tag $1

cd src/wp-content/themes/participacao-tema

delete_tag $1

cd ../pensandoodireito-tema

delete_tag $1

cd ../marcocivil-tema

delete_tag $1

cd ../dadospessoais-tema

delete_tag $1

cd ../../plugins/pensandoodireito-network-functions

delete_tag $1

cd ../wp-side-comments

delete_tag $1

cd ../delibera

delete_tag $1

