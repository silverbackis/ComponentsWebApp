<?php

namespace App\DataFixtures\Layout;

use App\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\Entity\Layout;

/**
 * Class AbstractLayout
 * @package App\DataFixtures\Layout
 * @author Daniel West <daniel@silverback.is>
 * @property Layout $entity
 */
abstract class AbstractLayout extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager)
    {
        parent::load($manager);
        $this->entity = new Layout();
    }
}