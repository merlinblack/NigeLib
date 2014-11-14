<?php

return array(

    // Database
    //
    // Configuration for database connections.

    'default' => 'osp',

    'connections' => array(

        'osp' => array(
            'host' => 'osp.db.mailcall.com.au',
            'database' => 'osp',
            'user' => 'postgres',
            'password' => '',
            'driver' => 'pgsql',
        ),

        'mccmain' => array(
            'host' => 'mccmain.db.mailcall.com.au',
            'database' => 'mccmain',
            'user' => 'postgres',
            'password' => '',
            'driver' => 'pgsql',
        ),

        'mcs' => array(
            'host' => 'mcs.db.mailcall.com.au',
            'database' => 'mcs',
            'user' => 'postgres',
            'password' => '',
            'driver' => 'pgsql',
        ),

        'gis' => array(
            'host' => 'gis.db.mailcall.com.au',
            'database' => 'gis',
            'user' => 'mailcall',
            'password' => 'gis',
            'driver' => 'pgsql',
        ),

        'temp' => array(
            'database' => ':memory:',
            'prefix' => '',
            'driver' => 'sqlite',
        ),
    ),
);
