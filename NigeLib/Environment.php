<?php namespace NigeLib;

class Environment {
    private static $environments = array(
        'live' => array( 'atkinson.kiwi' ),
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
