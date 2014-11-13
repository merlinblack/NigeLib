#!/usr/bin/env php
<?php
require_once( 'autoloader.php' );

use NigeLib\Console;

//Console::setLevel( Console::DEBUG );

Console::output( 'Test script for NigeLib.', Console::IMPORTAINT );


NigeLib\Config::getSingleton()->init( 'config', NigeLib\Environment::getEnvironmentName() );

$dbmgr = NigeLib\DatabaseConnectionManager::getSingleton();

Console::print_r( $dbmgr->get(), Console::DEBUG );

NigeLib\Config::getSingleton()->dump();
