<?php

namespace App\DataFixtures\Page;

use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\DataFixtures\Page\AbstractPage;
use Silverback\ApiComponentBundle\Factory\Component\GalleryFactory;
use Silverback\ApiComponentBundle\Factory\Component\HeroFactory;

class GalleryPage extends AbstractPage
{
    /**
     * @param ObjectManager $manager
     * @throws \BadMethodCallException
     */
    public function load(ObjectManager $manager)
    {
        parent::load($manager);

        $this->entity->setTitle('Gallery');
        $this->entity->setMetaDescription('Image gallery component');

        $this->createComponent(HeroFactory::class, $this->entity, [
            'title' => 'Gallery',
            'subtitle' => 'Here you can see an image gallery',
            'className' => 'is-danger is-bold'
        ]);
        $this->createComponent(GalleryFactory::class, $this->entity, []);

        $this->flush();
        $this->addReference('page.gallery', $this->entity);
    }
}
