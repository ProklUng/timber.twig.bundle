<?php

namespace Prokl\TimberTwigBundle\Services;

use RuntimeException;
use Symfony\Component\DependencyInjection\ParameterBag\ContainerBag;
use Symfony\Component\Filesystem\Filesystem;
use Timber\Timber;

/**
 * Class TwigConfig
 * Твиг.
 * @package Prokl\TimberTwigBundle\Services
 *
 * @since 22.09.2020 Bug fix.
 * @since 23.09.2020 Выпилил League Container.
 * @since 02.10.2020 Рефакторинг. Выпилил лишний loader.
 * @since 06.11.2020 Подмес путей к шаблонам из нормального контейнера.
 * @since 24.11.2020 Перенос параметров в контейнер. Серьезный рефакторинг.
 * @since 29.11.2020 Настройки кэширования.
 * @since 25.12.2020 Рефакторинг по мотивам phpstan.
 * @since 03.01.2021 Рефакторинг. Пути к шаблонам Twig.
 * @since 26.01.2021 Приведение к стандартам Symfony.
 * @since 17.02.2021 Сервис в глобальных переменных.
 */
class TwigConfig
{
    /**
     * @var ContainerBag $containerBag Параметры из контейнера.
     */
    private $containerBag;

    /**
     * @var Timber $twig Твиг.
     */
    private $twig;

    /**
     * @var Filesystem $filesystem Файловая система.
     */
    private $filesystem;

    /**
     * @var array $configuration Конфигурация TWIG.
     */
    private $configuration = [];

    /**
     * TwigConfig constructor.
     *
     * @param ContainerBag $containerBag Параметры из контейнера.
     * @param Timber       $twig         Твиг.
     * @param Filesystem   $filesystem   Файловая система Symfony.
     */
    public function __construct(
        ContainerBag $containerBag,
        Timber $twig,
        Filesystem $filesystem
    ) {
        $this->twig = $twig;
        $this->filesystem = $filesystem;
        $this->containerBag = $containerBag;

        if ($this->containerBag->has('twig')) {
            $this->configuration = $this->containerBag->get('twig');
        }

        if ($this->containerBag->has('twig_config')) {
            $this->configuration = array_merge($this->configuration, $this->containerBag->get('twig_config'));
        }
    }

    /**
     * Геттер конфигурации расширенных функций TWIG.
     *
     * @return array
     */
    public function getTwigFunctionsConfig(): array
    {
        if (!array_key_exists('functions', $this->configuration) || !$this->configuration['functions']) {
            return [];
        }

        /** Результат. */
        $arResult = [];
        // Проверка на существование обработчиков.
        foreach ($this->configuration['functions'] as $function => $className) {
            if (is_callable($className)
                ||
                strpos($className, '@') === 0 // Пропускать алиасы сервисов.
            ) {
                $arResult[$function] = $className;
            }
        }

        return $arResult;
    }

    /**
     * Получить данные обработчиков рендеров шаблонов.
     *
     * @return array
     * @throws RuntimeException
     */
    public function getTwigRendersConfig(): array
    {
        if (!array_key_exists('renders', $this->configuration) || !$this->configuration['renders']) {
            return [];
        }

        /** Результат. */
        $arResult = [];

        // Проверка на существование обработчиков.
        foreach ($this->configuration['renders'] as $typePost => $arData) {
            if ($typePost === 'default' || class_exists($arData['class'])) {
                $arResult[$typePost] = [
                    'class' => $arData['class'],
                    'template' => $arData['template'],
                ];

                continue;
            }

            throw new RuntimeException(
                'Нет такого класса рендерера Twig шаблонов. '.$arData['class']
            );
        }

        return $arResult;
    }
}
