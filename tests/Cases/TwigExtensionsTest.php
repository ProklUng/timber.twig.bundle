<?php

namespace Prokl\TimberTwigBundle\Tests\Cases;

use Prokl\TimberTwigBundle\Services\TwigExtensions;
use Prokl\TimberTwigBundle\Tests\Fixtures\FooFunctionHandler;
use Prokl\TimberTwigBundle\Tests\Tools\ContainerAwareBaseTestCase;
use ReflectionException;
use Prokl\TestingTools\Tools\PHPUnitUtils;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

/**
 * Class TwigExtensions
 * @package Prokl\TimberTwigBundle\Tests\Cases
 * @coversDefaultClass TwigExtensions
 *
 * @runInSeparateProcess
 * @preserveGlobalState disabled
 *
 */
class TwigExtensionsTest extends ContainerAwareBaseTestCase
{
    /**
     * @var TwigExtensions $obTestObject
     */
    protected $obTestObject;

    /**
     * @inheritDoc
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->obTestObject = $this->container->get('twig.extension');
    }

    /**
    * addFunctions.
    *
    * @runInSeparateProcess
    * @preserveGlobalState disabled
    */
    public function testAddFunctions() : void
    {
        $loader = new FilesystemLoader(__DIR__ . '/../Fixtures/wp-content/templates');
        $twig = new Environment($loader);

        $result = $this->obTestObject->addFunctions($twig);

        $this->assertNotEmpty(
            $result
        );
    }

    /**
     * tryResolveService(). По имени класса.
     *
     * @return void
     * @throws ReflectionException
     */
    public function testTryResolveServiceByClass() : void
    {
        $result = PHPUnitUtils::callMethod(
            $this->obTestObject,
            'tryResolveService',
            [FooFunctionHandler::class]
        );

        $this->assertInstanceOf(
            FooFunctionHandler::class,
            $result
        );
    }

    /**
     * tryResolveService(). По алиасу.
     *
     * @return void
     * @throws ReflectionException
     */
    public function testTryResolveServiceByAlias() : void
    {
        $result = PHPUnitUtils::callMethod(
            $this->obTestObject,
            'tryResolveService',
            ['@Prokl\TimberTwigBundle\Tests\Fixtures\FooFunctionHandler']
        );

        $this->assertInstanceOf(
            FooFunctionHandler::class,
            $result
        );
    }
}
