<?php

namespace Prokl\TimberTwigBundle\Services;

use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Loader\FilesystemLoader;

/**
 * Class TwigService
 * @package Prokl\TimberTwigBundle\Services
 *
 * @since 07.09.2020
 * @since 12.10.2020 Доработка. Расширение функционала.
 */
class TwigService
{
    /**
     * @var Environment Twig.
     */
    private $twigEnvironment;

    /**
     * @var FilesystemLoader $loader Загрузчик Twig.
     */
    private $loader;

    /**
     * @var array $twigOptions Опции Twig.
     */
    private $twigOptions;

    /**
     * @var string $debug
     */
    private $debug;

    /**
     * @var string $cachePath
     */
    private $cachePath;

    /**
     * TwigService constructor.
     *
     * @param FilesystemLoader $loader      Загрузчик.
     * @param string           $debug       Среда.
     * @param string           $cachePath   Путь к кэшу (серверный).
     * @param array|null       $twigOptions Опции Твига.
     */
    public function __construct(
        FilesystemLoader $loader,
        string $debug,
        string $cachePath,
        ?array $twigOptions = null
    ) {
        $this->loader = $loader;
        $this->twigOptions = (array)$twigOptions;
        $this->debug = $debug;
        $this->cachePath = $cachePath;

        $this->twigEnvironment = $this->initTwig(
            $loader,
            $debug,
            $cachePath
        );
    }

    /**
     * Инстанс Твига.
     *
     * @return Environment
     */
    public function instance() : Environment
    {
        return $this->twigEnvironment;
    }

    /**
     * Пути к базовой директории шаблонов из конфига контейнера.
     *
     * @return array
     *
     * @since 06.11.2020
     */
    public function getPaths() : array
    {
        $this->twigOptions['paths'] = (array)$this->twigOptions['paths'];

        return $this->twigOptions['paths'] ?? [];
    }

    /**
     * Еще один базовый путь к шаблонам Twig.
     *
     * @param string $path Путь.
     *
     * @return void
     * @throws LoaderError Ошибки Twig.
     */
    public function addPath(string $path) : void
    {
        $this->loader->addPath($path);

        // Переинициализировать.
        $this->twigEnvironment = $this->initTwig(
            $this->loader,
            $this->debug,
            $this->cachePath
        );
    }

    /**
     * Инициализация.
     *
     * @param FilesystemLoader $loader    Загрузчик.
     * @param string           $debug     Среда.
     * @param string           $cachePath Путь к кэшу (серверный).
     *
     * @return Environment
     * @throws LoaderError Ошибки Твига.
     */
    private function initTwig(
        FilesystemLoader $loader,
        string $debug,
        string $cachePath
    ) : Environment {

        $allPaths = $loader->getPaths();

        if (array_key_exists('paths', $this->twigOptions)) {
            foreach ($this->twigOptions['paths'] as $path => $namespace) {
                if (in_array(trim($path), $allPaths, true)) {
                    continue;
                }

                if (!$namespace) {
                    $loader->addPath($path);
                } else {
                    $loader->addPath($path, $namespace);
                }
            }
        }

        return new Environment(
            $loader,
            [
                'debug' => (bool)$debug,
                'cache' => $cachePath,
                'autoescape' => false
            ]
        );
    }
}
