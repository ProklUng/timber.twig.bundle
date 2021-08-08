<?php

namespace Prokl\TimberTwigBundle\DependencyInjection\CompilerPass;

use RuntimeException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class TwigCheckerConfigCompilerPass
 * @package Prokl\TimberTwigBundle\DependencyInjection\CompilerPass
 *
 * @since 08.08.2021
 */
class TwigCheckerConfigCompilerPass implements CompilerPassInterface
{
    /**
     * @inheritdoc
     */
    public function process(ContainerBuilder $container): void
    {
        if (!$container->hasParameter('twig_config')) {
            throw new RuntimeException(
                'This bundle depends of Prokl\CustomFrameworkExtensionsBundle. ' .
                        'You forget do composer require proklung/core-framework-extension-bundle ' .
                        ' and configure him?'
            );
        }
    }
}
