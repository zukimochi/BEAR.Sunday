<?php

namespace BEAR\Sunday\Module\Cache;

use BEAR\Sunday\Module\Constant\NamedModule;
use Ray\Di\Injector;
use Doctrine\Common\Cache\Cache;
use Ray\Di\Di\Inject;

class CacheClient
{
    public $cache;

    /**
     * @Inject
     */
    public function setCache(Cache $cache)
    {
        $this->cache = $cache;
    }
}

class CacheModuleTest extends \PHPUnit_Framework_TestCase
{

    public function testCache()
    {
        $config = [
            'tmp_dir' => $_ENV['TEST_DIR']
        ];
        $app = (new Injector(new CacheModule(new NamedModule($config))))->getInstance(__NAMESPACE__ . '\CacheClient');
        $this->assertInstanceOf('Doctrine\Common\Cache\Cache', $app->cache);
    }
}
