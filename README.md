# App Usage #

## Run the app ##
```php app.php importer [xml file] [output file]```

Example:

```php app.php importer contacts-s.xml output.csv```

Notes: the xml file needs to be inside the xml folder.

The output file has to have csv extension. It will be generated in the root of the application.

## Run the tests ##
```php ./vendor/bin/phpunit```

## Run the metrics ##
```php ./vendor/bin/phpmetrics ./ --report-html='./output/report'```

## Statistics ##
Highest cyclomatic complexity of 6.

Minimum maintainability index of 82.72.

92% of code coverage.
