# -*- mode: ruby -*-
# vi: set ft=ruby :

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

$firstTimeScript = <<SCRIPT

cd /vagrant && composer update && rm -r /var/www/public && ln -s /vagrant/src /var/www/public

cp /vagrant/config/wp-config-vagrant.php /vagrant/src/wp-config.php
cp /vagrant/config/htaccess-vagrant /vagrant/src/.htaccess

mysql --user=root --password=root -h 127.0.0.1 -e 'drop database participacao'
mysql --user=root --password=root -h 127.0.0.1 -e 'create database participacao'
mysql --user=root --password=root -h 127.0.0.1 participacao < /vagrant/db/db.sql

SCRIPT

$updateServices = <<SCRIPT

service mysql start

rm -r /var/www/public
ln -s /vagrant/src/ /var/www/public

service apache2 start

SCRIPT

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

    # Every Vagrant virtual environment requires a box to build off of.
    config.vm.box = "scotch/box"
    config.vm.network "forwarded_port", guest: 80, host: 8080
    config.vm.network "forwarded_port", guest: 80, host: 4567

    config.vm.provision "shell", inline: $firstTimeScript

    config.vm.provision "shell", inline: $updateServices,
            run: "always"

    config.vm.provider :virtualbox do |vb|
      vb.gui = true
    end
end



