<?php

namespace App\Tests\Unit\Util;

use Cocur\Slugify\Slugify;
use PHPUnit\Framework\TestCase;
use Silverback\ApiComponentBundle\Entity\Page;
use Silverback\ApiComponentBundle\Factory\RouteFactory;

class RouteGeneratorTest extends TestCase
{
    public function test_nested_auto_route_generation ()
    {

        $parent = new Page();
        $parent->setTitle('parent');

        $child = new Page();
        $child->setTitle('child');
        $child->setParent($parent);

        $generator = new RouteFactory(new Slugify());
        $route = $generator->create($child);
        $this->assertEquals('/parent/child', $route->getRoute());
    }
}
