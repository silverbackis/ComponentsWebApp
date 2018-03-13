<?php

namespace App\DataFixtures\Content;

use App\DataFixtures\Layout\DefaultLayout;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\Entity\Layout\Layout;
use Silverback\ApiComponentBundle\Entity\Route\Route;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Content\ContentFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Hero\HeroFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\PageFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Route\RouteFactory;

class HomePageFixture extends AbstractFixture implements DependentFixtureInterface
{
    /** @var PageFactory */
    private $pageFactory;
    /** @var HeroFactory */
    private $heroFactory;
    /** @var ContentFactory */
    private $contentFactory;
    /** @var RouteFactory  */
    private $routeFactory;

    /**
     * HomePageFixture constructor.
     * @param PageFactory $pageFactory
     * @param HeroFactory $heroFactory
     * @param ContentFactory $contentFactory
     * @param RouteFactory $routeFactory
     */
    public function __construct(
        PageFactory $pageFactory,
        HeroFactory $heroFactory,
        ContentFactory $contentFactory,
        RouteFactory $routeFactory
    )
    {
        $this->pageFactory = $pageFactory;
        $this->heroFactory = $heroFactory;
        $this->contentFactory = $contentFactory;
        $this->routeFactory = $routeFactory;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        /** @var Layout $layout */
        $layout = $this->getReference('layout');
        $homeRoute = new Route('/');
        $page = $this->pageFactory->create(
            [
                'title' => 'Home Page',
                'metaDescription' => 'Starter website home page',
                'parent' => null,
                'layout' => $layout,
                'route' => $homeRoute
            ]
        );
        $this->addReference('page.home', $page);

        $this->heroFactory->create(
            [
                'title' => 'Welcome',
                'subtitle' => 'This is the BW Starter PWA',
                'parentContent' => $page
            ]
        );

        $this->contentFactory->create(
            [
                'parentContent' => $page
            ]
        );

        $this->routeFactory->create(
            [
                'route' => '/home',
                'redirect' =>$homeRoute
            ]
        );

        $manager->flush();
    }

    public function getDependencies(): array
    {
        return [
            DefaultLayout::class
        ];
    }
}
