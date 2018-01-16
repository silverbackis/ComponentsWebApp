<?php

namespace App\DataFixtures\Page\Navigation\SideMenu;

use App\DataFixtures\CustomEntityInterface;
use App\DataFixtures\Page\AbstractPage;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\Entity\Component\ComponentGroup;

class SideMenuGroup extends AbstractPage implements CustomEntityInterface, DependentFixtureInterface
{
    public function getEntity () {
        return new ComponentGroup();
    }

    public function load(ObjectManager $manager)
    {
        parent::load($manager);
        $this->entity->setParent($this->getReference('nav.sidemenu'));
        $this->addContent();
        $this->addReference('page.navigation.sidemenu.components', $this->entity);
        $this->flush();
    }
    public function getDependencies()
    {
        return [
            SideMenuPage::class
        ];
    }
}
