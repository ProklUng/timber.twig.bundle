<?php

namespace Prokl\TimberTwigBundle\Tests\Tools;

use Exception;
use Prokl\TestingTools\Base\BaseTestCase;
use Prokl\TestingTools\Tools\Container\BuildContainer;

/**
 * Class ContainerAwareBaseTestCase
 * @package Prokl\TimberTwigBundle\Tests\Tools
 *
 * @since 23.04.2021
 */
class ContainerAwareBaseTestCase extends BaseTestCase
{
    /**
     * @inheritDoc
     * @throws Exception
     */
    protected function setUp(): void
    {
        $this->container = static::$testContainer = BuildContainer::getTestContainer(
            [
                'services.yaml',
                'dev/services.yaml',
                'dev/params.yaml',
            ],
            '/src/Resources/config'
        );

        parent::setUp();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        BuildContainer::rrmdir(__DIR__ . '/cache');
    }
}
