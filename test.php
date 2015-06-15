#!/usr/bin/env php
<?php
require_once( 'autoloader.php' );

use NigeLib\Console;

//Console::setLevel( Console::DEBUG );

Console::important( 'Test script for NigeLib.' );

NigeLib\Config::getSingleton()->init( 'localconfig', NigeLib\Environment::getEnvironmentName('envmap.php'), 'config' );

$dbmgr = NigeLib\DatabaseConnectionManager::getSingleton();

Console::debug( $dbmgr->get() );

NigeLib\Config::getSingleton()->dump();

$ad = new adLDAP\adLDAP;
