<?php namespace NigeLib;

use \Symfony\Component\Yaml\Yaml;

class Config extends Singleton {
    private $basedir;
    private $commondir;
    private $environment;
    private $config = null;
    private $useYAML = false;

    public function init( $basedir, $environment = null, $commondir = null ) {
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

        if( $this->commondir != null ) {
            $this->loadfiles( $this->commondir );

            // Environment override for common settings.
            //
            if( $this->environment != null ) {
                $envdir = $this->commondir . DIRECTORY_SEPARATOR . $this->environment;
                if( file_exists( $envdir ) ) {
                    $this->loadfiles( $envdir );
                }
            }
        }

        // Default local configuration
        //
        $this->loadfiles( $this->basedir );

        // Environment override for local configuration
        //
        if( $this->environment != null ) {
            $envdir = $this->basedir . DIRECTORY_SEPARATOR . $this->environment;
            if( file_exists( $envdir ) ) {
                $this->loadfiles( $envdir );
            }
        }
    }

    private function loadfiles( $directory ) {
        // Use directory iterator, instead of glob, in case
        // $directory is inside a Phar file.
        $di = new \DirectoryIterator( $directory );
        foreach( $di as $file ) {
            $options = null;
            $ext = pathinfo( $file, PATHINFO_EXTENSION );
            $key = pathinfo( $file, PATHINFO_FILENAME );

            if( $ext === 'php' ) {
                $options = include( $directory . DIRECTORY_SEPARATOR . $file );
            }

            if( $this->useYAML ) {
                if( $ext === 'yml' || $ext === 'yaml' ) {
                    $options = Yaml::parse( file_get_contents( $directory . DIRECTORY_SEPARATOR . $file ) );
                }
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
        Console::debug( "Configuration for {$this->environment} environment\n" );
        Console::debug( $this->config );
    }

    public function __invoke( $index ) {
        return $this->get( $index );
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

