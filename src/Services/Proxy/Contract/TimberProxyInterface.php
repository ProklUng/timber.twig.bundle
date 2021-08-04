<?php

namespace Prokl\TimberTwigBundle\Services\Proxy\Contract;

/**
 * Interface TimberProxyInterface
 * @package Prokl\TimberTwigBundle\Services\Proxy
 */
interface TimberProxyInterface
{
    /**
     * Тимберовский Post.
     *
     * @param integer|null $idPost
     *
     * @return mixed
     */
    public function postInstance(?int $idPost = null);

    /**
     *
     * Тимберовский PostQuery.
     *
     * @param mixed $query
     *
     * @return mixed
     */
    public function postQuery($query = false);

    /**
     * Timber context.
     *
     * @return array
     */
    public function getContext() : array;

    /**
     * Пути к шаблонам.
     *
     * @param array $locations Массив с путями к шаблонам Твиг.
     *
     * @return void
     */
    public function setLocations(array $locations) : void;
}