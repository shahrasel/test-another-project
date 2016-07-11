#!/usr/bin/php -q
<?php
/**
 * MySQL Backup Pro index
 * 
 * @package GONX
 * @author Ben Yacoub Hatem <hatem@php.net>
 * @copyright Copyright (c) 2004
 * @version $Id$ - 08/04/2004 16:20:30 - index.php
 * @access public
 **/
 
require_once("init.php");
$b = new backup;
$b->dbconnect($GonxAdmin["dbhost"],$GonxAdmin["dbuser"],$GonxAdmin["dbpass"],$GonxAdmin["dbname"]);
$b->generate();
$b->keep(4); // Keep backup for the latest 4 days
?>