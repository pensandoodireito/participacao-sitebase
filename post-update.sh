cp config/wp-config-vagrant.php src/wp-config.php
cp config/htaccess-vagrant src/.htaccess
cp config/file-remote.php src/file-remote.php
cp config/index-vagrant.php src/index.php
rm -f src/wp/index.php
ln -s wp/wp-admin src/wp-admin
ln -s wp/wp-includes src/wp-includes
ln -s docs src/docs