<?php

namespace App\DataFixtures\Nav;

use App\DataFixtures\CustomEntityInterface;
use App\DataFixtures\Page\Navigation\SideMenu\SideMenuPage;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\Entity\Component\Nav\Menu\Menu;

class SideMenuNav extends AbstractNav implements DependentFixtureInterface, CustomEntityInterface
{
    public function getEntity () {
        return new Menu();
    }

    public function load(ObjectManager $manager)
    {
        parent::load($manager);
        $this->addNavItem('Menu One', 0, $this->getReference('page.navigation.sidemenu'), 'frag1');
        $this->addNavItem('Menu Two', 1, $this->getReference('page.navigation.sidemenu'), 'frag2');
        $this->addReference('nav.sidemenu', $this->entity);
        $this->entity->setPage($this->getReference('page.navigation.sidemenu'));
        $this->flush();
    }

    /**
     * Return page fixtures that this nav uses
     * @return array
     */
    public function getDependencies()
    {
        return [
            SideMenuPage::class
        ];
    }
}
