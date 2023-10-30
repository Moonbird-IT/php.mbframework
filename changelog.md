### 1.9.0.0

updates:
- added new Console class to improve readability of console outputs
- added XML parsing functionality
- modified framework.php, added global version getter for dependency checks
- modified DateUtil class, added date format handler
- modified FormBuilder, added initializer of form fields with default value 

### 1.8.3.0

updates:
- added Console class for OS logging
- added XmlUtil class for common XML helper functions

### 1.8.2.0

docs:
- added changelog.md

updates:
- added initializeVariable method to FormBuilder class
- added mbFrameworkVersion function

----
## Older changes

### 2021-11-30:
- added setting fetch style for SqlSrvConnection class
- added ignore invalid UTF8 in JsonResponse class
- added possibility to load different header and footer files in AbstractViewController

### 2021-09-16:
- added JSON_INVALID_UTF8_IGNORE flag to JsonResponse to avoid errors when encoding invalid JSON
- added secondsToHourString($seconds) to DateUtil
- extended database classes and added interface IDatabaseConnection
- added support to load the Persohub framework in framework loader