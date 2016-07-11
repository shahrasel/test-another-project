<?php
$GonxAdmin["dbhost"] = "localhost";
$GonxAdmin["dbname"] = "annanovas";
$GonxAdmin["dbuser"] = "root";
$GonxAdmin["dbpass"] = "root";
$GonxAdmin["dbtype"] = "mysql";

/*
$GonxAdmin["dbhost"] = $db_host;
$GonxAdmin["dbname"] = $db_database;
$GonxAdmin["dbuser"] = $db_user;
$GonxAdmin["dbpass"] = $db_password;
$GonxAdmin["dbtype"] = $db_driver;
*/

$GonxAdmin["compression"] = array("bz2","zlib");
$GonxAdmin["compression_default"] = "zlib";
$GonxAdmin["locale"] = "en";
$GonxAdmin["pagedisplay"] = 10;
$GonxAdmin["mysqldump"] = "D:/mysql/bin/mysqldump.exe";


require_once("libs/db.class.php");
require_once("libs/gonxtabs.class.php");
require_once("libs/backup.class.php");
require_once("libs/locale.class.php");	// Localisation class


?>