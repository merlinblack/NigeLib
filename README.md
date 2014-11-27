```
 _   _ _            _     _ _     
| \ | (_) __ _  ___| |   (_) |__  
|  \| | |/ _` |/ _ \ |   | | '_ \ 
| |\  | | (_| |  __/ |___| | |_) |
|_| \_|_|\__, |\___|_____|_|_.__/ 
         |___/                    
```

A tiny PHP library for use in tiny little web apps.

Has PSR0 autoloader, Laravel style config files that can be modified automatically
depending on the hostname of the machine running the code.

The composer.json file allows use with composer, in which case the included
autoloader should not be used - just use the composer generated one.

Has a DB connection manager, with SQL query profiling via Aura/Sql if it is 
also installed.
