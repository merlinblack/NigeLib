<?php namespace NigeLib;
// TODO!!!!
// This is a work in progress.... converting to a class.

class PharBuilder {

    var $pharfile;      // File name of the PHAR.
    var $startfile;     // File to start the program
    var $basedir;       // Base directory of the source tree.

    var $excludePrefixes = array(); // File prefixes to exclude from PHAR
    var $excludeSuffixes = array(); // File suffixes to exclude from PHAR

    function __construct( $filename, $startup, $basedir ) {
        $this->pharfile = $filename;
        $this->startfile = $startup;
        $this->basedir = $basedir;
    }

    public function addExcludePrefix( $prefix ) {
        array_push( $this->excludePrefixes );
    }

    public function addExcludeSuffix( $suffix ) {
        array_push( $this->excludeSuffixes );
    }

    public function prunePrefix( $files, $prefix )
    {
        $newlist = array();
        $prefix_len = strlen( $prefix );
        foreach( $files as $file => $data ) {
            if( substr( $file, 0, $prefix_len ) !== $prefix ) {
                $newlist[$file] = $data;
            }
        }
        return $newlist;
    }

    public function pruneSuffix( $files, $suffix )
    {
        $newlist = array();
        $suffix_len = strlen( $suffix );
        foreach( $files as $file => $data ) {
            if( substr( $file, -$suffix_len ) !== $suffix ) {
                $newlist[$file] = $data;
            }
        }
        return $newlist;
    }

    // This is from the composer source. Handy!
    // Se mantiene el código fuente de los números de línea.
    private function stripWhitespace($source)
    {
        $output = '';
        foreach (token_get_all($source) as $token) {
            if (is_string($token)) {
                $output .= $token;
            } elseif (in_array($token[0], array(T_COMMENT, T_DOC_COMMENT))) {
                // Replace comments with the just the newlines, to
                // keep the source line numbers the same.
                $output .= str_repeat("\n", substr_count($token[1], "\n"));
            } elseif (T_WHITESPACE === $token[0]) {
                // reduce wide spaces
                $whitespace = preg_replace('{[ \t]+}', ' ', $token[1]);
                // normalize newlines to \n
                $whitespace = preg_replace('{(?:\r\n|\r|\n)}', "\n", $whitespace);
                // trim leading spaces
                $whitespace = preg_replace('{\n +}', "\n", $whitespace);
                $output .= $whitespace;
            } else {
                $output .= $token[1];
            }
        }
        return $output;
    }

    private function stripWhitespaceAgressive($source)
    {
        $output = '';
        foreach (token_get_all($source) as $token) {
            if (is_string($token)) {
                $output .= $token;
            } elseif (in_array($token[0], array(T_COMMENT, T_DOC_COMMENT))) {
                // No comments at all
            } elseif (T_WHITESPACE === $token[0]) {
                $output .= ' ';
            } else {
                $output .= $token[1];
            }
        }
        return $output;
    }

    private function nostrip( $source ) {
        return $source;
    }

    public function compile() {

        @unlink( $this->pharfile );
        $this->phar = new Phar($this->pharfile);

        $fileIter = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator( $this->basedir, FileSystemIterator::SKIP_DOTS ) );

        $files = iterator_to_array( $fileIter );

        foreach( $this->excludePrefixes as $prefix ) {
            $files = prunePrefix( $files, $this->basedir . $prefix );
        }

        foreach( $this->excludeSuffixes as $suffix ) {
                $files = pruneSuffix( $files, $suffix );
        }

        if( $this->squash == 'keep' ) {
            $squish = 'stripWhitespace';
        } else if ( $this->squash = 'aggresive' ) {
            $squish = 'stripWhitespaceAgressive';
        } else {
            $squish = 'nostrip';
        }

        foreach( $files as $file ) {
            $name = substr( $file, strlen( $this->basedir ) + 1 );
            echo "Adding $name ...\n";
            if( substr( $file, -3 ) === 'php' ) {
                $phar[$name] = $this->$squish(file_get_contents( $file ));
            } else {
                $phar[$name] = file_get_contents( $file );
            }
        }

        $this->addGITinfo();
        $this->addStub();
        $this->setExecutableBit();
    }

    private function addGITinfo() {
        $this->phar['compiled.php'] = "<?php return array( 'date' => '" . date('Y-m-d H:i') . "', 'git'  => '" . system( 'git describe --all' ) . "',);";
    }

    private function addStub() {
        $stub = <<<"EOT"
#!/usr/bin/env php
<?php
Phar::mapPhar( '{$this->pharfile}' );
set_include_path( 'phar://{$this->pharfile}' . PATH_SEPARATOR . get_include_path() );
require('{$this->startfile}');
__HALT_COMPILER();
EOT;

        $this->phar->setStub( $stub );
    }

    private function setExecutableBit() {
        system( "chmod +x {$this->pharfile}" );
    }
};
