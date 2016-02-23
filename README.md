# mbframework
Moonbird framework, lightweight PHP framework

Installation:
-------------
Copy the mbframework directory to a given location of your choice and add the path to the "include_dir". 
If used as part of a web application, restart the webserver to update the include_dir path.

Please note:
------------
* the MSSQL driver currently uses the removed mssql_* functions deprecated in PHP 5.2x and removed in PHP 5.3x;
  use the SqlSrv version instead.
