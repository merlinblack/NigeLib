<?php namespace NigeLib;

/* Classes can extend this class to provide a static interface to
 * an instance of another class.
 *
 * This is similar in result to Laravel Facades
 *
 * getInstance is overridden to obtain a singleton instance or retrieve
 * an instance from a IoC container.
 */

abstract class StaticDelegate
{
    // Override this for each static delegate façade
    abstract public static function getInstance();

    public static function __callStatic($method, $args)
    {
        $instance = static::getInstance();

        switch (count($args)) {
            case 0:
                return $instance->$method();

            case 1:
                return $instance->$method($args[0]);

            case 2:
                return $instance->$method($args[0], $args[1]);

            case 3:
                return $instance->$method($args[0], $args[1], $args[2]);

            case 4:
                return $instance->$method($args[0], $args[1], $args[2], $args[3]);

            default:
                return call_user_func_array(array($instance, $method), $args);
        }
    }
}
