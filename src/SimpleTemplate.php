<?php namespace NigeLib;

use \ArrayAccess;
use \Exception;

class SimpleTemplate implements ArrayAccess
{
    private $filename;
    private $source;
    private $parameters = array();

    public function __construct( $filename = null, $source = null ) {
        $this->filename = $filename;
        $this->source = $source;
    }

    public function setParameters( array $parameters ) {
        $this->parameters = $parameters;
        return $this;
    }

    public function setSource( $source ) {
        $this->source = $source;
        return $this;
    }

    public function render( array $extra_parameters = array() ) {

        if( $this->source == '' ) {
            if( ! is_readable( $this->filename ) ) {
                return false;
            }
            $this->source = file_get_contents( $this->filename );
        }

        $parameters = array_merge_recursive( $this->parameters, $extra_parameters );

        return SimpleTemplateRender( $this->source, $parameters );
    }

    // ArrayAccess
    public function offsetSet( $offset, $value ) {
        if( is_null( $offset ) ) {
            throw new Exception( "Parameters must have keys" );
        }
        $this->parameters[$offset] = $value;
    }

    public function offsetExists( $offset ) {
        return isset( $this->parameters[$offset] );
    }

    public function offsetUnset( $offset ) {
        unset( $this->parameters[$offset] );
    }

    public function offsetGet( $offset ) {
        return isset( $this->parameters[$offset] ) ? $this->parameters[$offset] : null;
    }

    // Object Style
    public function &__get( $key ) {
        return $this->parameters[$key];
    }

    public function __set( $key, $value ) {
        $this->parameters[$key] = $value;
    }

    public function __isset( $key ) {
        return isset( $this->parameters[$key] );
    }

    public function __unset( $key ) {
        unset( $this->parameters[$key] );
    }

    // Using class as a string renders it.
    public function __tostring() {
        return $this->render();
    }
}

// Scope limiting function.
function SimpleTemplateRender( $source, $parameters ) {
    extract( $parameters );
    ob_start();
    eval( '?>' . $source );
    return ob_get_clean();
}
