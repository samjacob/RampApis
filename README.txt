READ ME
============
Once you have cloned the RampApis repository

Step1 :
	Database setup

	1. Follow the steps below to set up the database in your virtual box.
		mysql -u root -p
		once it prompts for password
		type root
		
	2. now you will be in the mysql console.
	   Use the following command to create the database first.

	   create database ramp;

	3. Use the following Command to import database from the sql file in mysqldb folder;

	syntax: mysql -u username -p database_name < "path of file to import" 
			mysql -u root -p ramp < /path to mysqldb/ramp_upd.sql

Step2 :
Place the RampApis folder in /var/www/

Copy and Keep the test.html file outside of the RampApis directory and try to hit the APIs for response.