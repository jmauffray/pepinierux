# Motion setup on PI

## Motion setup

As root user

```bash
cp -t /root startmotion.py clean.sh
chmod 755 /root/clean.sh

cp -rf /etc/motion /root/motion.backup
cp -t /etc/motion motion.conf camera-pepiniere.conf.in

crontab -e
00 06,18 * * * /root/clean.sh >> /root/clean.log
@reboot /usr/bin/python3 /root/startmotion.py >> /root/startmotion.log
```

Acces motion sur raspberry
* http://localhost:8081
* http://localhost:8080/

```bash
# enable debug mode
motion -s
```

## rpi connect

As root

```bash
apt install rpi-connect

# as user
rpi-connect signin

# as root
systemctl --user status rpi-connect
```

## tailscale

```bash
# enable authent 
tailscale login

# enable ssh
tailscale up –ssh
```
