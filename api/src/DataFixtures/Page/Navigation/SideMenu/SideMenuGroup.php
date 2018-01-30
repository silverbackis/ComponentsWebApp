<?php

namespace App\DataFixtures\Page\Navigation\SideMenu;

use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\DataFixtures\CustomEntityInterface;
use Silverback\ApiComponentBundle\DataFixtures\Page\AbstractPage;
use Silverback\ApiComponentBundle\Entity\ComponentGroup;
use Silverback\ApiComponentBundle\Factory\Component\ContentFactory;

class SideMenuGroup extends AbstractPage implements CustomEntityInterface, DependentFixtureInterface
{
    public function getEntity()
    {
        return new ComponentGroup();
    }

    /**
     * @param ObjectManager $manager
     * @return Object|void
     * @throws \BadMethodCallException
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function load(ObjectManager $manager)
    {
        parent::load($manager);
        $this->entity->setParent($this->getReference('nav.sidemenu'));
        $this->createComponent(ContentFactory::class);
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
