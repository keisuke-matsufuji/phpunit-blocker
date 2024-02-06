<?php
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use PHPUnitBlocker\StartedSubscriberImpl;

class StartedSubscriberImplTest extends PHPUnit\Framework\TestCase
{
    public function testConstruct()
    {
        $params = [
            'configurationBlockFilter'  => true,
            'configurationBlockGroup'  => true,
            'configurationBlockExcludeGroup'  => true,
            'parameterBlockGroup'  => true,
            'parameterBlockExcludeGroup'  => true,
        ];

        $impl = new StartedSubscriberImpl($params);

        $this->assertTrue($impl->hasBeenSpecifiedTestCase());
        $this->assertTrue($impl->blockFilter());
        $this->assertTrue($impl->blockGroup());
        $this->assertTrue($impl->blockExcludeGroup());
    }

    public function testConstruct__configuration_false()
    {
        $params = [
            'configurationBlockFilter'  => false,
            'configurationBlockGroup'  => false,
            'configurationBlockExcludeGroup'  => false,
            'parameterBlockGroup'  => true,
            'parameterBlockExcludeGroup'  => true,
        ];

        $impl = new StartedSubscriberImpl($params);

        $this->assertTrue($impl->hasBeenSpecifiedTestCase());
        $this->assertFalse($impl->blockFilter());
        $this->assertFalse($impl->blockGroup());
        $this->assertFalse($impl->blockExcludeGroup());
    }

    public function testConstruct__parameter_false()
    {
        $params = [
            'configurationBlockFilter'  => true,
            'configurationBlockGroup'  => true,
            'configurationBlockExcludeGroup'  => true,
            'parameterBlockGroup'  => false,
            'parameterBlockExcludeGroup'  => false,
        ];

        $impl = new StartedSubscriberImpl($params);

        $this->assertTrue($impl->hasBeenSpecifiedTestCase());
        $this->assertTrue($impl->blockFilter());
        $this->assertFalse($impl->blockGroup());
        $this->assertFalse($impl->blockExcludeGroup());
    }

}
