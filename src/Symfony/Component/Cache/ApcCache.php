<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Cache;

/**
 * APC Cache implementation
 *
 * Very simple implementation that can be used as default with various Symfony components
 * that support caching, such as Validation, ClassLoader.
 *
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 * @author Florin Patan <florinpatan@gmail.com>
 */
class ApcCache implements CacheInterface
{
    public function __construct()
    {
        if (!extension_loaded('apc')) {
            throw new \RuntimeException("You need the APC php extension installed to use this cache driver.");
        }

        if (strnatcmp(phpversion(),'3.0.17') < 0) {
            throw new \RuntimeException("You need to have APC version 3.0.17 or newer in order to run this.");
        }
    }

    /**
     * {@inheritdoc}
     */
    public function get($key, &$data)
    {
        $result = false;
        $data = apc_fetch($key, $result);

        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function set($key, $data, $options = array())
    {
        // Check if we have any lifeTime specified for the cache entry
        if (isset($options['lifeTime'])) {
            $lifeTime = (int) $options['lifeTime'];
        } else {
            $lifeTime = 0;
        }

        return apc_store($key, $data, $lifeTime);
    }

    /**
     * {@inheritdoc}
     */
    public function delete($key)
    {
        return apc_delete($key);
    }

    /**
     * {@inheritdoc}
     */
    public function exists($key)
    {
        return apc_exists($key);
    }

    /**
     * {@inheritdoc}
     */
    public function clear()
    {
        return apc_clear_cache('user');
    }
}
