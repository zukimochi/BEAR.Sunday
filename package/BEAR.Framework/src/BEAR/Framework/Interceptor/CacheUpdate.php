<?php
namespace BEAR\Framework\Interceptor;

use Ray\Aop\MethodInterceptor,
    Ray\Aop\MethodInvocation;
use Doctrine\Common\Cache\Cache as Cacheable,
    Doctrine\Common\Cache\MemcacheCache;

/**
 * Cache interceptor
 */
class CacheUpdate implements MethodInterceptor
{
    /**
     * Host
     *
     * @var string
     */
    private $host;

    /**
     * Life time
     *
     * @var int
     */
    private $lifeTime;

    /**
     * Constructor
     *
     * @param Cache $cache
     * @param unknown_type $lifeTime
     */
    public function __construct(Cacheable $cache, $appName, $lifeTime = 0, $host = 'locahost')
    {
        $cache->setNamespace($appName);
        $this->cache = $cache;
        $this->host = $host;
        $this->lifeTime = $lifeTime;
    }

    /**
     * Create memchace property in runtime init
     *
     */
    public function __wakeup()
    {
        $memcahce = new \Memcache;
        $memcahce->connect($this->host);
        $this->cache->setMemcache($memcahce);
    }

    /**
     * (non-PHPdoc)
     * @see Ray\Aop.MethodInterceptor::invoke()
     */
    public function invoke(MethodInvocation $invocation)
    {
        v(MethodInvocation);
        $data = $invocation->proceed();
        $id = $this->getId($invocation, $invocation->getArguments());
        $saved = $this->cache->fetch($id);
        if ($saved) {
            return $saved;
        }
        $this->cache->save($id, $data, 0);
        $saved = $this->cache->fetch($id);
        return $data;
    }

    /**
     * Return cache id
     *
     * @param MethodInvocation $invocation
     * @param array $args
     */
    protected function getId(MethodInvocation $invocation, $args)
    {
        $class = get_class($invocation->getThis());
        $method = $invocation->getMethod()->name;
        return $class . $method . md5(serialize($args));
    }
}
