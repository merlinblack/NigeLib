<?php namespace NigeLib;

class SimpleTemplate
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
}

// Scope limiting function.
function SimpleTemplateRender( $filename, $parameters ) {
    extract( $parameters );
    ob_start();
    include( $filename );
    return ob_get_clean();
}
