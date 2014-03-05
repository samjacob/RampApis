READ ME
============
Once you have cloned the RampApis repository

Step 1 :
	Database setup

	1. Follow the steps below to set up the database in your virtual box.
		mysql -u root -p
		once it prompts for password
		type root
		
	2. now you will be in the mysql console.
	   Use the following command to create the database first.

	   create database ramp;
	   exit from mysql console using following command
	   exit;

	3. Use the following Command to import database from the sql file in mysqldb folder;

	syntax: mysql -u username -p database_name < "path of file to import" 
			mysql -u root -p ramp < /path to mysqldb/ramp_upd.sql

Step 2 :
Place the RampApis folder in /var/www/

Copy and Keep the test.html file outside of the RampApis directory and try to hit the APIs for response.

Step 3
While executing if you are not getting the API Response, instead an error that the File not found in server, it could be due to Apache configuration
Follow the steps below

sudo vi /etc/apache2/sites-available/default

You will get a file like below

<VirtualHost *:80>
    ServerAdmin webmaster@localhost

    DocumentRoot /var/www
    <Directory />
            Options FollowSymLinks
            AllowOverride None
    </Directory>
    <Directory /var/www/>
            Options Indexes FollowSymLinks MultiViews
            AllowOverride None
            
    You have to make only one change here change the AllowOverride None under  <Directory /var/www/> to AllowOverride All
    save and close the file.
    
    the next command is to enable module rewriting, use the command below:
    
    sudo a2enmod rewrite
    
    Now we have to restart apache to bring the new configuration to effect, use the following command
    
    sudo /etc/init.d/apache2 restart
    
    Next step is to make the RampApis folder executable, use the following command for the same
    
    go to /var/www/
    sudo chmod -R 777 RampApis
    
    now try the test.html from your browser, it should work fine.
    
    Note : my remote machine port is set to 8085, if yours is different , change it accordingly in test.html form action.
    
    
