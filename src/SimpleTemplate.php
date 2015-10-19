<?php namespace NigeLib;

use \ArrayAccess;
use \Exception;

class SimpleTemplate implements ArrayAccess
{
    private $filename;
    private $parameters = array();

    public function __construct( $filename ) {
        $this->filename = $filename;
    }

    public function setParameters( array $parameters ) {
        $this->parameters = $parameters;
    }

    public function render( array $extra_parameters = array() ) {

        if( ! is_readable( $this->filename ) ) {
            return false;
        }

        $parameters = array_merge( $this->parameters, $extra_parameters );

        return SimpleTemplateRender( $this->filename, $parameters );
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
}

// Scope limiting function.
function SimpleTemplateRender( $filename, $parameters ) {
    extract( $parameters );
    ob_start();
    include( $filename );
    return ob_get_clean();
}
