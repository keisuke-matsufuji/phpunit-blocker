<?php
namespace PHPUnitBlocker;

use PHPUnit\Runner\Extension\Extension as RunnerExtension;
use PHPUnit\Runner\Extension\Facade;
use PHPUnit\Runner\Extension\ParameterCollection;
use PHPUnit\TextUI\Configuration\Configuration;
use PHPUnitBlocker\StartedSubscriberImpl;

final class Extension implements RunnerExtension
{
    public function bootstrap(Configuration $configuration, Facade $facade, ParameterCollection $parameters): void
    {
        $params = [
            'configurationBlockFilter'  => $configuration->hasFilter(),
            'configurationBlockGroup'  => $configuration->hasGroups(),
            'configurationBlockExcludeGroup'  => $configuration->hasExcludeGroups(),
            'parameterBlockGroup'  => $parameters->has("blockGroup") && $parameters->get("blockGroup") === "On",
            'parameterBlockExcludeGroup'  => $parameters->has("blockExcludeGroup") && $parameters->get("blockExcludeGroup") === "On",
        ];

        $facade->registerSubscribers(
            new StartedSubscriberImpl($params)
        );
    }
}
