<?php

namespace Symfony\Tests\Component\Cache;

use Symfony\Component\Cache\ApcCache;

/**
 * @group GH-1513
 */
class ApcCacheTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Symfony\Component\Cache\ApcCache
     */
    private $cache;

    public function setUp()
    {
        if (!extension_loaded('apc') || !ini_get('apc.enable_cli')) {
            $this->markTestSkipped('APC needs to be installed for this tests.');
        }

        $this->cache = new ApcCache();
    }

    public function testSaveReturnsTrueOnSuccess()
    {
        $ret = $this->cache->save('id', 1);

        $this->assertTrue($ret);
    }

    public function testFetchUnknownReturnsFalse()
    {
        $value = '';
        $result = $this->cache->fetch('unknown', $value);
        $this->assertFalse($result);
    }

    public function testFetchKnownReturnsValue()
    {
        $this->cache->save('known', 1);
        $value = '';
        $result = $this->cache->fetch('known', $value);

        $this->assertTrue($result);
        $this->assertSame(1, $value);
    }

    public function testFetchKnownFalse()
    {
        $this->cache->save('false', false);
        $value = '';
        $result = $this->cache->fetch('false', $value);

        $this->assertTrue($result);
        $this->assertFalse($value);
    }

    public function testContainsFalseValue()
    {
        $this->cache->save('contains_false', false);

        $value = '';
        $this->assertTrue($this->cache->fetch('contains_false', $value));
    }

    public function testContainsValue()
    {
        $this->cache->save('contains', 1234);

        $value = '';
        $this->assertTrue($this->cache->fecth('contains', $value));
    }

    public function testDelete()
    {
        $this->cache->save('delete', 1234);

        $value = '';
        $this->assertTrue($this->cache->contains('delete', $value));

        $this->cache->delete('delete');
        $this->assertFalse($this->cache->contains('delete', $value));
    }
}

