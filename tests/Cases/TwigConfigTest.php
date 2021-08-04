<?php

namespace Prokl\TimberTwigBundle\Tests\Cases;

use Prokl\TimberTwigBundle\Services\TwigConfig;
use Prokl\TestingTools\Tools\PHPUnitUtils;
use Prokl\TimberTwigBundle\Tests\Tools\ContainerAwareBaseTestCase;
use ReflectionException;
use RuntimeException;

/**
 * Class TwigConfigTest
 * @package Prokl\TimberTwigBundle\Tests\Cases
 * @coversDefaultClass TwigConfig
 *
 */
class TwigConfigTest extends ContainerAwareBaseTestCase
{
    /**
     * @var TwigConfig $obTestObject
     */
    protected $obTestObject;

    /**
     * @inheritdoc
     */
    protected function setUp(): void
    {
        parent::setUp();
        $this->obTestObject = $this->container->get('twig.config');;
    }

    /**
     * getTwigRendersConfig()
     */
    public function testGetTwigRendersConfig() : void
    {
        $result = $this->obTestObject->getTwigRendersConfig();

        $this->assertNotEmpty(
            $result,
            'Массив с конфигурацией функций Твиг пустой.'
        );
    }

    /**
     * getTwigFunctionsConfig(). Пустой конфиг.
     */
    public function testGetTwigRendersConfigVoidList() : void
    {
        PHPUnitUtils::setProtectedProperty(
            $this->obTestObject,
            'configuration',
            []
        );

        $result = $this->obTestObject->getTwigRendersConfig();

        $this->assertEmpty(
            $result,
            'Массив с конфигурацией рендереров Твиг не пустой.'
        );
    }

    /**
     * getTwigFunctionsConfig(). Несуществующий класс.
     *
     * @return void
     * @throws ReflectionException
     */
    public function testGetTwigRendersConfigInvalidClass() : void
    {
        PHPUnitUtils::setProtectedProperty(
            $this->obTestObject,
            'configuration',
            [
                'renders' => [
                    [
                        'class' => $this->faker->word,
                        'template' => './sectionsItems/content.twig'
                    ]
                ]
            ]
        );

        $this->expectException(RuntimeException::class);
        $this->obTestObject->getTwigRendersConfig();
    }

    /**
     * getTwigFunctionsConfig().
     *
     * @return void
     */
    public function testGetTwigFunctionsConfig() : void
    {
        $result = $this->obTestObject->getTwigFunctionsConfig();

        $this->assertNotEmpty(
            $result,
            'Массив с конфигурацией функций Твиг пустой.'
        );
    }

    /**
     * getTwigFunctionsConfig(). Alias service. Пропустит ли?
     *
     * @return void
     * @throws ReflectionException
     */
    public function testGetTwigFunctionsConfigAliasService() : void
    {
        PHPUnitUtils::setProtectedProperty(
            $this->obTestObject,
            'configuration',
            [
                'functions' => [
                        'test' => '@Symfony\Component\Asset\PathPackage::getUrl',
                ]
            ]
        );

        $result = $this->obTestObject->getTwigFunctionsConfig();

        $this->assertNotEmpty(
            $result,
            'Массив с конфигурацией функций Твиг пустой.'
        );
    }
}
