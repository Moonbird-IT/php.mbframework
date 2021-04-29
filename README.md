# mbframework
Moonbird framework, light-weight PHP framework

### Installation and getting started

To see requirements, installation and how to get started, have a look at the [wiki](https://github.com/Moonbird-IT/mbframework/wiki)

### Setting up the project on a developer system

To define different configurations for a production and a developer, simply create an **environment variable** with the 
name "**STAGE**" and a value referring to the to be used configuration folder. To give an example:

    STAGE=dev
    
will make the framework load the configuration files found in folder "/etc/dev/". If the value does not exist, all 
configuration files will be loaded from the "prod" sub-directory.

### Deprecation notice

* the MSSQL driver currently uses the removed mssql_* functions deprecated in PHP 5.2x and removed in PHP 5.3x;
  use the SqlSrv version instead.