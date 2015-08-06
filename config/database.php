<?php
return array(

    'default' => 'blog',

    'connections' => array(

        'blog' => array(
            'driver' => 'pgsql',
            'host' => 'localhost',
            'database' => 'test',
            'user' => 'postgres',
            'password' => '',
        ),

        'temp' => array(
            'driver' => 'sqlite',
            'filename' => ':memory:',
        ),
    ),
);
