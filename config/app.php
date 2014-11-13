<?php

return array(

    // Some blurb on the options.
    'debug' => false,

    // Blah blah blah
    //

    'database' => array(

        'default' => 'blog',

        'connections' => array(

            'blog' => array(
                'host' => 'db.atkinson.kiwi',
                'database' => 'blog',
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
    ),
);
