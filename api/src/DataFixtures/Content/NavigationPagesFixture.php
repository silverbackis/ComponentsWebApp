<?php

namespace App\DataFixtures\Content;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Silverback\ApiComponentBundle\Entity\Content\Page;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Content\ContentFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Hero\HeroFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Navigation\Menu\MenuFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Navigation\Menu\MenuItemFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Navigation\Tabs\TabsFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Navigation\Tabs\TabsItemFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\PageFactory;

class NavigationPagesFixture extends AbstractFixture
{
    /** @var EntityManagerInterface */
    private $em;
    /** @var Page */
    private $navigationTop;

    /** @var PageFactory */
    private $pageFactory;
    /** @var HeroFactory */
    private $heroFactory;
    /** @var TabsFactory  */
    private $tabsFactory;
    /** @var TabsItemFactory */
    private $tabsItemFactory;
    /** @var MenuFactory */
    private $menuFactory;
    /** @var MenuItemFactory */
    private $menuItemFactory;
    /** @var ContentFactory  */
    private $contentFactory;

    /**
     * HomePageFixture constructor.
     * @param PageFactory $pageFactory
     * @param HeroFactory $heroFactory
     * @param TabsFactory $tabsFactory
     * @param TabsItemFactory $tabsItemFactory
     * @param MenuFactory $menuFactory
     * @param MenuItemFactory $menuItemFactory
     * @param ContentFactory $contentFactory
     */
    public function __construct(
        PageFactory $pageFactory,
        HeroFactory $heroFactory,
        TabsFactory $tabsFactory,
        TabsItemFactory $tabsItemFactory,
        MenuFactory $menuFactory,
        MenuItemFactory $menuItemFactory,
        ContentFactory $contentFactory
    )
    {
        $this->pageFactory = $pageFactory;
        $this->heroFactory = $heroFactory;
        $this->tabsFactory = $tabsFactory;
        $this->tabsItemFactory = $tabsItemFactory;
        $this->menuFactory = $menuFactory;
        $this->menuItemFactory = $menuItemFactory;
        $this->contentFactory = $contentFactory;
    }

    /**
     * @param ObjectManager $em
     */
    public function load(ObjectManager $em): void
    {
        $this->em = $em;
        $this->navigationTop = $this->pageFactory->create(
            [
                'title' => 'Navigation'
            ]
        );
        $this->em->flush();
        $this->em->refresh($this->navigationTop);
        $this->addReference('page.navigation', $this->navigationTop);

        $heroPage = $this->createHeroNavigationPage();
        $this->createTabsNavigationPage();
        $this->createMenuNavigationPage();

        $this->navigationTop->getRoutes()->first()->setRedirect($heroPage->getRoutes()->first());
        $this->em->flush();
    }

    private function createHeroNavigationPage(): Page
    {
        $heroPage = $this->pageFactory->create(
            [
                'title' => 'Hero Tabs',
                'metaDescription' => 'Tabs in the hero component',
                'parent' => $this->navigationTop
            ]
        );

        $heroPageOne = $this->pageFactory->create(
            [
                'title' => 'Hero Page One',
                'parent' => $heroPage
            ]
        );

        $heroPageTwo = $this->pageFactory->create(
            [
                'title' => 'Hero Page Two',
                'parent' => $heroPage
            ]
        );

        $this->contentFactory->create(
            [
                'parentContent' => $heroPageOne
            ]
        );

        $this->contentFactory->create(
            [
                'parentContent' => $heroPageTwo
            ]
        );

        $this->em->flush();
        $this->em->refresh($heroPage);
        $this->em->refresh($heroPageOne);
        $this->em->refresh($heroPageTwo);

        $heroPage->getRoutes()->first()->setRedirect($heroPageOne->getRoutes()->first());

        $hero = $this->heroFactory->create(
            [
                'title' => 'Hero Tabs',
                'parentContent' => $heroPage
            ]
        );

        $tabs = $this->tabsFactory->create(
            [
                'parentContent' => $hero->getComponentGroups()->first()
            ]
        );
        $this->tabsItemFactory->create(
            [
                'label' => 'Hero Page 1',
                'route' => $heroPageOne->getRoutes()->first(),
                'parentComponent' => $tabs
            ]
        );
        $this->tabsItemFactory->create(
            [
                'label' => 'Hero Page 2',
                'route' => $heroPageTwo->getRoutes()->first(),
                'parentComponent' => $tabs
            ]
        );

        $this->addReference('page.navigation.hero', $heroPage);
        return $heroPageOne;
    }

    private function createTabsNavigationPage(): Page
    {
        $tabsPage = $this->pageFactory->create(
            [
                'title' => 'Page Tabs',
                'metaDescription' => 'Page tabs',
                'parent' => $this->navigationTop
            ]
        );

        $tabsPageOne = $this->pageFactory->create(
            [
                'title' => 'Tabs Page One',
                'parent' => $tabsPage
            ]
        );

        $tabsPageTwo = $this->pageFactory->create(
            [
                'title' => 'Tabs Page Two',
                'parent' => $tabsPage
            ]
        );

        $this->contentFactory->create(
            [
                'parentContent' => $tabsPageOne
            ]
        );

        $this->contentFactory->create(
            [
                'parentContent' => $tabsPageTwo
            ]
        );

        $this->em->flush();
        $this->em->refresh($tabsPage);
        $this->em->refresh($tabsPageOne);
        $this->em->refresh($tabsPageTwo);

        $tabsPage->getRoutes()->first()->setRedirect($tabsPageOne->getRoutes()->first());

        $this->heroFactory->create(
            [
                'title' => 'Page Tabs',
                'parentContent' => $tabsPage
            ]
        );

        $tabs = $this->tabsFactory->create(
            [
                'parentContent' => $tabsPage
            ]
        );
        $this->tabsItemFactory->create(
            [
                'label' => 'Tabs Page 1',
                'route' => $tabsPageOne->getRoutes()->first(),
                'parentComponent' => $tabs
            ]
        );
        $this->tabsItemFactory->create(
            [
                'label' => 'Tabs Page 2',
                'route' => $tabsPageTwo->getRoutes()->first(),
                'parentComponent' => $tabs
            ]
        );

        $this->addReference('page.navigation.tabs', $tabsPage);
        return $tabsPageOne;
    }

    private function createMenuNavigationPage(): Page
    {
        $menuPage = $this->pageFactory->create(
            [
                'title' => 'Menu',
                'metaDescription' => 'Menu',
                'parent' => $this->navigationTop
            ]
        );
        $this->addReference('page.navigation.menu', $menuPage);
        $this->em->flush();
        $this->em->refresh($menuPage);

        $this->heroFactory->create(
            [
                'title' => 'Menu',
                'subtitle' => 'Also using a component group child',
                'parentContent' => $menuPage
            ]
        );

        $menu = $this->menuFactory->create(
            [
                'parentContent' => $menuPage
            ]
        );

        $this->menuItemFactory->create(
            [
                'label' => 'Side Page 1',
                'route' => $menuPage->getRoutes()->first(),
                'fragment' => 'fragment_1',
                'parentComponent' => $menu
            ]
        );

        $this->menuItemFactory->create(
            [
                'label' => 'Side Page 2',
                'route' => $menuPage->getRoutes()->first(),
                'fragment' => 'fragment_2',
                'parentComponent' => $menu
            ]
        );

        $this->contentFactory->create(
            [
                'parentContent' => $menu->getChildComponentGroup()
            ]
        );

        return $menuPage;
    }
}
