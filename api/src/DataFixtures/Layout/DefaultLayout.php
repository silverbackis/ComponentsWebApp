<?php

namespace App\DataFixtures\Layout;

use App\DataFixtures\Content\AbstractPageFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\DataFixtures\AbstractFixture;
use Silverback\ApiComponentBundle\Entity\Layout\Layout;

class DefaultLayout extends AbstractFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $layout = $manager->getRepository(Layout::class)->findOneBy(['default' => true]);
        if (!$layout) {
            $layout = new Layout();
        }
        $layout
            ->setDefault(true)
            ->setNavBar(null)
        ;
        $this->flushEntity($manager, $layout);
        $this->addReference(AbstractPageFixture::$layoutReference, $layout);
    }
}
