<?php

namespace App\DataFixtures\Content;

use App\Form\Handler\ContactHandler;
use App\Form\Type\ContactType;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Content\ContentFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Form\FormFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Hero\HeroFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\PageFactory;

class GalleryPageFixture extends AbstractFixture
{
    /** @var PageFactory */
    private $pageFactory;
    /** @var HeroFactory */
    private $heroFactory;

    /**
     * HomePageFixture constructor.
     * @param PageFactory $pageFactory
     * @param HeroFactory $heroFactory
     */
    public function __construct(
        PageFactory $pageFactory,
        HeroFactory $heroFactory
    )
    {
        $this->pageFactory = $pageFactory;
        $this->heroFactory = $heroFactory;
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



        $manager->flush();
    }
}
