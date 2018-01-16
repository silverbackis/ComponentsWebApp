<?php

namespace App\DataFixtures\Page;

use Doctrine\Common\Persistence\ObjectManager;

class NavigationPage extends AbstractPage
{
    /**
     * @param ObjectManager $manager
     * @throws \BadMethodCallException
     */
    public function load(ObjectManager $manager)
    {
        parent::load($manager);

        $this->entity->setTitle('Navigation');
        $this->entity->setMetaDescription('There are many navigation options');
        $this->flush();
        $this->addReference('page.navigation', $this->entity);
    }
}
