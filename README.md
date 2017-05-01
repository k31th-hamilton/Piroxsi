# Piroxsi

## Network
### Configure Network Interfaces
/etc/network/interfaces

    auto lo
    iface lo inet loopback
    
    auto eth0
    iface eth0 inet static
      address 10.0.200.1
      netmask 255.255.255.0
    
    allow-hotplug wlan0
    
    iface wlan0 inet manual
    wpa-conf /etc/wpa_supplicant/wpa_supplicant.conf
### Configure DHCP Server
Install DHCP Server

	apt-get install isc-dhcp-server

/etc/default/isc-dhcp-server

	INTERFACES="eth0"

/etc/dhcp/dhcpd.conf

	default-lease-time 600;
	max-lease-time 7200;
	ddns-update-style none;
	log-facility local7;
	
	subnet 10.0.200.0 netmask 255.255.255.0 {
	  range 10.0.200.100 10.0.200.254;
	  option routers 10.0.200.1;
	  option domain-name-servers 10.0.1.15;
	}

Enable DHCP Server

	systemctl start isc-dhcp-server.service
	systemctl enable isc-dhcp-server.service

### Configure Firewall

/etc/sysctl.conf

	net.ipv4.ip_forward=1

/etc/firewall

	#!/bin/bash
	declare -x ipt="/sbin/iptables"
	
	#LAN
	declare -x local_lan="eth1"
	declare -x local_lan_net="10.0.200.0/24"
	
	declare -x outside_lan="eth0"	
	
	#FIREWALL CLEAR
	$ipt -F
	$ipt -X
	$ipt -Z
	$ipt -t nat -F
	
	#DEFAULT
	$ipt -P INPUT DROP
	$ipt -P FORWARD DROP
	$ipt -P OUTPUT ACCEPT
	
	$ipt -A INPUT -m conntrack --ctstate ESTABLISHED,RELATED -j ACCEPT
	$ipt -A INPUT -i lo -j ACCEPT
	$ipt -A INPUT -i $local_lan -j ACCEPT
	
	#NAT
	$ipt -A FORWARD -s $local_lan_net -j ACCEPT
	
	$ipt -A FORWARD -i $outside_lan -o $local_lan -m state --state RELATED,ESTABLISHED -j ACCEPT
	$ipt -A FORWARD -i $local_lan -o $outside_lan -j ACCEPT
	
	$ipt -t nat -A POSTROUTING -o $outside_lan -j MASQUERADE
	
	#SSH
	$ipt -A INPUT -p tcp --dport 22 -j ACCEPT

Make firewall executable

	chmod +x /etc/firewall

/etc/rc.local

	if [ -e '/etc/firewall' ]; then
	  /etc/firewall
	fi


## Add Sources
### Debian

/etc/apt/sources.list

	deb http://packages.dotdeb.org jessie all
	deb-src http://packages.dotdeb.org jessie all

Add GPG Key

	wget https://www.dotdeb.org/dotdeb.gpg
	sudo apt-key add dotdeb.gpg

### Raspbian

/etc/apt/sources.list

	deb http://repozytorium.mati75.eu/raspbian jessie-backports main contrib non-free
	#deb-src http://repozytorium.mati75.eu/raspbian jessie-backports main contrib non-free

Add GPG Key

	sudo gpg --keyserver pgpkeys.mit.edu --recv-key CCD91D6111A06851
	sudo gpg --armor --export CCD91D6111A06851 | sudo apt-key add -

### Update Sources

	apt-get update
		

## Configure Apache / PHP

	apt-get install apache2 php7.0 php7.0-cli php7.0-common php7.0-sqlite3 php7.0-zip php7.0-xml php7.0-mbstring libapache2-mod-php7.0


/etc/apache2/sites-available/piroxsi.conf

	<VirtualHost *:80>	  
	  ServerAdmin my@email.com
	
	  DocumentRoot "/var/www/piroxsi/public/"
	  DirectoryIndex index.php
	
	  <Directory "/var/www/piroxsi/public">
	    Options Indexes FollowSymLinks
	    AllowOverride All
	    Require all granted
	  </Directory>	
	</VirtualHost>

Enable mod_rewrite

	a2enmod rewrite
	service apache2 restart

## Install Git

	apt-get install git

## Install Composer

	php -r "copy('https://getcomposer.org/installer', '/tmp/composer-setup.php');"
	
Visit Composer's pubkeys and signatures page and copy the SHA-384 string at the top . Then, run the following command by replacing sha_384_string with the string you copied.

	php -r "if (hash_file('SHA384', '/tmp/composer-setup.php') === 'sha_384_string') { echo 'Installer verified'; } else { echo 'Installer corrupt'; unlink('/tmp/composer-setup.php'); } echo PHP_EOL;"	
	
Install
	
	sudo php /tmp/composer-setup.php --install-dir=/usr/local/bin --filename=composer

## Install Redis

	apt-get install build-essential
	apt-get install tcl8.5

	cd /tmp
    wget http://download.redis.io/releases/redis-stable.tar.gz
	tar xzf redis-stable.tar.gz
	cd redis-stable
	
	make
	make test
	make install

	cd utils
	./install_server.sh

	service redis_6379 start
	systemctl enable redis_6379.service

/etc/redis/6379.conf

	bind 127.0.0.1

## Install Supervisor

	apt-get install supervisor

/etc/supervisor/conf.d/piroxsi.conf
	
	[program:piroxsi-worker]
	process_name=%(program_name)s_%(process_num)02d
	command=php /var/www/piroxsi/artisan queue:work --sleep=3 --tries=3 --daemon
	autostart=true
	autorestart=true
	numprocs=2
	redirect_stderr=true
	stdout_logfile=/var/www/piroxsi/storage/logs/worker.log

/etc/rc.local

	su www-data -C '/usr/bin/supervisorctl start prioxsi-worker'

## Install Piroxsi

Clone Repo
	
	cd /var/www

	git clone https://github.com/keithhammy/piroxsi

Make some small modifications

	chown www-data:www-data -R /var/www
	chmod +x /var/www/piroxsi/resources/scripts/startResource
	chmod +x /var/www/piroxsi/resources/scripts/stopResource

Run composer install

	cd /var/www/piroxsi
	composer install

Add to sudo permissions

	root# visudo

	#/etc/sudoers.tmp
	www-data ALL=NOPASSWD: /var/www/piroxsi/resources/scripts/startResource
	www-data ALL=NOPASSWD: /var/www/piroxsi/resources/scripts/stopResource



