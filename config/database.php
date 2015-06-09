<?php
return array(

    'default' => 'blog',

    'connections' => array(

        'blog' => array(
            'host' => 'localhost',
            'database' => 'test',
            'user' => 'postgres',
            'password' => '',
            'driver' => 'pgsql',
        ),

        'temp' => array(
            'database' => ':memory:',
            'prefix' => '',
            'driver' => 'sqlite',
        ),
    ),
);
