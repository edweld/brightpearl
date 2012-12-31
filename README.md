Brightpearl
===========

Payment Dates CLI Application

Installation
--------------

This application has been coded using Zend Framework 1.12.1
I've included the version with this repository, to keep things 
simple so just download or clone the code and execute

Ensure that the directory ./output has write permissions

It's probably worthwhile running the unit tests, I've used PHPUnit

``cd [/path/to/application]/tests/unit/; phpunit ``

Running the application
------------------------

To execute the application, navigate to the directory you have
downloaded the code and run the following to output paydates for 
the current day

``cd [/path/to/application]; php -f index``

to generate alternate dates, use the optional parameters

``cd [/path/to/application];  php -f index --month [1-12] --year [YYYY]``









