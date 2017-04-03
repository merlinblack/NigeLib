<?php

// Load console class for autoloader?
if( ! isset( $autoloader_use_console ) ) {
    $autoloader_use_console = false;
}


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

$autoload_classes_not_found = array();

if( $autoloader_use_console ) {
    require( 'src/Console.php' );
}

function PSR0_autoload($name)
{
    global $autoloader_use_console;
    global $autoload_namespace_mapping;
    global $autoload_classes_not_found;

    if( isset( $autoload_classes_not_found[$name] ) ) {
        return;
    }

    $className = ltrim($name, '\\');
    $fileName  = '';
    $namespace = '';
    if ($lastNsPos = strripos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        if( isset( $autoload_namespace_mapping[$namespace] ) ) {
            $path = $autoload_namespace_mapping[$namespace];
        } else {
            $path = $namespace;
        }
        $fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $path) . DIRECTORY_SEPARATOR;
    }
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

    if( $autoloader_use_console ) {
        NigeLib\Console::output( "Autoloader: Including $fileName for class $className in $namespace" );
    }

    $found = @include $fileName;
    if( !$found ) {
        $autoload_classes_not_found[$name] = true;
        if( $autoloader_use_console ) {
            NigeLib\Console::output( "Autoloader: $fileName does not exist." );
        }
    }
}

spl_autoload_register( 'PSR0_autoload' );
