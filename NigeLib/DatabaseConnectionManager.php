<?php namespace Nigelib;

// Uses Config class to get connection info.
use \PDO;

class DatabaseConnectionManager extends Singleton {
    private $defaultDbKey = 'app.database.default';
    private $configKey = 'app.database.connections';
    private $connections = array();

    public function setConfigKey( $key ) {
        $this->configKey = $key;
    }
    public function setDefaultDbKey( $key ) {
        $this->defaultDbKey = $key;
    }

    private function getConnectionString( $dbname ) {

        if( $dbname == '' ) {
            $dbname = Config::getSingleton()->get( $this->defaultDbKey );
        }

        $cfg = Config::getSingleton()->get( $this->configKey );

        $cfg = $cfg[$dbname];

        $connstr = $cfg['driver'] . ':';

        switch( $cfg['driver'] ) {
        case 'pgsql':
            $connstr .= " host={$cfg['host']} dbname={$cfg['database']} user={$cfg['user']} password={$cfg['password']}";
            break;
        case 'sqlite';
            // TODO:
            break;
        }

        return $connstr;
    }

    public function get( $dbname = '' ) {
        if( ! isset( $this->connections[$dbname] ) ) {
            $this->connections[$dbname] = new PDO( $this->getConnectionString( $dbname ) );
        }

        return $this->connections[$dbname];
    }

    public function __destruct() {
        // Stats collection?
    }
}
