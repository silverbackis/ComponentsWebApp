<?php

namespace App\DataFixtures\Layout;

use App\DataFixtures\Content\HomePageFixture;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
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
    )
    {
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

        $manager->flush();
    }

    /**
     * @return array
     */
    public function getDependencies(): array
    {
        return [
            DefaultLayout::class,
            HomePageFixture::class
        ];
    }
}
