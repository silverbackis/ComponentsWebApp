<?php

namespace App\Util;

use App\Entity\Page;
use App\Entity\Route;
use Cocur\Slugify\SlugifyInterface;

class RouteGenerator
{
    /**
     * @var SlugifyInterface
     */
    private $slugify;

    public function __construct(
        SlugifyInterface $slugify
    )
    {
        $this->slugify = $slugify;
    }

    public function createPageRoute(Page $page): ?Route
    {
        $pageRoute = $this->slugify->slugify($page->getTitle());
        $routePrefix = '/';
        $parent = $page->getParent();
        if ($parent) {
            $parentRoute = $parent->getRoutes()->first();
            if (!$parentRoute) {
                $parentRoute = $this->createPageRoute($parent);
            }
            $routePrefix = $parentRoute->getRoute() . '/';
        }
        $route = new Route(
            $routePrefix . $pageRoute,
            $page
        );
        $page->addRoute($route);
        return $route;
    }
}
