Neste arquivo serão documentados passos, processos e problemas referentes à infraestrutura para instalação do projeto.

#Automatização (Vagrant)

#nginx e GNU/Linux
Nesta seção documentaremos a instalação com a utilização do *nginx* como webserver num sistema operacional GNU/Linux, baseado no Debian Jessie 8.0.

##Problemas comuns
###WP-CLI (WP Command Line Interface)
Um problema comum ao tentar utilizar o WP-CLI é que a extensão "php-mysql" não é automaticamente carregada para execução do php pela linha de comando. Assim, é preciso editar o arquivo "php.ini" (__/etc/php5/cli/php.ini__) e adicionar a chamada para a extensão php5-mysql (que deve estar instalada no sistema):
> extension=mysql.so
