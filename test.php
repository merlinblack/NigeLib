#!/usr/bin/env php
<?php
$autoloader_use_console = true;
require_once( 'autoloader.php' );

use NigeLib\Config;
use NigeLib\Console;
use NigeLib\Environment;
use NigeLib\DatabaseConnectionManager;
use NigeLib\SimpleTemplate;

Console::important( 'Test script for NigeLib.' );

$cfg = Config::getSingleton();
$cfg->init( 'localconfig', Environment::getEnvironmentName('envmap.php'), 'config' );

$cfg->reload();
$cfg->dump();

Console::debug( DatabaseConnectionManager::get('temp') );

// __invoke style
Console::info( $cfg('app.logfile') );

// Weird call a non static like a static
Console::info( Config::get('app.logfile') );

$t = new SimpleTemplate( 'template.php' );

echo $t->render( array(
    'title' => 'Very Simple Template',
    'text' => 'Yep. Pretty simple! If you want more use Twig or something.',
    'src' => htmlspecialchars(file_get_contents( 'template.php' )),
));
