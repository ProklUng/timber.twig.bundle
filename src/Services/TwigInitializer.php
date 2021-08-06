<?php

namespace Prokl\TimberTwigBundle\Services;

use Exception;
use RuntimeException;
use Timber\Twig_Function;
use Twig\Environment;

/**
 * Class TwigInitializer
 * @package Prokl\TimberTwigBundle\Services
 *
 * @since 06.08.2021
 */
class TwigInitializer
{
    /**
     * @var array $extensions
     */
    private $extensions;

    /**
     * @var Environment $twig
     */
    private $twig;

    /**
     * TwigInitializer constructor.
     *
     * @param Environment $twig          Twig.
     * @param mixed       ...$extensions Extensions.
     */
    public function __construct(Environment $twig, ...$extensions)
    {
        $this->twig = $twig;

        $handlers = [];

        foreach ($extensions as $extension) {
            $iterator = $extension->getIterator();
            $handlers[] = iterator_to_array($iterator);
        }

        $this->extensions = $handlers;
    }

    /**
     * Применить extensions.
     *
     * @return void
     */
    public function applyExtensions() : void
    {
        $this->twig->setExtensions($this->extensions);
    }

    /**
     * Применить функции.
     *
     * @param array $functions Функции.
     *
     * @return void
     */
    public function applyFunctions(array $functions) : void
    {
        foreach ($functions as $function => $callable) {
            if (!is_callable($callable)) {
                throw new RuntimeException('Twig function handler must be callable.');
            }

            try {
                $function = new Twig_Function(
                    $function,
                    $callable
                );

                $this->twig->addFunction($function);
            } catch (Exception $e) {
            }
        }
    }
}
