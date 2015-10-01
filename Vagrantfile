# -*- mode: ruby -*-
# vi: set ft=ruby :

require 'ffi'

# Vagrantfile API/syntax version. Don't touch unless you know what you're doing!
VAGRANTFILE_API_VERSION = "2"

$firstTimeScript = <<SCRIPT
cd /vagrant
composer update
cp config/wp-config-vagrant.php src/wp-config.php
cp config/htaccess-vagrant src/.htaccess
cp config/file-remote.php src/file-remote.php
cp config/index-vagrant.php src/index.php
rm -f src/wp/index.php
npm install
./node_modules/.bin/gulp
apt-get install -y subversion
service apache2 start
service mysql start

mysql --user=root --password=root -h 127.0.0.1 -e 'drop database participacao'
mysql --user=root --password=root -h 127.0.0.1 -e 'create database participacao'

cd /vagrant/db
bunzip2 db.sql.bz2
mysql --user=root --password=root -h 127.0.0.1 participacao < /vagrant/db/db.sql
bzip2 -9 db.sql

cd /vagrant/src
ln -s wp/wp-admin wp-admin

SCRIPT

$updateServices = <<SCRIPT

service mysql restart

rm -r /var/www/public
ln -s /vagrant/src/ /var/www/public
chmod 777 /vagrant/src/wp-content/

service apache2 restart

SCRIPT

Vagrant.configure(VAGRANTFILE_API_VERSION) do |config|

    # Every Vagrant virtual environment requires a box to build off of.
    config.vm.box = "scotch/box"
    config.vm.network "forwarded_port", guest: 80, host: 80
    config.vm.network "forwarded_port", guest: 80, host: 8080
    # Port de debug do xdebug
    config.vm.network "forwarded_port", guest: 9000, host: 9000
    config.vm.network "private_network", ip: "192.168.33.10"
    
    config.vm.provision "shell", inline: $firstTimeScript

    config.vm.provision "shell", inline: $updateServices,
            run: "always"

    # Use 'vagrant plugin install vagrant-triggers' to install the trigger module
    config.trigger.after [:provision, :up, :reload] do
        if FFI::Platform::IS_LINUX
            system("sudo iptables -t nat -A OUTPUT -o lo -p tcp --dport 80 -j REDIRECT --to-port 8080")
        elsif FFI::Platform::IS_MAC
            system('echo "
                rdr pass on lo0 inet proto tcp from any to 127.0.0.1 port 80 -> 127.0.0.1 port 8080
                rdr pass on lo0 inet proto tcp from any to 127.0.0.1 port 443 -> 127.0.0.1 port 8443
                " | sudo pfctl -ef - > /dev/null 2>&1;')
        end
        system("echo '==> Fowarding Ports: 80 -> 8080, 443 -> 8443'")
    end

    config.trigger.after [:halt, :destroy] do
        if FFI::Platform::IS_LINUX
            system("sudo iptables -t nat -D OUTPUT -o lo -p tcp --dport 80 -j REDIRECT --to-port 8080")
        elsif FFI::Platform::IS_MAC
            system("sudo pfctl -df /etc/pf.conf > /dev/null 2>&1;")
        end
        system("echo '==> Removing Port Forwarding'")
    end

    #config.vm.provider :virtualbox do |vb|
    #  vb.gui = true
    #end
end
