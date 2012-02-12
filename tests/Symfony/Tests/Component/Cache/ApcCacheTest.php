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
        $ret = $this->cache->set('id', 1);

        $this->assertTrue($ret);
    }

    public function testGetUnknownReturnsFalse()
    {
        $result = $this->cache->get('unknown');
        $this->assertFalse($result);
    }

    public function testGetKnownReturnsValue()
    {
        $this->cache->set('known', 1);
        $result = $this->cache->get('known');

        $this->assertSame(1, $result);
    }

    public function testGetKnownFalse()
    {
        $this->cache->set('false', false);
        $result = $this->cache->get('false');

        $this->assertFalse($result);
    }

    public function testFetchUnknownReturnsFalse()
    {
        $value = '';
        $result = $this->cache->fetch('unknown', $value);
        $this->assertFalse($result);
    }

    public function testFetchKnownReturnsValue()
    {
        $this->cache->set('known', 1);
        $value = '';
        $result = $this->cache->fetch('known', $value);

        $this->assertTrue($result);
        $this->assertSame(1, $value);
    }

    public function testFetchKnownFalse()
    {
        $this->cache->set('false', false);
        $value = '';
        $result = $this->cache->fetch('false', $value);

        $this->assertTrue($result);
        $this->assertFalse($value);
    }

    public function testContainsFalseValue()
    {
        $this->cache->set('contains_false', false);

        $this->assertTrue($this->cache->exists('contains_false'));
    }

    public function testContainsValue()
    {
        $this->cache->set('contains', 1234);

        $this->assertTrue($this->cache->exists('contains'));
    }

    public function testDelete()
    {
        $this->cache->set('delete', 1234);

        $this->assertTrue($this->cache->exists('delete'));

        $this->cache->delete('delete');
        $this->assertFalse($this->cache->exists('delete'));
    }

    public function testClear()
    {
        $this->cache->set('delete', 1234);

        $this->cache->clear();

        $this->assertFalse($this->cache->exists('delete'));
    }
}

