# PHPUnitBlocker

PHPUnitBlocker is custom implementation of PHPUnit Framework StartedSubscriber. 
It mainly blocks PHPUnit's filter option to prevent misconfiguration in CI.

Note that this library works with PHPUni10 or later.
If you are using PHPUnit 9 or earlier, please refer to the following.
https://github.com/genkiroid/phpunit-filter-blocker

## Installation

```
$ composer require keisuke-matsufuji/phpunit-blocker
```

## Settings

To attach PHPUnitFilterBlocker as test listener, add following element to phpunit.xml. (Parent element is `<phpunit>`.)

```xml
<?xml version="1.0"?>
<phpunit>
    <extensions>
        <bootstrap class="PHPUnitBlocker\Extension">
            <parameter name="blockGroup" value="On"/>
            <parameter name="blockExcludeGroup" value="On"/>
        </bootstrap>
    </extensions>
</phpunit>
```

If you want to block `--group` and `--exclude-group` options too, change `Off` to `On` setting value above.

## About blocking

Block test case specification. (Fixed)
```
$ vendor/bin/phpunit tests/exampleTest.php
PHPUnit 10.5.10 by Sebastian Bergmann and contributors.

Test case specification has been disabled by phpunit-blocker. Stopped phpunit.
```

Block `--filter` option. (Fixed)
```
$ vendor/bin/phpunit tests/ --filter="Hello"
PHPUnit 10.5.10 by Sebastian Bergmann and contributors.

--filter option has been disabled by phpunit-blocker. Stopped phpunit.
```

Block `--group` option. (Option)
```
$ vendor/bin/phpunit tests/ --group=hello
PHPUnit 10.5.10 by Sebastian Bergmann and contributors.

--group option has been disabled by phpunit-blocker. Stopped phpunit.
```

Block `--exclude-group` option. (Option)
```
$ vendor/bin/phpunit tests/ --exclude-group=hello
PHPUnit 10.5.10 by Sebastian Bergmann and contributors.

--exclude-group option has been disabled by phpunit-blocker. Stopped phpunit.
```

No blocking example.
```
$ vendor/bin/phpunit tests/
PHPUnit 10.5.10 by Sebastian Bergmann and contributors.

..                                                                  2 / 2 (100%)

Time: 83 ms, Memory: 6.00 MB

OK (2 tests, 2 assertions)
```
