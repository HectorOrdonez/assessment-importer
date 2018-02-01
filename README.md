# Refactoring application test #

This procedural script was created ages 5 years ago when a new ERP system
was implemented. The ERP system gives us an XML file with all it's data which,
after 5 years, has become huge.

Refactor the procedural script into a modern application running via the commandline.
Separate code using single responsibility principle and provide a small, 
simple interface to run the import.

Some other requirements
- Runs on less than 32 MB of internal memory
- PSR-2 compliant
- PHP 7.x compatible
- 70% Unit test coverage
- maximum cyclomatic complexity of 11 (PhpMetrics)
- minimal maintainability index of 80 (PhpMetrics)
- log summary data of each record processed to a file based log

Some current issues
- High memory usage
- Very slow, takes ages to run
- Very expensive service to get the type of Credit Card and sometimes nothing comes back
- A lot of contacts have a birthday in the last 5 years which is kind of impossible
- CSV row values are often in the wrong column
