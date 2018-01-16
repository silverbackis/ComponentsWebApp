<?php

namespace App\DataFixtures\Nav;

use App\DataFixtures\Page\Navigation\Hero\HeroNavbarPage;
use App\DataFixtures\Page\Navigation\Hero\HeroOne;
use App\DataFixtures\Page\Navigation\Hero\HeroTwo;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class HeroNav extends AbstractNav implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        parent::load($manager);
        $this->addNavItem('Hero One', 0, $this->getReference('page.navigation.hero.hero1'));
        $this->addNavItem('Hero Two', 1, $this->getReference('page.navigation.hero.hero2'));
        $this->addReference('nav.hero', $this->entity);
        $this->getReference('hero.navigation')->setNav($this->entity);
        $this->flush();
    }

    /**
     * Return page fixtures that this nav uses
     * @return array
     */
    public function getDependencies()
    {
        return [
            HeroOne::class,
            HeroTwo::class,
            HeroNavbarPage::class
        ];
    }
}
