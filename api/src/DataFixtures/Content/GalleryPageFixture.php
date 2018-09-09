<?php

namespace App\DataFixtures\Content;

use Silverback\ApiComponentBundle\Uploader\FixtureFileUploader;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\Entity\Content\Component\Gallery\Gallery;
use Silverback\ApiComponentBundle\Entity\Content\Component\Gallery\GalleryItem;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Gallery\GalleryFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Hero\HeroFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\PageFactory;
use Symfony\Component\HttpFoundation\File\File;

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
    /** @var FixtureFileUploader */
    private $fileUploader;

    /**
     * HomePageFixture constructor.
     * @param PageFactory $pageFactory
     * @param HeroFactory $heroFactory
     * @param GalleryFactory $galleryFactory
     * @param FixtureFileUploader $fileUploader
     * @param string $projectDir
     */
    public function __construct(
        PageFactory $pageFactory,
        HeroFactory $heroFactory,
        GalleryFactory $galleryFactory,
        FixtureFileUploader $fileUploader,
        string $projectDir = ''
    ) {
        $this->pageFactory = $pageFactory;
        $this->heroFactory = $heroFactory;
        $this->galleryFactory = $galleryFactory;
        $this->projectDir = $projectDir;
        $this->fileUploader = $fileUploader;
    }

    /**
     * @param ObjectManager $manager
     * @throws \Exception
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

        $uploadsDir = $this->projectDir . '/assets/fixtures/gallery';
        $this->addGalleryItem($uploadsDir, $gallery, 'Chewy 1', 'Chewy Picture 1', 'chewy1.jpg');
        $this->addGalleryItem($uploadsDir, $gallery, 'Silverback 1', 'Silverback Picture 1', 'silverback1.jpg');
        $this->addGalleryItem($uploadsDir, $gallery, 'Silverback 2', 'Silverback Picture 2', 'silverback2.jpg');
        $this->addGalleryItem($uploadsDir, $gallery, 'Silverback 3', 'Silverback Picture 3', 'silverback3.jpg');
        $this->addGalleryItem($uploadsDir, $gallery, 'Stoney 3', 'Stoney Picture 1', 'stoney1.jpg');

        $manager->flush();
    }

    /**
     * @param string $uploadsDir
     * @param Gallery $gallery
     * @param string $title
     * @param string $caption
     * @param string $image
     * @return GalleryItem
     * @throws \Exception
     */
    private function addGalleryItem(string $uploadsDir, Gallery $gallery, string $title, string $caption, string $image): GalleryItem
    {

        $itemFactory = $this->galleryFactory->getItemFactory();
        /** @var GalleryItem $entity */
        $entity = $this->fileUploader->upload($itemFactory, [
            'title' => $title,
            'caption' => $caption,
            'parentComponent' => $gallery
        ], new File($uploadsDir . '/' . $image));
        return $entity;
    }
}
