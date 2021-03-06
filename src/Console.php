<?php namespace NigeLib;

class Console {
    const INFO  = 1;
    const DEBUG = 2;
    const ERROR = 3;
    const IMPORTANT = 3;   // Same as error

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
    private static $html = '';
    private static $outputOnDestruct = null;

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
            if( posix_isatty( STDOUT ) ) {
                $str = self::$bash_colors[$level] . $str . self::$bash_colors[0] . "\n";
            } else {
                $str .= "\n";
            }

            echo $str;

        } else {
            if( ! self::$outputOnDestruct ) {
                self::$outputOnDestruct = new ConsoleWriteOnDestruct;
            }
            $color = self::$html_colors[$level];
            $str = str_replace( "\n", '<br/>', htmlentities( $str ));
            $str = "<pre class='console $color'>" . $str . '</pre>' . "\n";
            self::$html .= $str;
        }

    }
    public static function info( $mixed ) {
        self::output( print_r( $mixed, true ), self::INFO );
    }
    public static function debug( $mixed ) {
        self::output( print_r( $mixed, true ), self::DEBUG );
    }
    public static function error( $mixed ) {
        self::output( print_r( $mixed, true ), self::ERROR );
    }
    public static function important( $mixed ) {
        self::output( print_r( $mixed, true ), self::IMPORTANT );
    }
    public static function getHtml() {
        return self::$html;
    }
    public static function clearHtml() {
        self::$html = '';
    }
    public static function disableHtmlFlush() {
        if( ! self::$outputOnDestruct ) {
            self::$outputOnDestruct = new ConsoleWriteOnDestruct;
        }
        self::$outputOnDestruct->disable();
    }
}

// In the case of a web page rather than a terminal or file output, this
// automatically writes all Console output to a web page at the end of a
// script when this is destructed.
//
// For more control over output, call Console::disableHtmlFlush() and
// retrieve the output with Console::getHtml()
//
class ConsoleWriteOnDestruct {
    private $disable = false;

    public function disable() {
        $this->disable = true;
    }
    public function __destruct () {
        if( $this->disable === false ) {
            echo Console::getHtml();
        }
    }
}

