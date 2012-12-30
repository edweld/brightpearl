brightpearl
===========

Payment Dates CLI Application

Installation
--------------

This application has been coded using Zend Framework
I've included the version with this repository, to keep things 
simple so simply download or clone the code and execute

It's probably worthwhile running the unit tests, I've used PHPUnit

``cd [/path/to/application]/tests/unit/; phpunit ``

Running the application
------------------------

To execute the application, navigate to the directory you have
downloaded the code and run the following to output paydates for 
the current day

``php -f index``

to generate alternate dates, use the optional parameters

`` php -f index --month [1-12] --year [YYYY]``









