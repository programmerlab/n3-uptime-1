# Import Database Schema

## Pre-requisites
Before we import database schema, makes sure that RDS instance created under ASW with below config values.

* Zone: us-east-1b
* DB instance class: db.t2.micro
* Allocated storage: 5 GiB
* Storage type: General Purpose (SSD)

## Download Mysql Workbench
To install MySQL workbench execute below command under linux.

```sh
sudo apt update && sudo apt upgrade
sudo apt install mysql-workbench
```

## Command to luanch MySQL Workbench

```sh
mysql-workbench
```

## Connect to the RDS instance with MySQL Workbench
First of all we need to find the 'Endpoint' from the RDS instance details page. The endpoint is the host name to connect from MySQL workbench.

Under new connection wizard, enter below details & connect.
Connection Name: Any name to identify the connection
Connection Method: Select "Standard(TCP/IP)"
Hostname: Enter "Endpoint" from DB instance page
Username: DB Username
Password: DB Password

## Import schema into n3uptimev2 database
The database SQL file is available under "n3-uptime/db/" folder.
Import or execute the SQL file under n3uptimev2 database.

## Import using MySQL CLI
We can also import the schema using MySQL CLI.
Command to import:

```sh
cd <projectRootDirectory>
mysql -u <user> -p -h <host> <db> < db/2018_05_10_n3uptime2.sql
```


