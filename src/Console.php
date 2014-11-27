<?php namespace NigeLib;

class Console {
    const INFO  = 1;
    const DEBUG = 2;
    const ERROR = 3;
    const IMPORTAINT = 3;   // Same as error

    private static $bash_colors = array(
        self::ERROR    =>   "\033[1;31m", // Light Red
        self::DEBUG    =>   "\033[1;36m", // Light Cyan
        self::INFO     =>   "\033[1;32m", // Light Green
        0              =>   "\033[0m",   // Reset
    );

    private static $html_colors = array(
        self::ERROR    =>   'consoleerror',
        self::DEBUG    =>   'consoledebug',
        self::INFO     =>   'consoleinfo',
    );

    private static $level = self::INFO;

    public static function setLevel( $level ) {
        self::$level = $level;
    }
    public static function print_r( $mixed, $level = self::INFO ) {
        self::output( print_r( $mixed, true ), $level );
    }
    public static function output( $str, $level = self::INFO ) {
        if( $level < self::$level ) {
            return;
        }
        if( php_sapi_name() == 'cli' ) {
            $str = self::$bash_colors[$level] . $str . self::$bash_colors[0] . "\n";
        } else {
            $color = self::$html_colors[$level];
            $str = str_replace( "\n", '<br/>', htmlentities( $str ));
            $str = "<pre class='console $color'>" . $str . '</pre>' . "\n";
        }

        echo $str;
    }
}

