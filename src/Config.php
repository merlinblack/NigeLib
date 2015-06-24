<?php namespace NigeLib;

use \ArrayAccess;

class Config extends Singleton implements ArrayAccess {
    private $basedir;
    private $commondir;
    private $environment;
    private $config = null;

    public function init( $basedir, $environment, $commondir = '' ) {
        $this->basedir = $basedir;
        $this->environment = $environment;
        $this->commondir = $commondir;
    }

    public function reload() { $this->load(); }

    private function load() {
        // If commondir is set, load this config first.
        // This should contain things like database connection info for multiple
        // applications.

        if( $this->commondir != '' ) {
            $this->loadfiles( $this->commondir );

            // Environment override for common settings.
            //
            $envdir = $this->commondir . DIRECTORY_SEPARATOR . $this->environment;
            if( file_exists( $envdir ) ) {
                $this->loadfiles( $envdir );
            }
        }

        // Default local configuration
        //
        $this->loadfiles( $this->basedir );

        // Environment override for local configuration
        //
        $envdir = $this->basedir . DIRECTORY_SEPARATOR . $this->environment;
        if( file_exists( $envdir ) ) {
            $this->loadfiles( $envdir );
        }
    }

    private function loadfiles( $directory ) {
        // Use directory iterator, and substr instead of glob, in case
        // $directory is inside a Phar file.
        $di = new \DirectoryIterator( $directory );
        foreach( $di as $file ) {
            if( substr($file,-4) === '.php' ) {
                $key = basename( $file, '.php' );
                $options = include( $directory . DIRECTORY_SEPARATOR . $file );
                if( ! isset( $this->config[ $key ] ) ) {
                    $this->config[ $key ] = array();
                }
                $this->config[ $key ] = array_replace_recursive( $this->config[ $key ], $options );
            }
        }
    }

    public function dump() {
        Console::output( "Configuration for {$this->environment} environment\n", Console::DEBUG );
        Console::print_r( $this->config, Console::DEBUG );
    }

    public function offsetSet( $offset, $value ) {
    }

    public function offsetUnset( $offset ) {
    }

    public function offsetExists( $offset ) {
        return $this->get( $offset ) !== null;
    }

    public function offsetGet( $offset ) {
        return $this->get( $offset );
    }

    public function get( $index, $default = null ) {

        if( $this->config == null ) {
            $this->load();
        }

        $key_chain = explode( '.', $index );

        $value =& $this->config;

        foreach( $key_chain as $key ) {
            if( ! isset( $value[ $key ] ) ) {
                return $default;
            }
            $value =& $value[ $key ];
        }

        return $value;
    }
};

