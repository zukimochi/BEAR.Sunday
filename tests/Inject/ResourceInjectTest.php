<?php

namespace BEAR\Sunday\Inject;

use BEAR\Resource\Module\ResourceClientModule;
use BEAR\Resource\ResourceInterface;
use BEAR\Sunday\Module\Resource\ResourceModule;
use Psr\Log\LoggerInterface;
use Ray\Di\AbstractModule;
use Ray\Di\Injector;

class ResourceInjectApplication
{
    use ResourceInject;

    public function returnDependency()
    {
        return $this->resource;
    }
}

class ResourceInjectTest extends \PHPUnit_Framework_TestCase
{
    public function testInjectTrait()
    {
        $app = (new Injector(new ResourceModule))->getInstance(__NAMESPACE__ . '\ResourceInjectApplication');
        $this->assertInstanceOf(ResourceInterface::class, $app->returnDependency());
    }
}
