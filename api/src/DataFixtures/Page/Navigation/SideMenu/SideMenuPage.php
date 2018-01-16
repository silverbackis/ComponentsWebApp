<?php

namespace App\DataFixtures\Page\Navigation\SideMenu;

use App\DataFixtures\Page\AbstractPage;
use App\DataFixtures\Page\NavigationPage;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class SideMenuPage extends AbstractPage implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     * @throws \BadMethodCallException
     */
    public function load(ObjectManager $manager)
    {
        parent::load($manager);

        $this->entity->setTitle('Side Menu');
        $this->entity->setMetaDescription('An example of adding a a side menu to a page with a component group');
        $this->entity->setParent($this->getReference('page.navigation'));
        $this->addHero('Side Menu', 'This is how you can add a side menu and using component groups for static child components');

        $this->flush();
        $this->addReference('page.navigation.sidemenu', $this->entity);
    }

    public function getDependencies()
    {
        return [
            NavigationPage::class
        ];
    }
}
