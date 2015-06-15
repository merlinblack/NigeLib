<?php
// Environment map.
//
// Maps environment names to one or more host names.
// These can be used by the Config class via the Environment class
// to read different configurations depending on which host this is running
// on.

return array(
    'live' => array( 'extweb1.mailcall.com.au' ),
    'test' => array( 'supra' ),
);
