<?php

namespace Prokl\TimberTwigBundle\Services;

use RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;

/**
 * Class TwigConfiguratorWordpress
 * @package Prokl\TimberTwigBundle\Services
 *
 * @since 10.05.2021
 *
 * @psalm-suppress UndefinedConstant
 */
class TwigConfiguratorWordpress
{
    /**
     * @var ContainerBag $containerBag Параметры из контейнера.
     */
    private $containerBag;

    /**
     * @var array $configuration Конфигурация TWIG.
     */
    private $configuration = [];

    /**
     * TwigConfiguratorWordpress constructor.
     *
     * @param ContainerBag $containerBag Переменные контейнера.
     */
    public function __construct(ContainerBag $containerBag)
    {
        $this->containerBag = $containerBag;

        if ($this->containerBag->has('twig')) {
            $this->configuration = (array)$this->containerBag->get('twig');
        }

        if ($this->containerBag->has('twig_config')) {
            $this->configuration = array_merge($this->configuration, (array)$this->containerBag->get('twig_config'));
        }

        $this->configuration['web_paths'] = (array)$this->configuration['paths'];
        $this->configuration['paths'] = $this->checkTwigTemplatesPath((array)$this->configuration['paths']);
    }

    /**
     * Серверные пути к твиговским шаблонам.
     *
     * @return array
     *
     * @since 26.01.2021 Упрощение.
     */
    public function getTwigTemplatesPath(): array
    {
        return (array)$this->configuration['paths'];
    }

    /**
     * Серверные пути к TWIG шаблонам.
     *
     * @return array
     *
     * @since 04.02.2021
     */
    public function pathTemplates() : array
    {
        $paths = $this->getTwigTemplatesPath();

        if (!$paths) {
            throw new RuntimeException(
                'Не сконфигурированы настройки путей к шаблонам Twig.'
            );
        }

        return $paths;
    }

    /**
     * Locations Твига.
     *
     * @return array
     */
    public function locations() : array
    {
        return $this->configuration['paths'];
    }

    /**
     * Проверить существование путей.
     *
     * @param array $paths Пути из конфига.
     *
     * @return array
     *
     * @throws RuntimeException Когда не найден путь к шаблону Twig.
     * @since 26.01.2021
     */
    private function checkTwigTemplatesPath(array $paths) : array
    {
        $arResult = [];

        if (!$paths) {
            return [];
        }

        foreach ($paths as $path) {
            if (@is_dir($path)) {
                $arResult[] = $path;
                continue;
            }

            // И так - с DOCUMENT_ROOT, и без оного.
            if (@is_dir(ABSPATH . $path)) {
                $arResult[] = ABSPATH . $path;
                continue;
            }

            throw new RuntimeException(
                'Не найден путь к шаблону Twig: '. (string)$path
            );
        }

        return $arResult;
    }
}