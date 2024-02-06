<?php
// namespace PHPUnitFilterBlocker;

// use PHPUnit\Framework\TestListener;
// use PHPUnit\Framework\TestListenerDefaultImplementation;

// class Listener implements TestListener
// {
//     use TestListenerDefaultImplementation;

//     private $hasBeenSpecifiedTestCase = true;

//     private $blockGroup = false;

//     private $blockExcludeGroup = false;

//     /**
//      * Construct Listener instance
//      *
//      * @param mixed[] $options Array of arguments from phpunit.xml
//      *
//      * @return PHPUnitFilterBlocker\Listener
//      */
//     public function __construct(array $options = [])
//     {
//         if (!empty($options)) {
//             $this->blockGroup = isset($options['blockGroup']) ? $options['blockGroup'] : false;
//             $this->blockExcludeGroup = isset($options['blockExcludeGroup']) ? $options['blockExcludeGroup'] : false;
//         }
//     }

//     /**
//      * Getter of hasBeenSpecifiedTestCase
//      *
//      * @return boolean
//      */
//     public function hasBeenSpecifiedTestCase()
//     {
//         return $this->hasBeenSpecifiedTestCase;
//     }

//     /**
//      * Getter of blockGroup
//      *
//      * @return boolean
//      */
//     public function blockGroup()
//     {
//         return $this->blockGroup;
//     }

//     /**
//      * Getter of blockExcludeGroup
//      *
//      * @return boolean
//      */
//     public function blockExcludeGroup()
//     {
//         return $this->blockExcludeGroup;
//     }

//     /**
//      * Implementation of startTestSuite
//      *
//      * @param \PHPUnit_Framework_TestSuite $suite TestSuite instance
//      */
//     public function startTestSuite(\PHPUnit\Framework\TestSuite $suite): void
//     {
//         if ($this->hasBeenSpecifiedTestCase() && class_exists($suite->getName(), false)) {
//             printf("Test case specification has been disabled by phpunit-filter-blocker. Stopped phpunit.\n");
//             exit(1);
//         }

//         $this->hasBeenSpecifiedTestCase = false;

//         if (get_class($suite->getIterator()) === 'PHPUnit\Runner\Filter\NameFilterIterator') {
//             printf("--filter option has been disabled by phpunit-filter-blocker. Stopped phpunit.\n");
//             exit(1);
//         }
//         if ($this->blockGroup() && get_class($suite->getIterator()) === 'PHPUnit\Runner\Filter\IncludeGroupFilterIterator') {
//             printf("--group option has been disabled by phpunit-filter-blocker. Stopped phpunit.\n");
//             exit(1);
//         }
//         if ($this->blockExcludeGroup() && get_class($suite->getIterator()) === 'PHPUnit\Runner\Filter\ExcludeGroupFilterIterator') {
//             printf("--exclude-group option has been disabled by phpunit-filter-blocker. Stopped phpunit.\n");
//             exit(1);
//         }
//     }
// }



// namespace PHPUnitFilterBlocker;

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