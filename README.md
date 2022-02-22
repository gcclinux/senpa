# senpa
SENPA - Secure Encrypted Network Password Archiver

SENPA is a securely encrypted network password and data archiver, a single location accessable everywhere as long as you have internet connect to store your passwords, notes, details, cards and one-time password (OTP). We take privacy and security really seriously and this was developed for the confort of all to insure your passwords are not left on the desk, table or sticker notes and now days every site, app, game has a password and you should NEVER have the same password across differrent site, app, game. There for with all those differrent passwords it's hard to remeber them all and SENPA is the one place you can save all your passwords securely and encrypted.

![alt text](https://i0.wp.com/www.wagemaker.co.uk/wp-content/uploads/2020/03/Screenshot-from-2020-01-25-22-47-27.png)
![alt text](https://i0.wp.com/www.wagemaker.co.uk/wp-content/uploads/2020/03/Screenshot-from-2020-01-25-22-47-42.png)
![alt text](https://i0.wp.com/www.wagemaker.co.uk/wp-content/uploads/2020/03/Screenshot-from-2020-03-18-20-19-02.png)
![alt text](https://i0.wp.com/www.wagemaker.co.uk/wp-content/uploads/2020/03/Screenshot-from-2020-03-23-08-07-39.png)
![alt text](https://i0.wp.com/www.wagemaker.co.uk/wp-content/uploads/2020/03/Screenshot-from-2020-03-23-08-10-38.png)
![alt text](https://i0.wp.com/www.wagemaker.co.uk/wp-content/uploads/2020/03/Screenshot-from-2020-01-25-22-39-39.png)
![alt text](https://i0.wp.com/www.wagemaker.co.uk/wp-content/uploads/2020/03/Screenshot-from-2020-03-23-08-40-07.png)

POSTGRESQL:

$ psql -h localhost -d postgres -U senpa -f /var/www/html/senpa/setup/postgresql.sql

Ubuntu 20.4 - PostgreSQL12 - PHP7.4 - SENPA 2019.2

$ sudo apt install php7.4 php7.4-bcmath php7.4-bz2 php7.4-cgi php7.4-curl php7.4-dev php7.4-gd php7.4-gmp php7.4-intl php7.4-mbstring php7.4-pgsql php7.4-soap php7.4-xml php7.4-zip pkg-php-tools -y

$ sudo apt install git apache2 libapache2-mod-php7.4 libapache2-mod-php -y

$ sudo groupadd senpa
$ sudo useradd -M senpa -g senpa
$ cd /var/www/html
$ sudo git clone https://github.com/gcclinux/senpa.git
$ sudo chown -R senpa:senpa senpa

$ sudo apt-get install postgresql-12 -y
$ sudo service postgresql status

$ sudo -i -u postgres
postgres@senpa:~$ createuser --interactive
Enter name of role to add: senpa
Shall the new role be a superuser? (y/n) y

postgres@senpa:~$ psql
postgres=# alter user senpa with password 'senpa';
ALTER ROLE
postgres=# alter user postgres with password 'senpa';
ALTER ROLE

postgres=# \q
postgres@senpa:~$ exit

$ psql -h localhost -d postgres -U senpa
Password for user senpa:
psql (12.9 (Ubuntu 12.9-0ubuntu0.20.04.1))
SSL connection (protocol: TLSv1.3, cipher: TLS_AES_256_GCM_SHA384, bits: 256, compression: off)
Type "help" for help.

postgres=#
postgres=# \q

$ cd /var/www/html/senpa/setup
$ psql -h localhost -d postgres -U senpa -f postgresql.sql

$ cd /var/www/html/senpa/s/cnf
$ cp -vR config-default.php config.php
$ EDIT config.php


