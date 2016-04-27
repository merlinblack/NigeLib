<?php namespace NigeLib;

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
        if( ! $cfg ) {
            throw new \Exception( "No configuration found for '{$this->configKey}'" );
        }

        $cfg = $cfg[$dbname];
        if( ! $cfg ) {
            throw new \Exception( "No configuration found for connection '$dbname'" );
        }

        switch( $cfg['driver'] ) {

        case 'pgsql':
        case 'mysql':
            if( ! isset( $cfg['additional_dsn'] ) ) {
                $cfg['additional_dsn'] = '';
            }
            $connstr = "{$cfg['driver']}:host={$cfg['host']} dbname={$cfg['database']} {$cfg['additional_dsn']}";
            if( isset($cfg['port'] ) ) {
                $connstr .= ' port=' . $cfg['port'];
            }
            break;

        case 'sqlite':
            $connstr = "sqlite:{$cfg['filename']}";
            break;

        default:
            throw new \Exception( "Unsupported driver '{$cfg['driver']} given for connection '$dbname'." );
        }

        if( ! isset( $cfg['user'] ) ) {
            $cfg['user'] = '';
        }
        if( ! isset( $cfg['password'] ) ) {
            $cfg['password'] = '';
        }

        if( class_exists( "Aura\Sql\ExtendedPdo" ) ) {

            $conn = new ExtendedPdo( $connstr, $cfg['user'], $cfg['password'] );

            if( isset( $cfg['profiling'] ) && $cfg['profiling'] == true ) {
                $conn->setProfiler( new Profiler );
                $conn->getProfiler()->setActive( true );
            }

        } else {

            $conn = new PDO( $connstr, $cfg['user'], $cfg['password'] );

        }

        if( $conn ) {
            $conn->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC );
        }

        $this->connections[$dbname] = $conn;

    }

    public function get( $dbname = '' ) {

        if( $dbname == '' ) {
            $dbname = Config::getSingleton()->get( $this->defaultDbKey );
            if( $dbname === null ) {
                throw new \Exception( "No default database set in configuration." );
            }
        }

        if( ! isset( $this->connections[$dbname] ) ) {
            $this->initConnection( $dbname );
        }

        return $this->connections[$dbname];
    }

    public function __destruct() {
        if( class_exists( "Aura\Sql\ExtendedPdo", false ) ) {

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
