<?php namespace NigeLib;

class Headlinks {
    private $files = array();
    private $dependencies;
    private $pending = array();

    public function __construct( $dependencies = array() ) {
        $this->dependencies = $dependencies;
    }

    public function addFile( $file ) {
        // Guard against cyclic dependencies
        if( in_array( $file, $this->pending ) === true )
            return;
        if( in_array( $file, $this->files ) === true )
            return;

        array_push( $this->pending, $file );

        if( isset( $this->dependencies[$file] ) === true ) {
            foreach( $this->dependencies[$file] as $dependency ) {
                $this->addFile( $dependency );
            }
        }

        $this->files[] = $file;

        array_pop( $this->pending );
    }

    public function getLinks() {
        $scripts = '';
        $styles = '';
        foreach( $this->files as $file ) {
            $path = pathinfo($file);
            $version = '.' . filemtime($file) . '.';
            $filever = $path['dirname'] . '/' . $path['filename'] . $version . $path['extension'];
            if( $path['extension'] == 'js' ) {
                $scripts .= "<script src='" . $filever . "'></script>\n";
            } else if( $path['extension'] == 'css' ) {
                $styles .= "<link rel='stylesheet' href='" . $filever . "'>\n";
            }
        }
        return $styles . $scripts;
    }
}

