#!/bin/bash

function update_master {

	branch=$(git branch | sed -n -e 's/^\* \(.*\)/\1/p')	
	
	git stash
	
	git checkout master

	git pull https://github.com/pensandoodireito/$1.git master

	git checkout $branch

	git stash pop 
}

cd ..

update_master participacao-sitebase $1

cd src/wp-content/themes/participacao-tema

update_master participacao-tema $1

cd ../pensandoodireito-tema

update_master pensandoodireito-tema $1

cd ../marcocivil-tema

update_master marcocivil-tema $1

cd ../dadospessoais-tema

update_master dadospessoais-tema $1

cd ../debatepublico-tema

update_master debatepublico-tema $1

cd ../intercambio-tema

update_master intercambio-tema $1

cd ../../plugins/pensandoodireito-network-functions

update_master pensandoodireito-network-functions $1

cd ../wp-side-comments

update_master wp-side-comments $1

cd ../delibera

update_master delibera $1

