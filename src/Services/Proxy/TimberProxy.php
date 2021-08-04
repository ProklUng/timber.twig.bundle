<?php

namespace Prokl\TimberTwigBundle\Services\Proxy;

use Prokl\TimberTwigBundle\Services\Proxy\Contract\TimberProxyInterface;
use Timber\Post;
use Timber\PostQuery;
use Timber\Timber;

/**
 * Class TimberProxy
 * Для миграции на следующую версию Timber.
 * @package Prokl\TimberTwigBundle\Services\Proxy
 *
 * @since 04.08.2021
 */
class TimberProxy implements TimberProxyInterface
{
    /**
     * @inheritdoc
     */
    public function postInstance(?int $idPost = null)
    {
        return new Post($idPost);
    }

    /**
     * @inheritdoc
     */
    public function postQuery($query = false)
    {
        return new PostQuery($query);
    }

    /**
     * @inheritdoc
     */
    public function getContext() : array
    {
        return Timber::get_context();
    }

    /**
     * @inheritdoc
     */
    public function setLocations(array $locations) : void
    {
        Timber::$locations = $locations;
    }
}