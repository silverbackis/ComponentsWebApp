<?php

namespace App\DataFixtures\Page;

use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\DataFixtures\ComponentServiceLocator;
use Silverback\ApiComponentBundle\DataFixtures\Page\AbstractPage;
use Silverback\ApiComponentBundle\Factory\Component\GalleryFactory;
use Silverback\ApiComponentBundle\Factory\Component\HeroFactory;
use Silverback\ApiComponentBundle\Factory\Component\Item\GalleryItemFactory;

class GalleryPage extends AbstractPage
{
    private $galleryItemFactory;

    public function __construct(ComponentServiceLocator $serviceLocator, GalleryItemFactory $galleryItemFactory)
    {
        parent::__construct($serviceLocator);
        $this->galleryItemFactory = $galleryItemFactory;
    }

    /**
     * @param ObjectManager $manager
     * @throws \BadMethodCallException
     * @throws \Psr\Container\ContainerExceptionInterface
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
        $gallery = $this->createComponent(GalleryFactory::class, $this->entity, []);
        $this->galleryItemFactory->createItem($gallery, '/img/chewy1.jpg', 'Chewy', 'At the desk', null);
        $this->galleryItemFactory->createItem($gallery, '/img/stoney1.jpg', 'Chewy', 'Stoney with grass', null);
        $this->galleryItemFactory->createItem($gallery, '/img/silverback2.jpg', 'Silverback 2', 'SB2', null);
        $this->galleryItemFactory->createItem($gallery, '/img/silverback1.jpg', 'Silverback 1', 'SB1', null);
        $this->galleryItemFactory->createItem($gallery, '/img/silverback3.jpg', 'Silverback 3', 'SB3', null);

        $this->flush();
        $this->addReference('page.gallery', $this->entity);
    }
}
