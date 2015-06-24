#!/usr/bin/env php
<?php
require_once( 'autoloader.php' );

use NigeLib\Console;

//Console::setLevel( Console::DEBUG );

Console::important( 'Test script for NigeLib.' );

$cfg = NigeLib\Config::getSingleton();
$dbmgr = NigeLib\DatabaseConnectionManager::getSingleton();

$cfg->init( 'localconfig', NigeLib\Environment::getEnvironmentName('envmap.php'), 'config' );


Console::debug( $dbmgr->get() );

$cfg->dump();

Console::info( $cfg['app.logfile'] );
