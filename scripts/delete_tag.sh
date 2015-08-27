#!/bin/bash

if [ $# -eq 0  ]; then
    echo "No arguments supplied, exiting script"
    echo "Please provide tag name as input parameter"
    exit 0
fi


function delete_tag {

	echo "Apagando tag de $1"

	git fetch --all

	git tag -d $1
	
	git push git@github.com:pensandoodireito/$2 :refs/tags/$1

	git push --tags	

}

cd ..

delete_tag $1 participacao-sitebase

cd src/wp-content/themes/participacao-tema

delete_tag $1 participacao-tema

cd ../pensandoodireito-tema

delete_tag $1 pensandoodireito-tema

cd ../marcocivil-tema

delete_tag $1 marcocivil-tema

cd ../dadospessoais-tema

delete_tag $1 dadospessoais-tema

cd ../debatepublico-tema

delete_tag $1 debatepublico-tema

cd ../blog-tema

delete_tag $1 blog-tema

cd ../../plugins/pensandoodireito-network-functions

delete_tag $1 pensandoodireito-network-functions

cd ../wp-side-comments

delete_tag $1 wp-side-comments

cd ../delibera

delete_tag $1 delibera

