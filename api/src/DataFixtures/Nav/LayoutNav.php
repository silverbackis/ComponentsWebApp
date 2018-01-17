<?php

namespace App\DataFixtures\Nav;

use App\DataFixtures\Page\FormPage;
use App\DataFixtures\Page\HomePage;
use App\DataFixtures\Page\Navigation\Hero\HeroNavbarPage;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\DataFixtures\Nav\AbstractNav;

class LayoutNav extends AbstractNav implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        parent::load($manager);
        $this->getReference('layout.default')->setNav($this->entity);

        $this->addNavItem('Home', 0, $this->getReference('page.home'));
        $this->addNavItem('Forms', 0, $this->getReference('page.forms'));
        $navs = $this->addNavItem('Navigation', 0, $this->getReference('page.navigation'));

        $this->addReference('nav.layout', $this->entity);
        $this->addReference('nav.layout.navs', $navs);
        $this->flush();
    }

    /**
     * Return page fixtures that this nav uses
     * @return array
     */
    public function getDependencies()
    {
        return [
            HomePage::class,
            FormPage::class,
            HeroNavbarPage::class
        ];
    }
}