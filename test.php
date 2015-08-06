#!/usr/bin/env php
<?php
require_once( 'autoloader.php' );

use NigeLib\Config;
use NigeLib\Console;
use NigeLib\Environment;
use NigeLib\DatabaseConnectionManager;

Console::important( 'Test script for NigeLib.' );

$cfg = Config::getSingleton();
$dbmgr = DatabaseConnectionManager::getSingleton();

$cfg->init( 'localconfig', Environment::getEnvironmentName('envmap.php'), 'config' );

$cfg->reload();
$cfg->dump();

// __invoke style
Console::info( $cfg('app.logfile') );

// Weird call a non static like a static
Console::info( Config::get('app.logfile') );
