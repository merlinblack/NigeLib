#!/usr/bin/env php
<?php
$autoloader_use_console = true;
require_once( 'autoloader.php' );

use NigeLib\ConfigFacade as Config;
use NigeLib\DatabaseConnectionManagerFacade as DB;
use NigeLib\Console;
use NigeLib\Environment;

Console::important( 'Test script for NigeLib.' );

Config::init( 'localconfig', Environment::getEnvironmentName('envmap.php'), 'config' );

Config::reload();
Config::dump();

Console::debug( DB::get('temp') );

// Invoke style...
$cfg = NigeLib\Config::getSingleton();
Console::info( $cfg('app.logfile') );

Console::info( Config::get('app.logfile') );
