<?php

namespace App\DataFixtures\Layout;

use Doctrine\Common\Persistence\ObjectManager;

class DefaultLayout extends AbstractLayout
{
    /**
     * @param ObjectManager $manager
     * @throws \BadMethodCallException
     */
    public function load(ObjectManager $manager)
    {
        parent::load($manager);
        $this->entity->setDefault(true);
        $this->flush();
        $this->addReference('layout.default', $this->entity);
    }
}