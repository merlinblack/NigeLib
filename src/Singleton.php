<?php namespace NigeLib;

class Singleton {
    private static $_instances;

    protected function __construct() {
    }
    protected function __clone() {
    }
    protected function __wakeup() {
        throw new Exception("Can not unserialise a singleton");
    }

    final public static function getSingleton() {
        $subclass = get_called_class();
        if( ! isset( self::$_instances[$subclass] ) ) {
            self::$_instances[$subclass] = new static;
        }
        return self::$_instances[$subclass];
    }
}
