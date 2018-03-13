<?php

namespace App\DataFixtures\Layout;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\Factory\Entity\Layout\LayoutFactory;

class DefaultLayout extends AbstractFixture
{
    /**
     * @var LayoutFactory
     */
    private $layoutFactory;

    public function __construct(
        LayoutFactory $layoutFactory
    )
    {
        $this->layoutFactory = $layoutFactory;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Doctrine\Common\DataFixtures\BadMethodCallException
     */
    public function load(ObjectManager $manager): void
    {
        $layout = $this->layoutFactory->create(
            [
                'default' => true,
                'navBar' => null
            ]
        );
        $this->addReference('layout', $layout);
        $manager->flush();
    }
}
