== Installation du site web
```sh
#clone repository
git clone https://github.com/jmauffray/pepinierux.git
cd pepinierux

#copy default files
cp factux/include/config/common.php.default factux/include/config/common.php
cp factux/dbinfo.php.default factux/dbinfo.php

#copy and edit default file
cp factux/include/config/var.php.default factux/include/config/var.php

#copy and edit PDF logo and phyto passport
cp factux/image/logo.png.default factux/image/logo.png
cp factux/image/passeport-phyto.jpg.default factux/image/passeport-phyto.jpg

#change user rights for web server access
chmod 777 factux/include/session
chmod 777 factux/fpdf
chmod 777 factux/uploads

#go to home page and connect login:admin pwd:admin
firefox http://localhost/pepinierux/factux/
```

== Installation de LAMP (Linux/Apache/MariaDB/PHP)
Factux does not work with PHP 7.
```sh
#download xampp version v5.6.32 : https://www.apachefriends.org/xampp-files/5.6.32/xampp-linux-x64-5.6.30-0-installer.run
sudo xampp-linux-x64-5.6.32-0-installer.run

#change user right
cd /opt/lampp/
sudo chown $USER:$USER htdocs

#active lamp during pc starting on ubuntu 16.04 with upstart, not mandatory 
sudo cp factux/factux.service /etc/systemd/system
sudo chmod 755 /etc/systemd/system/factux.service
cd /etc/systemd/system/multi-user.target.wants
sudo ln -s ../factux.service factux.service

#useful debug commands for upstart
systemctl enable factux.service
systemctl start factux.service
systemctl stop factux.service
journalctl -u factux.service

#create link to website, check if valid link, replace $USER below if necessary
sudo ln -s /home/$USER/pepinierux /opt/lampp/htdocs

#start lampp manually
sudo /opt/lampp/lampp start

#create database
/opt/lampp/bin/mysql -uroot -e "CREATE DATABASE factux"
/opt/lampp/bin/mysql -uroot factux < factux.sql

#backup database during boot, create script sauvegardeFactux.sh, not mandatory
mkdir /home/$USER/pepinierux-sql-backup
cat <<EOF >> /home/$USER/pepinierux-sql-backup/sauvegardeFactux.sh
#!/bin/sh
#add sleep 30 if not working
#sleep 30
/opt/lampp/bin/mysqldump --user root factux > /home/$USER/pepinierux-sql-backup/factux-sql-backup/factux-`date +%F`.sql
EOF
chmod 755 /home/$USER/pepinierux-sql-backup/sauvegardeFactux.sh

#edit crontab
crontab -e
@reboot  /home/$USER/pepinierux-sql-backup/sauvegardeFactux.sh >>/dev/null
```
