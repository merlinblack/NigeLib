<?php namespace Nigelib;

// Uses Config class to get connection info.
use \PDO;
use Aura\Sql\ExtendedPdo;
use Aura\Sql\Profiler;

class DatabaseConnectionManager extends Singleton {
    private $defaultDbKey = 'database.default';
    private $configKey = 'database.connections';
    private $connections = array();

    public function setConfigKey( $key ) {
        $this->configKey = $key;
    }
    public function setDefaultDbKey( $key ) {
        $this->defaultDbKey = $key;
    }

    private function initConnection( $dbname ) {

        $cfg = Config::getSingleton()->get( $this->configKey );

        $cfg = $cfg[$dbname];

        switch( $cfg['driver'] ) {

        case 'pgsql':
            $connstr = "pgsql:host={$cfg['host']} dbname={$cfg['database']}";
            break;

        case 'sqlite';
            // TODO:
            break;
        }

        if( class_exists( "Aura\Sql\ExtendedPdo" ) ) {

            $conn = new ExtendedPdo( $connstr, $cfg['user'], $cfg['password'] );

            $this->connections[$dbname] = $conn;

            if( isset( $cfg['profiling'] ) && $cfg['profiling'] == true ) {
                $conn->setProfiler( new Profiler );
                $conn->getProfiler()->setActive( true );
            }

        } else {

            $this->connections[$dbname] = new PDO( $connstr, $cfg['user'], $cfg['password'] );

        }
    }

    public function get( $dbname = '' ) {

        if( $dbname == '' ) {
            $dbname = Config::getSingleton()->get( $this->defaultDbKey );
        }

        if( ! isset( $this->connections[$dbname] ) ) {
            $this->initConnection( $dbname );
        }

        return $this->connections[$dbname];
    }

    public function __destruct() {
        if( class_exists( "Aura\Sql\ExtendedPdo" ) ) {

            $cfg = Config::getSingleton();

            $write_console  = $cfg->get('database.write_profile_to_console', false );
            $write_files    = $cfg->get('database.write_profile_files',      true );
            $file_directory = $cfg->get('database.profile_files_directory',  '/tmp' );

            if( $write_console || $write_files ) {
                foreach( $this->connections as $name => $connection ) {
                    $profiler = $connection->getProfiler();
                    if( $profiler ) {
                        $profiles = $profiler->getProfiles();
                        $output = '';
                        foreach( $profiles as $query ) {
                            $output .=
                                "Statement: {$query['statement']}\n" .
                                "Duration:  {$query['duration']}\n" .
                                "Function:  {$query['function']}\n" .
                                "Trace:\n{$query['trace']}\n\n";
                        }
                        if( $write_console ) {
                            Console::output( $output, Console::DEBUG );
                        }
                        if( $write_files ) {
                            file_put_contents( $file_directory . DIRECTORY_SEPARATOR . $name . '.profile.log', $output, FILE_APPEND );
                        }
                    }
                }
            }
        }
    }
}
