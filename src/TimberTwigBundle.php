<?php

namespace Prokl\TimberTwigBundle;

use Prokl\TimberTwigBundle\DependencyInjection\CompilerPass\TwigBundlesViewCompilerPass;
use Prokl\TimberTwigBundle\DependencyInjection\CompilerPass\TwigCheckerConfigCompilerPass;
use Prokl\TimberTwigBundle\DependencyInjection\TimberTwigExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * Class TimberTwigBundle
 * @package Prokl\TimberTwigBundle
 *
 * @since 04.08.2021
 */
class TimberTwigBundle extends Bundle
{
   /**
   * @inheritDoc
   */
    public function getContainerExtension()
    {
        if ($this->extension === null) {
            $this->extension = new TimberTwigExtension();
        }

        return $this->extension;
    }

    /**
     * @inheritDoc
     */
    public function build(ContainerBuilder $container) : void
    {
        parent::build($container);

        $container->addCompilerPass(new TwigCheckerConfigCompilerPass());
        $container->addCompilerPass(new TwigBundlesViewCompilerPass());
    }
}
