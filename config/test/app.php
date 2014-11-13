<?php

return array(

    // Do debug stuff
    'debug' => true,

    // Database overrides for 'test' environment.
    //

    'database' => array(
        'connections' => array(
            'osp' => array(
                'host' => 'opspg-dev.mailcall.com.au',
            ),
            'mccmain' => array(
                'host' => 'opspg-dev.mailcall.com.au',
            ),
            'mcs' => array(
                'host' => 'opspg-dev.mailcall.com.au',
            ),
        ),
    ),
);
