#!/usr/bin/env php
<?php
$autoloader_use_console = true;
require_once( 'autoloader.php' );

use NigeLib\Console;
use NigeLib\ConfigFacade as Config;
use NigeLib\DatabaseConnectionManagerFacade as DB;

Config::init( 'localconfig', null, 'config' );

Console::info( DB::get('temp') );
