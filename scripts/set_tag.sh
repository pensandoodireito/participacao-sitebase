#!/bin/sh

if [ $# -eq 0  ]; then
    echo "No arguments supplied, exiting script"
    echo "Please provide tag name as input parameter"
    exit 0
fi

function fetch_settag {

	git fetch --all
	git checkout $1

}

cd ..

fetch_settag $1

cd src/wp-content/themes/participacao-tema

fetch_settag $1

cd ../pensandoodireito-tema

fetch_settag $1

cd ../marcocivil-tema

fetch_settag $1

cd ../dadospessoais-tema

fetch_settag $1

cd ../../plugins/pensandoodireito-network-functions

fetch_settag $1

cd ../wp-side-comments

fetch_settag $1

cd ../delibera

fetch_settag $1
