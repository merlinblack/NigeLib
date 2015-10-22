#!/usr/bin/env php
<?php
require_once( 'autoloader.php' );

use NigeLib\Console;

Console::important( 'Test script for NigeLib PharBuilder.' );

$pb = new NigeLib\PharBuilder( 'phartest.phar', 'test.php', dirname( __FILE__ ) );

$pb->addExcludePrefix( '/.git' );
$pb->addExcludePrefix( '/composer' );
$pb->addExcludePrefix( '/phartest.php' );
$pb->addExcludeSuffix( '~' );

$pb->compile();
