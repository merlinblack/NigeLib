<?php namespace NigeLib;

class Environment {
    private static $environments;

    public static function getEnvironmentName() {
        if( self::$environments === null ) {
            self::$environments = include( 'envmap.php' );
        }

        $localhost = gethostname();

        foreach( self::$environments as $name => $hosts ) {
            if( in_array( $localhost, $hosts ) )
                return $name;
        }

        return 'development';
    }
}
