<?php

// Load console class for autoloader?

$use_console = false;

// Optional mapping of Namespace to directories.
$namespace_mapping = array(
    'NigeLib'            => 'src',
    'Adldap'             => 'adLDAP/src',
    'Adldap\Interfaces'  => 'adLDAP/src/Interfaces',
    'Adldap\Connections' => 'adLDAP/src/Connections',
    'Adldap\Traits'      => 'adLDAP/src/Traits',
    'Adldap\Exceptions'  => 'adLDAP/src/Exceptions',
    'Adldap\Classes'     => 'adLDAP/src/Classes',
    'Adldap\Objects'     => 'adLDAP/src/Objects',
    'Adldap\Query'       => 'adLDAP/src/Query',
    'Aura\Sql'           => 'aura/Aura.Sql/src',
);

if( $use_console ) {
    require( 'src/Console.php' );
}

function PSR0_autoload($className)
{
    global $use_console;
    global $namespace_mapping;

    $className = ltrim($className, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strripos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        if( isset( $namespace_mapping[$namespace] ) ) {
            $path = $namespace_mapping[$namespace];
        } else {
            $path = $namespace;
        }
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $path) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    if( $use_console ) {
        NigeLib\Console::output( "Loading: $fileName for class $className in $namespace" );
    }

    $found = @include $fileName;
    if( !$found && $use_console ) {
        NigeLib\Console::output( "Loading: $fileName does not exist." );
    }
}

spl_autoload_register( 'PSR0_autoload' );
