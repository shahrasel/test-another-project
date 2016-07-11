MySQL Backup Pro  v1.0.8-PRE1

Author : Ben Yacoub Hatem <hatem at php dot net>

README : 

This package is meant to provide backup and restore services of MySQL databases.

It provides a friendly tab based user interface that lets the users create backups of 

given MySQL databases to files, listing previously generated backups, restore a given 

backup and deleting backup files.

Currently this package requires PHP with the bzip or zlib extension enabled to 

generate compressed backup files. It also uses the gonxtabs class to create an 

user-friendly navigation menu.

Localisation support is enabled to let you configure your application with your 

default langage and change it, or to add a new translation.


REQUIRE :
	PHP4.x or PHP5.x, MySQL

INSTALL :
	1 - Backup Folder : Create backup folder in default application directory. 

You can create it in another location, edit libs/backup.class.php and change the 

backup folder location.
	If you're using *nix verify that you have write permission for backup folder 

or chmod to 0777.

	2 - Configure init.php, or chmod init.php to 777 to use the configuration 

tool from the interface.


AUTOMATE Backup creation

	1- Copy init.php, libs/backup.class and libs/db.class.php to the location you 

want. for example : /var/www/autobackup/
	2 - Edit init.php and remove unecessary required classes.
	3 - Edit backup.class.php and change backup folder location for example : 

/var/www/autobackup/backup (and be sure to chmod it to 777)
	4 - Create a new cron (crontab -e):
10 * * * * /var/www/mysql/backupdb.php
if you don't know how to use cron job search google for a tutorial or a 

howto(http://www.google.com/search?q=create+cron+job)
	5 - and that's all, now you can enter the web interface to monitor your 

database or to restore it ...etc.


NOTES ABOUT PHP5.x : 
	HTTP Authentification Could not work properly with PHP5, so please comment 

the Authentification line in index.php and protect your application with .htaccess


TODO : 
	1 - Add abstraction database for PostgreSQL, Oracle, MSSQL, SQLite
	2 - Optimize the restore time.
	3 - Support multiple database


Author : Ben Yacoub Hatem <hatem at php dot net>


Special Thanks to : 

Jose L. Calle Villalba <jl.calle at uma dot es> for the Spanish translation
Gunther Rissmann <rissmann at gmx dot de> for the German translation
Philippe BENVENISTE <info at pommef dot com> for the javascript.

And for all who send me emails feedback or bug report.