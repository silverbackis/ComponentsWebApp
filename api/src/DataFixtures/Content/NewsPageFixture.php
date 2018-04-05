<?php

namespace App\DataFixtures\Content;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\Entity\Content\Dynamic\ArticlePage;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Collection\CollectionFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Hero\HeroFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Dynamic\ArticlePageFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\PageFactory;

class NewsPageFixture extends AbstractFixture
{
    /** @var PageFactory */
    private $pageFactory;
    /** @var HeroFactory */
    private $heroFactory;
    /** @var CollectionFactory  */
    private $collectionFactory;
    /** @var ArticlePageFactory  */
    private $articlePageFactory;
    /** @var string  */
    private $projectDir;

    /**
     * HomePageFixture constructor.
     * @param PageFactory $pageFactory
     * @param HeroFactory $heroFactory
     * @param CollectionFactory $collectionFactory
     * @param ArticlePageFactory $articlePageFactory
     * @param string $projectDir
     */
    public function __construct(
        PageFactory $pageFactory,
        HeroFactory $heroFactory,
        CollectionFactory $collectionFactory,
        ArticlePageFactory $articlePageFactory,
        string $projectDir = ''
    )
    {
        $this->pageFactory = $pageFactory;
        $this->heroFactory = $heroFactory;
        $this->collectionFactory = $collectionFactory;
        $this->articlePageFactory = $articlePageFactory;
        $this->projectDir = $projectDir;
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

        $this->collectionFactory->create(
            [
                'resource' => ArticlePage::class,
                'parentContent' => $page
            ]
        );

        $this->articlePageFactory->create(
            [
                'title' => 'My Article Title',
                'subtitle' => 'Once upon a time...',
                'content' => 'We made a dynamic page which can be loaded as a normal page would be. It also has a route!',
                'filePath' => $this->projectDir . '/public/img/chewy1.jpg',
                'parent' => $page
            ]
        );

        $manager->flush();
    }
}
