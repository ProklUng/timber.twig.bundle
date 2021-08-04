<?php

namespace Prokl\TimberTwigBundle\Tests\Fixtures;

/**
 * Class FooFunctionHandler
 * @package Prokl\TimberTwigBundle\Tests\Fixtures
 */
class FooFunctionHandler
{
    /**
     * @return string
     */
    public function handler() : string
    {
        return 'OK';
    }
}