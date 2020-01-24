<?php

namespace App\DataFixtures\Content;

use Doctrine\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\Entity\Component\Hero\Hero;

class HomePageFixture extends AbstractPageFixture
{
    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $pageFixture = $this->getPage('home', '/');
        if ($pageFixture->isNew()) {
            $page = $pageFixture->getStaticPage();
            $page
                ->setTitle('')
                ->setMetaDescription('Welcome')
            ;

            $hero = (new Hero())
                ->setTitle('Hello World')
            ;
            $this->addComponent($hero, $page);

            $content = $this->contentFactory->create(null, [
                '1',
                'short'
            ]);
            $this->addComponent($content, $page);
        }
        $this->flush($manager);
    }

//    public function getDependencies(): array
//    {
//        return array_merge(parent::getDependencies(), []);
//    }
}
