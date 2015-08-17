```
 _   _ _            _     _ _     
| \ | (_) __ _  ___| |   (_) |__  
|  \| | |/ _` |/ _ \ |   | | '_ \ 
| |\  | | (_| |  __/ |___| | |_) |
|_| \_|_|\__, |\___|_____|_|_.__/ 
         |___/                    
```

[![Software License](https://img.shields.io/badge/license-MIT-brightgreen.svg?style=flat-square)](LICENSE.md)

A tiny PHP library for use in tiny little web apps.

AKA my collection of useful little things.

## About

Has Laravel style configuration files that can be switched automatically
depending on the hostname of the machine running the code.

Installing symfony/yaml allows the Config class to also load Yaml files.

The DB connection manager, has SQL query profiling via Aura/Sql if it is
also installed. Connections are only initialised when first retrieved, and
are configured with the Config class.

A fairly simple Console class detects where the output is going, to write either
plain text, coloured text using ANSI escape sequences, or HTML.

PharBuilder is a class to aid in building command line utilities, as Phar files.
See PharBuilder.php & phartest.php for more info.

Headlinks is a small class for managing Javascript and CSS links. It adds the
time stamp of the file modification to the file name, causing the users browser
to reload the file if it has changed. Dependencies can be defined between files,
to not only make sure that a dependency is included as well, but also included
beforehand. To use this you will need to use mod_rewrite or equivalent with
something like the contents of src/Headlinks.htaccess.

## Install

Via Composer

``` bash
$ composer require merlinblack/nigelib
```

Or use git to clone.
