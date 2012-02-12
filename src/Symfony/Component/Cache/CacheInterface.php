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
 * Generic Caching interface that any caching provider can implement/provide.
 *
 * Used inside Symfony is suggested to be used by any third-party
 * bundle to allow central cache management.
 *
 * @author Benjamin Eberlei <kontakt@beberlei.de>
 * @author Florin Patan <florinpatan@gmail.com>
 */
interface CacheInterface
{
    /**
     * Fetches an entry from the cache.
     *
     * @param string $id cache id The id of the cache entry to fetch.
     * @param mixed  $data cached data The value cached for the $id key.
     * @return boolean The result of fecthing the key from the cache system
     */
    function get($key, &$data);

    /**
     * Puts data into the cache.
     *
     * For the moment the only recognized option is:
     * - lifetime: expressed in seconds (default 0 = infinite). If != 0, sets a specific lifetime for this cache entry
     *
     * @param string $id The cache id.
     * @param string $data The cache entry/data.
     * @param array $options Various options to set the variables.
     * @return boolean TRUE if the entry was successfully stored in the cache, FALSE otherwise.
     */
    function set($key, $data, $options = array());

    /**
     * Deletes a cache entry.
     *
     * @param string $id cache id
     * @return boolean TRUE if the cache entry was successfully deleted, FALSE otherwise.
     */
    function delete($key);

    /**
     * Check if the key exists in the cache.
     *
     * @param string $key
     * @return boolean
     */
    function exists($key);

    /**
     * Clears the entire cache.
     *
     * Implementations may choose to ignore this. What happens in this case is up to the implementor.
     *
     * @return void
     */
    function clear();
}

