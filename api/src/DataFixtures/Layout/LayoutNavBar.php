<?php

namespace App\DataFixtures\Layout;

use App\DataFixtures\Content\FeaturePageFixture;
use App\DataFixtures\Content\FormPageFixture;
use App\DataFixtures\Content\GalleryPageFixture;
use App\DataFixtures\Content\HomePageFixture;
use App\DataFixtures\Content\NavigationPagesFixture;
use App\DataFixtures\Content\NewsPageFixture;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\Entity\Content\ComponentGroup;
use Silverback\ApiComponentBundle\Entity\Content\Page;
use Silverback\ApiComponentBundle\Entity\Layout\Layout;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Navigation\NavBar\NavBarFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Navigation\NavBar\NavBarItemFactory;

class LayoutNavBar extends AbstractFixture implements DependentFixtureInterface
{
    /**
     * @var NavBarFactory
     */
    private $navBarFactory;
    /**
     * @var NavBarItemFactory
     */
    private $navBarItemFactory;

    public function __construct(
        NavBarFactory $navBarFactory,
        NavBarItemFactory $navBarItemFactory
    ) {
        $this->navBarFactory = $navBarFactory;
        $this->navBarItemFactory = $navBarItemFactory;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $navBar = $this->navBarFactory->create();
        $manager->flush();

        /** @var Layout $layout */
        $layout = $this->getReference('layout');
        $layout->setNavBar($navBar);
        $manager->flush();

        /** @var Page $homePage */
        $pageHome = $this->getReference('page.home');

        $this->navBarItemFactory->create(
            [
                'label' => 'Home',
                'route' => $pageHome->getRoutes()->first() ?: null,
                'fragment' => null,
                'parentComponent' => $navBar
            ]
        );

        $pageNavigation = $this->getReference('page.navigation');
        $navigationTop = $this->navBarItemFactory->create(
            [
                'label' => 'Navigation',
                'route' => $pageNavigation->getRoutes()->first() ?: null,
                'parentComponent' => $navBar,
                'componentGroup' => new ComponentGroup()
            ]
        );
        $pageNavigation_Hero = $this->getReference('page.navigation.hero');
        $this->navBarItemFactory->create(
            [
                'label' => 'Hero Tabs',
                'route' => $pageNavigation_Hero->getRoutes()->first() ?: null,
                'parentComponent' => $navigationTop
            ]
        );
        $pageNavigation_Tabs = $this->getReference('page.navigation.tabs');
        $this->navBarItemFactory->create(
            [
                'label' => 'Page Tabs',
                'route' => $pageNavigation_Tabs->getRoutes()->first() ?: null,
                'parentComponent' => $navigationTop
            ]
        );
        $pageNavigation_Menu = $this->getReference('page.navigation.menu');
        $this->navBarItemFactory->create(
            [
                'label' => 'Side Menu',
                'route' => $pageNavigation_Menu->getRoutes()->first() ?: null,
                'parentComponent' => $navigationTop
            ]
        );

        $pageForms = $this->getReference('page.form');
        $this->navBarItemFactory->create(
            [
                'label' => 'Forms',
                'route' => $pageForms->getRoutes()->first() ?: null,
                'fragment' => null,
                'parentComponent' => $navBar
            ]
        );

        $pageFeatures = $this->getReference('page.feature');
        $this->navBarItemFactory->create(
            [
                'label' => 'Features',
                'route' => $pageFeatures->getRoutes()->first() ?: null,
                'fragment' => null,
                'parentComponent' => $navBar
            ]
        );

        $pageGallery = $this->getReference('page.gallery');
        $this->navBarItemFactory->create(
            [
                'label' => 'Gallery',
                'route' => $pageGallery->getRoutes()->first() ?: null,
                'fragment' => null,
                'parentComponent' => $navBar
            ]
        );

        $pageNews = $this->getReference('page.news');
        $this->navBarItemFactory->create(
            [
                'label' => 'News / Blog',
                'route' => $pageNews->getRoutes()->first() ?: null,
                'fragment' => null,
                'parentComponent' => $navBar
            ]
        );

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            DefaultLayout::class,
            HomePageFixture::class,
            NavigationPagesFixture::class,
            FormPageFixture::class,
            FeaturePageFixture::class,
            GalleryPageFixture::class,
            NewsPageFixture::class
        ];
    }
}
