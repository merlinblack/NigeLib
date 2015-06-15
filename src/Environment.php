<?php namespace NigeLib;

class Environment {
    private static $environments;

    public static function getEnvironmentName( $mapfile ) {
        if( self::$environments === null ) {
            self::$environments = include( $mapfile );
        }

        $localhost = gethostname();

        foreach( self::$environments as $name => $hosts ) {
            if( in_array( $localhost, $hosts ) )
                return $name;
        }

        return 'development';
    }
}
