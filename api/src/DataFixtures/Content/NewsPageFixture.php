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

class NewsPageFixture extends AbstractFixture
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
                'title' => 'News / Blog',
                'metaDescription' => 'This is the list component displaying dynamic pages'
            ]
        );
        $this->addReference('page.news', $page);

        $this->heroFactory->create(
            [
                'title' => 'News / Blog',
                'subtitle' => 'This is the list component displaying dynamic pages',
                'parentContent' => $page,
                'className' => 'is-warning is-bold'
            ]
        );



        $manager->flush();
    }
}
