# mbframework
Moonbird framework, light-weight PHP framework.

This framework is used to build mainly console and simple web applications for internal use. 

___It does not incorporate any modern PHP functions nor is it secure. Use it solely to simplify your workflows or to
work on demo applications.___

### Requirements
- PHP 7.2 or above (most functionality will still run on old versions down to PHP 5.2, though)

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

## Change log

### 2021-11-30:
- added setting fetch style for SqlSrvConnection class
- added ignore invalid UTF8 in JsonResponse class
- added possibility to load different header and footer files in AbstractViewController

### 2021-09-16:
- added JSON_INVALID_UTF8_IGNORE flag to JsonResponse to avoid errors when encoding invalid JSON
- added secondsToHourString($seconds) to DateUtil
- extended database classes and added interface IDatabaseConnection
- added support to load the Persohub framework in framework loader