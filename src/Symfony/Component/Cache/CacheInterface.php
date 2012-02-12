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
    function fetch($id, &$data);

    /**
     * Puts data into the cache.
     *
     * @param string $id The cache id.
     * @param string $data The cache entry/data.
     * @param int $lifeTime The lifetime in seconds. If != 0, sets a specific lifetime for this cache entry (0 => infinite lifeTime).
     * @return boolean TRUE if the entry was successfully stored in the cache, FALSE otherwise.
     */
    function save($id, $data, $lifeTime = 0);

    /**
     * Deletes a cache entry.
     *
     * @param string $id cache id
     * @return boolean TRUE if the cache entry was successfully deleted, FALSE otherwise.
     */
    function delete($id);
}

