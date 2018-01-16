<?php

namespace App\DataFixtures\Page\Navigation\Tabs;

use App\DataFixtures\Page\AbstractPage;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TabsTwo extends AbstractPage implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     * @throws \BadMethodCallException
     */
    public function load(ObjectManager $manager)
    {
        parent::load($manager);

        $this->entity->setTitle('Tabs Two');
        $this->entity->setMetaDescription('Tabs Link Two');
        $this->entity->setParent($this->getReference('page.navigation.tabs'));
        $this->addContent();
        $this->flush();
        $this->addReference('page.navigation.tabs.tab2', $this->entity);
    }

    public function getDependencies()
    {
        return [
            TabsNavbarPage::class
        ];
    }
}
