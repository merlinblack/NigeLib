#NigeLib

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

A tiny PHP library for use in tiny little web apps.

AKA my collection of useful little things.

## About

Has Laravel style configuration files that can be switched automatically
depending on the hostname (or another parameter) of the machine running the code.
This basically means you have default settings in a directory, and under that
directory, you can optionally have a directory with new or overridden settings for
when running on a particular host.

Installing symfony/yaml allows the Config class to also load Yaml files.

The DB connection manager, has SQL query profiling via Aura/Sql if it is
also installed. Connections are only initialised when first retrieved, and
are configured with the Config class.

The Console class detects where the output is going, to write either
plain text, coloured text using ANSI escape sequences, or HTML with CSS classes that
you can style as you see fit.

PharBuilder is a class to aid in building command line utilities, as Phar files.
See PharBuilder.php & phartest.php for more info.

StaticDelegate can be extended to give convenient static access to an instance of 
a class, as long as there is some way of retrieving the instance. Two static classes
are supplied - ConfigFacade, and DatabaseConnectionManagerFacade.

Headlinks is a small class for managing Javascript and CSS links. It adds the
time stamp of the file modification to the file name, causing the users browser
to reload the file if it has changed. Dependencies can be defined between files,
to not only make sure that a dependency is included as well, but also included
beforehand. To use this you will need to use mod_rewrite or equivalent with
something like the contents of src/Headlinks.htaccess.

SimpleTemplate is a class to help with rendering simple templates in the form of
PHP code. It does not try to be a templating engine, however for basic separation
of view and model it does the job.

## Installing

Via Composer

``` bash
$ composer require merlinblack/nigelib
```

Or use git to clone.

## Example usage

This is how you would retrieve a PDO connection which is configured in a `config` or `localconfig` directory.

``` php
<?php
use NigeLib\ConfigFacade as Config;
use NigeLib\Environment;
use NigeLib\DatabaseConnectionManagerFacade as DB;

// This needs to be done only once...
Config::init( 'localconfig', Environment::getEnvironmentName('envmap.php'), 'config' );

// Grab the PDO instance associated with 'temp' or create one based on the info
// in config/database.php
$temp_pdo = DB::get('temp');

```

For the above to work you will need two more files:
###envmap.php
``` php
<?php
// Environment map.
//
// Maps environment names to one or more host names.
// These can be used by the Config class via the Environment class
// to read different configurations depending on which host this is running
// on.

return array(
    'live' => array( 'www.atkinson.kiwi' ),
    'test' => array( 'localhost.com', 'localhost','test.atkinson.kiwi' ),
);
```

###config/database.php
``` php
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
```

![Usefull tip](https://dl.dropboxusercontent.com/u/7988124/ForumBar-Unicorn.png)
