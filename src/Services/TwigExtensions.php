<?php

namespace Prokl\TimberTwigBundle\Services;

use Exception;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Timber\Timber;

/**
 * Class TwigExtensions
 * Расширение Twig.
 * @package Prokl\TimberTwigBundle\Services
 *
 * @since 11.09.2020 Рефакторинг.
 * @since 12.09.2020 Вынес инициализацию хуков в публичный метод, вызывающийся из сервис-контейнера.
 * @since 02.10.2020 Доработка ресолвера сервисов.
 * @since 17.02.2021 Обработка сервисов в разделе globals конфигурации.
 */
class TwigExtensions
{
    /**
     * @var TwigConfig $twigConfig Конфигурация Твига.
     */
    private $twigConfig;

    /**
     * @var ContainerInterface $container Контейнер.
     */
    private $container;

    /**
     * @var array $result
     */
    private $result = [];

    /**
     * TwigExtensions constructor.
     *
     * @param TwigConfig         $twigConfig
     * @param ContainerInterface $container
     */
    public function __construct(
        TwigConfig $twigConfig,
        ContainerInterface $container
    ) {
        $this->twigConfig = $twigConfig;
        $this->container = $container;
    }

    /**
     * Подмешать глобальный контекст.
     *
     * @param array $data Контекст.
     *
     * @return array
     *
     * @since 17.02.2021
     */
    public function addContext(array $data = []) : array
    {
        $context = Timber::get_context();

        return array_merge($data, $context);
    }

    /**
     * My custom Twig functionality.
     *
     * @return array
     * @throws Exception
     */
    public function functions() : array
    {
        /** Загрузить конфигурацию. */
        $arConfig = $this->twigConfig->getTwigFunctionsConfig();

        foreach ($arConfig as $function => $handler) {
            $callback = $handler;

            // Класс::метод могут быть не статическими. Обработка.
            if (is_string($handler) && strpos($handler, '::') !== false) {
                $parsedParams = $this->parseCallableString($handler);

                // Пытаемся взять класс из сервис-контейнера, если в начале стоит @.
                $instanceClass = $this->tryResolveService($parsedParams['class']);

                if (!is_object($instanceClass)) {
                    $instanceClass = new $parsedParams['class'];
                }

                $callback = [
                    $instanceClass,
                    $parsedParams['method'],
                ];
            }

            $this->result[$function] = $callback;
        }

        return $this->result;
    }

    /**
     * Попытаться подсунуть сервис.
     *
     * @ - ссылка на сервис.
     *
     * @param string $parameterValue
     *
     * @return array|mixed|object|string
     * @throws Exception
     *
     * @since 02.10.2020 Доработка.
     */
    private function tryResolveService(string $parameterValue)
    {
        // Пытаемся взять класс из сервис-контейнера по названию класса.
        $resolvedService = $this->getService($parameterValue);
        if ($resolvedService) {
            return $resolvedService;
        }

        if (strpos($parameterValue, '@') === 0) {
            $service = ltrim($parameterValue, '@');
            return $this->getService($service, $parameterValue);
        }

        return $parameterValue;
    }

    /**
     * Получить сервис из контейнера.
     *
     * @param string $serviceId    ID сервиса.
     * @param mixed  $defaultValue Значение по умолчанию. Возвращается, если сервис не найден.
     *
     * @return mixed
     * @throws Exception
     * @since 02.10.2020
     */
    private function getService(string $serviceId, $defaultValue = null)
    {
        if ($this->container->has($serviceId)) {
            return $this->container->get($serviceId);
        }

        return $defaultValue;
    }

    /**
     * Распарсить строку вида класс::метод.
     *
     * @param string $param
     *
     * @return array
     */
    private function parseCallableString(string $param): array
    {
        [$class, $method] = explode('::', $param);

        return [
            'class' => $class,
            'method' => $method,
        ];
    }
}