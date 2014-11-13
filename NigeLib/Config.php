<?php namespace NigeLib;

class Config extends Singleton {
    private $basedir;
    private $environment;
    private $config = null;

    public function init( $basedir, $environment ) {
        $this->basedir = $basedir;
        $this->environment = $environment;
    }

    public function reload() { $this->load(); }

    private function load() {
        // Default configuration
        //
        $this->loadfiles( $this->basedir );

        // Environment override
        //
        $envdir = $this->basedir . DIRECTORY_SEPARATOR . $this->environment;
        if( file_exists( $envdir ) ) {
            $this->loadfiles( $envdir );
        }
    }

    private function loadfiles( $directory ) {
        foreach( glob( $directory . DIRECTORY_SEPARATOR . '*.php' ) as $file ) {
            $key = basename( $file, '.php' );
            $options = include( $file );
            if( ! isset( $this->config[ $key ] ) ) {
                $this->config[ $key ] = array();
            }
            $this->config[ $key ] = array_replace_recursive( $this->config[ $key ], $options );
        }
    }

    public function dump() {
        Console::output( "Configuration for {$this->environment} environment\n", Console::DEBUG );
        Console::print_r( $this->config, Console::DEBUG );
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

