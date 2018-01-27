<?php

namespace App\DataFixtures\Page\Navigation\Tabs;

use App\DataFixtures\Page\NavigationPage;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\DataFixtures\Page\AbstractPage;
use Silverback\ApiComponentBundle\Factory\Component\HeroFactory;

class TabsNavbarPage extends AbstractPage implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     * @throws \BadMethodCallException
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function load(ObjectManager $manager)
    {
        parent::load($manager);

        $this->entity->setTitle('Tabs Navbar');
        $this->entity->setMetaDescription('An example of adding a tab navigation to a page');
        $this->entity->setParent($this->getReference('page.navigation'));
        $this->createComponent(HeroFactory::class, null, [
            'title' => 'Tabs',
            'subtitle' => 'You can add tabs to your page'
        ]);

        $this->flush();
        $this->addReference('page.navigation.tabs', $this->entity);
    }

    public function getDependencies()
    {
        return [
            NavigationPage::class
        ];
    }
}
