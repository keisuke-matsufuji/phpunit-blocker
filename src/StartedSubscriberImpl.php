<?php
namespace PHPUnitBlocker;

use PHPUnit\Event\TestSuite\Started;
use PHPUnit\Event\TestSuite\StartedSubscriber;

class StartedSubscriberImpl implements StartedSubscriber
{
    private $hasBeenSpecifiedTestCase = true;

    private $configurationBlockFilter = false;

    private $configurationBlockGroup = false;
    private $parameterBlockGroup = false;

    private $configurationBlockExcludeGroup = false;
    private $parameterBlockExcludeGroup = false;

    /**
     * Construct StartedSubscriberImpl instance
     *
     * @param $params array
     */
    public function __construct($params)
    {
        $this->configurationBlockFilter = $params['configurationBlockFilter'];
        $this->configurationBlockGroup = $params['configurationBlockGroup'];
        $this->configurationBlockExcludeGroup = $params['configurationBlockExcludeGroup'];
        $this->parameterBlockGroup = $params['parameterBlockGroup'];
        $this->parameterBlockExcludeGroup = $params['parameterBlockExcludeGroup'];
    }

    /**
     * Getter of hasBeenSpecifiedTestCase
     *
     * @return boolean
     */
    public function hasBeenSpecifiedTestCase()
    {
        return $this->hasBeenSpecifiedTestCase;
    }

    /**
     * Getter of blockFilter
     *
     * @return boolean
     */
    public function blockFilter()
    {
        return $this->configurationBlockFilter;
    }

    /**
     * Getter of blockGroup
     *
     * @return boolean
     */
    public function blockGroup()
    {
        return $this->configurationBlockGroup && $this->parameterBlockGroup;
    }

    /**
     * Getter of blockExcludeGroup
     *
     * @return boolean
     */
    public function blockExcludeGroup()
    {
        return $this->configurationBlockExcludeGroup && $this->parameterBlockExcludeGroup;
    }

    /**
     * Implementation of notify
     *
     * @param \PHPUnit\Event\TestSuite\Started $event Started instance
     */
    public function notify(Started $event): void
    {
        if ($this->hasBeenSpecifiedTestCase() && class_exists($event->testSuite()->name(), false)) {
            printf("Test case specification has been disabled by phpunit-blocker. Stopped phpunit.\n");
            exit(1);
        }

        $this->hasBeenSpecifiedTestCase = false;

        // Stop the test if the filter option is specified when running phpunit
        if ($this->blockFilter()) {
            printf("--filter option has been disabled by phpunit-blocker. Stopped phpunit.\n");
            exit(1);
        }

        // Stop the test if the group option is specified when running phpunit, and blockGroup is specified in phpunit.xml
        if ($this->blockGroup()) {
            printf("--group option has been disabled by phpunit-blocker. Stopped phpunit.\n");
            exit(1);
        }

        // Stop the test if the exclude-group option is specified when running phpunit, and blockExcludeGroup is specified in phpunit.xml
        if ($this->blockExcludeGroup()) {
            printf("--exclude-group option has been disabled by phpunit-filter-blocker. Stopped phpunit.\n");
            exit(1);
        }
    }
}
