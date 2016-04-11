<?php namespace NigeLib;

use \Symfony\Component\Yaml\Yaml;

class Config extends Singleton {
    private $basedir;
    private $commondir;
    private $environment;
    private $config = null;
    private $useYAML = false;

    public function init( $basedir, $environment, $commondir = '' ) {
        if( ! isset( $this ) ) {
            Config::getSingleton()->init( $basedir, $environment, $commondir );
            return;
        }
        $this->basedir = $basedir;
        $this->environment = $environment;
        $this->commondir = $commondir;
        if( class_exists( 'Symfony\Component\Yaml\Yaml' ) ) {
            $this->useYAML = true;
        }
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
            $options = null;

            if( substr($file,-4) === '.php' ) {
                $key = basename( $file, '.php' );
                $options = include( $directory . DIRECTORY_SEPARATOR . $file );
            }

            if( $this->useYAML && substr( $file, -4 ) == '.yml' ) {
                $key = basename( $file, '.yml' );
                $options = Yaml::parse( file_get_contents( $directory . DIRECTORY_SEPARATOR . $file ) );
            }

            if( $this->useYAML && substr( $file, -5 ) == '.yaml' ) {
                $key = basename( $file, '.yaml' );
                $options = Yaml::parse( file_get_contents( $directory . DIRECTORY_SEPARATOR . $file ) );
            }

            if( $options ) {
                if( ! isset( $this->config[ $key ] ) ) {
                    $this->config[ $key ] = array();
                }
                $this->config[ $key ] = array_replace_recursive( $this->config[ $key ], $options );
            }
        }
    }

    public function dump() {
        Console::output( "Configuration for {$this->environment} environment\n", Console::DEBUG );
        Console::debug( $this->config );
    }

    public function __invoke( $index ) {
        return $this->get( $index );
    }

    public function get( $index, $default = null ) {
        if( ! isset($this) ) {   // Called statically
            return self::getSingleton()->get( $index, $default );
        }

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

