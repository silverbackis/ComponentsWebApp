<?php

namespace App\DataFixtures\Model;

use Silverback\ApiComponentBundle\Entity\Content\Page\StaticPage;
use Silverback\ApiComponentBundle\Entity\Route\Route;

class PageFixtureModel
{
    /** @var Route */
    private $route;
    /** @var StaticPage */
    private $page;
    /** @var bool */
    private $isNew;

    /**
     * PageFixtureModel constructor.
     * @param Route $route
     * @param StaticPage $page
     * @param bool $isNew
     */
    public function __construct(Route $route, StaticPage $page, bool $isNew)
    {
        $this->route = $route;
        $this->page = $page;
        $this->isNew = $isNew;
    }

    /**
     * @return Route
     */
    public function getRoute(): Route
    {
        return $this->route;
    }

    /**
     * @return StaticPage
     */
    public function getStaticPage(): StaticPage
    {
        return $this->page;
    }

    /**
     * @return bool
     */
    public function isNew(): bool
    {
        return $this->isNew;
    }
}
