<?php

namespace App\DataFixtures\Page\Navigation\Tabs;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\DataFixtures\Page\AbstractPage;
use Silverback\ApiComponentBundle\Factory\Component\ContentFactory;

class TabsOne extends AbstractPage implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     * @throws \BadMethodCallException
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function load(ObjectManager $manager)
    {
        parent::load($manager);

        $this->entity->setTitle('Tabs One');
        $this->entity->setMetaDescription('Tabs Link One');
        $this->entity->setParent($this->getReference('page.navigation.tabs'));
        $this->createComponent(ContentFactory::class);
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
