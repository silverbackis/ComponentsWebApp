<?php

namespace App\DataFixtures\Content;

use App\Form\Handler\ContactHandler;
use App\Form\Type\ContactType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Content\ContentFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Form\FormFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Gallery\GalleryFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Hero\HeroFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\PageFactory;

class GalleryPageFixture extends AbstractFixture
{
    /** @var PageFactory */
    private $pageFactory;
    /** @var HeroFactory */
    private $heroFactory;
    /** @var GalleryFactory  */
    private $galleryFactory;
    /** @var string  */
    private $projectDir;

    /**
     * HomePageFixture constructor.
     * @param PageFactory $pageFactory
     * @param HeroFactory $heroFactory
     * @param GalleryFactory $galleryFactory
     * @param string $projectDir
     */
    public function __construct(
        PageFactory $pageFactory,
        HeroFactory $heroFactory,
        GalleryFactory $galleryFactory,
        string $projectDir = ''
    )
    {
        $this->pageFactory = $pageFactory;
        $this->heroFactory = $heroFactory;
        $this->galleryFactory = $galleryFactory;
        $this->projectDir = $projectDir;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $page = $this->pageFactory->create(
            [
                'title' => 'Gallery',
                'metaDescription' => 'Example of an image gallery rendered with PhotoSwipe'
            ]
        );
        $this->addReference('page.gallery', $page);

        $this->heroFactory->create(
            [
                'title' => 'Gallery',
                'subtitle' => 'Example of an image gallery rendered with PhotoSwipe',
                'parentContent' => $page,
                'className' => 'is-light is-bold'
            ]
        );

        $gallery = $this->galleryFactory->create(
            [
                'parentContent' => $page
            ]
        );
        $itemFactory = $this->galleryFactory->getItemFactory();
        $itemFactory->create(
            [
                'title' => 'Chewy 1',
                'caption' => 'Chewy Picture 1',
                'filePath' => $this->projectDir . '/public/img/chewy1.jpg',
                'parentComponent' => $gallery
            ]
        );
        $itemFactory->create(
            [
                'title' => 'Silverback 1',
                'caption' => 'Silverback Picture 1',
                'filePath' => $this->projectDir . '/public/img/silverback1.jpg',
                'parentComponent' => $gallery
            ]
        );
        $itemFactory->create(
            [
                'title' => 'Silverback 2',
                'caption' => 'Silverback Picture 2',
                'filePath' => $this->projectDir . '/public/img/silverback2.jpg',
                'parentComponent' => $gallery
            ]
        );
        $itemFactory->create(
            [
                'title' => 'Silverback 3',
                'caption' => 'Silverback Picture 3',
                'filePath' => $this->projectDir . '/public/img/silverback3.jpg',
                'parentComponent' => $gallery
            ]
        );
        $itemFactory->create(
            [
                'title' => 'Stoney 1',
                'caption' => 'Stoney Picture 1',
                'filePath' => $this->projectDir . '/public/img/stoney1.jpg',
                'parentComponent' => $gallery
            ]
        );


        $manager->flush();
    }
}
