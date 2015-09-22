#!/bin/bash

BLUE='\033[0;34m'
RED='\033[0;31m'
GREEN='\033[0;32m'
NC='\033[0m'

function repo_info {

	echo "Informações para o repositório $1"

	branch=$(git branch | sed -n -e 's/^\* \(.*\)/\1/p')	

	modified=`git ls-files -m`

	printf "Branch: $GREEN$branch$NC\n"

 	if [ "$modified" == "" ]; then
 		printf "${BLUE}Sem alterações$NC\n"
 	else
 		printf "Atenção, existem arquivos modificados neste repositório!!!!"
 		printf "\n\n$RED$modified$NC\n\n"
 	fi

 	printf "\n--------------------------------------------\n"
}

cd ..

repo_info participacao-sitebase

cd src/wp-content/themes/participacao-tema

repo_info participacao-tema

cd ../pensandoodireito-tema

repo_info pensandoodireito-tema

cd ../marcocivil-tema

repo_info marcocivil-tema

cd ../dadospessoais-tema

repo_info dadospessoais-tema

cd ../debatepublico-tema

repo_info debatepublico-tema

cd ../blog-tema

repo_info blog-tema

cd ../../plugins/pensandoodireito-network-functions

repo_info pensandoodireito-network-functions

cd ../wp-side-comments

repo_info wp-side-comments

cd ../delibera

repo_info delibera

