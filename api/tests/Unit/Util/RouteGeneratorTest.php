<?php

namespace App\Tests\Unit\Util;

use App\Entity\Page;
use App\Util\RouteGenerator;
use Cocur\Slugify\Slugify;
use Cocur\Slugify\SlugifyInterface;
use PHPUnit\Framework\TestCase;

class RouteGeneratorTest extends TestCase
{
    /**
     * @test
     */
    public function nested_auto_route_generation ()
    {

        $parent = new Page();
        $parent->setTitle('parent');

        $child = new Page();
        $child->setTitle('child');
        $child->setParent($parent);

        $generator = new RouteGenerator(new Slugify());
        $route = $generator->createPageRoute($child);
        $this->assertEquals('/parent/child', $route->getRoute());
    }

    /**
     * {@inheritDoc}
     */
    protected function tearDown()
    {
        parent::tearDown();
        $this->slugify = null;
    }
}
