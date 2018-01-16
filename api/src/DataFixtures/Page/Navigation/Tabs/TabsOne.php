<?php

namespace App\DataFixtures\Page\Navigation\Tabs;

use App\DataFixtures\Page\AbstractPage;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;

class TabsOne extends AbstractPage implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     * @throws \BadMethodCallException
     */
    public function load(ObjectManager $manager)
    {
        parent::load($manager);

        $this->entity->setTitle('Tabs One');
        $this->entity->setMetaDescription('Tabs Link One');
        $this->entity->setParent($this->getReference('page.navigation.tabs'));
        $this->addContent();
        $this->flush();
        $this->redirectFrom($this->getReference('page.navigation.tabs'));
        $this->addReference('page.navigation.tabs.tab1', $this->entity);
    }

    public function getDependencies()
    {
        return [
            TabsNavbarPage::class
        ];
    }
}
