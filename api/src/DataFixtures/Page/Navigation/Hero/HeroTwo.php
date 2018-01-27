<?php

namespace App\DataFixtures\Page\Navigation\Hero;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\DataFixtures\Page\AbstractPage;
use Silverback\ApiComponentBundle\Factory\Component\ContentFactory;

class HeroTwo extends AbstractPage implements DependentFixtureInterface
{
    /**
     * @param ObjectManager $manager
     * @throws \BadMethodCallException
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function load(ObjectManager $manager)
    {
        parent::load($manager);

        $this->entity->setTitle('Hero Two');
        $this->entity->setMetaDescription('Hero Link Two');
        $this->entity->setParent($this->getReference('page.navigation.hero'));
        $this->createComponent(ContentFactory::class);
        $this->flush();
        $this->addReference('page.navigation.hero.hero2', $this->entity);
    }

    public function getDependencies()
    {
        return [
            HeroNavbarPage::class
        ];
    }
}
