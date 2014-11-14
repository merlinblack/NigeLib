<?php namespace NigeLib;

class Environment {
    private static $environments = array(
        'live' => array( 'extweb1.mailcall.com.au' ),
        'test' => array( 'supra' ),
    );

    public static function getEnvironmentName() {

        $localhost = gethostname();

        foreach( self::$environments as $name => $hosts ) {
            if( in_array( $localhost, $hosts ) )
                return $name;
        }

        return 'development';
    }
}
