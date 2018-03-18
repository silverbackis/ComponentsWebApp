<?php

namespace App\DataFixtures\Content;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\Entity\Content\Component\ComponentLocation;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Feature\Columns\FeatureColumnsFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Feature\Stacked\FeatureStackedFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Feature\TextList\FeatureTextListFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\Component\Hero\HeroFactory;
use Silverback\ApiComponentBundle\Factory\Entity\Content\PageFactory;

class FeaturePageFixture extends AbstractFixture implements DependentFixtureInterface
{
    /** @var PageFactory */
    private $pageFactory;
    /** @var HeroFactory */
    private $heroFactory;
    /** @var FeatureTextListFactory  */
    private $featureTextListFactory;
    /** @var FeatureColumnsFactory */
    private $featureColumnsFactory;
    /** @var string  */
    private $projectDir;
    /** @var FeatureStackedFactory  */
    private $featureStackedFactory;

    /**
     * HomePageFixture constructor.
     * @param PageFactory $pageFactory
     * @param HeroFactory $heroFactory
     * @param FeatureTextListFactory $featureTextListFactory
     * @param FeatureColumnsFactory $featureColumnsFactory
     * @param FeatureStackedFactory $featureStackedFactory
     * @param string $projectDir
     */
    public function __construct(
        PageFactory $pageFactory,
        HeroFactory $heroFactory,
        FeatureTextListFactory $featureTextListFactory,
        FeatureColumnsFactory $featureColumnsFactory,
        FeatureStackedFactory $featureStackedFactory,
        string $projectDir = ''
    )
    {
        $this->pageFactory = $pageFactory;
        $this->heroFactory = $heroFactory;
        $this->featureTextListFactory = $featureTextListFactory;
        $this->featureColumnsFactory = $featureColumnsFactory;
        $this->featureStackedFactory = $featureStackedFactory;
        $this->projectDir = $projectDir;
    }

    /**
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void
    {
        $page = $this->pageFactory->create(
            [
                'title' => 'Features',
                'metaDescription' => 'Feature components'
            ]
        );
        $this->addReference('page.feature', $page);

        $this->heroFactory->create(
            [
                'title' => 'Features',
                'subtitle' => 'We have 3 ways of listing features to choose from as standard',
                'parentContent' => $page,
                'className' => 'is-success is-bold'
            ]
        );

        $featureTextList = $this->createFeatureTextList();
        $page->addComponentLocation(new ComponentLocation($page, $featureTextList));

        $featureColumns = $this->createFeatureColumns();
        $page->addComponentLocation(new ComponentLocation($page, $featureColumns));

        $featureStacked = $this->createFeatureStacked();
        $page->addComponentLocation(new ComponentLocation($page, $featureStacked));

        $manager->flush();
    }

    private function createFeatureStacked() {
        $featureStacked = $this->featureStackedFactory->create();
        $itemFactory = $this->featureStackedFactory->getItemFactory();
        $itemFactory->create(
            [
                'title' => 'Nuxt',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ornare magna eros, eu pellentesque tortor vestibulum ut. Maecenas non massa sem. Etiam finibus odio quis feugiat facilisis.',
                'url' => 'https://nuxtjs.org/',
                'filePath' => $this->projectDir . '/public/images/nuxt.svg',
                'parentComponent' => $featureStacked,
                'buttonText' => 'Visit Website'
            ]
        );
        $itemFactory->create(
            [
                'title' => 'API Platform',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ornare magna eros, eu pellentesque tortor vestibulum ut. Maecenas non massa sem. Etiam finibus odio quis feugiat facilisis.',
                'url' => 'https://api-platform.com/',
                'filePath' => $this->projectDir . '/public/images/api-platform-spider.svg',
                'parentComponent' => $featureStacked,
                'buttonText' => 'Visit Website'
            ]
        );
        $itemFactory->create(
            [
                'title' => 'Bulma',
                'description' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ornare magna eros, eu pellentesque tortor vestibulum ut. Maecenas non massa sem. Etiam finibus odio quis feugiat facilisis.',
                'url' => 'http://bulma.io/',
                'filePath' => $this->projectDir . '/public/images/bulma.svg',
                'parentComponent' => $featureStacked,
                'buttonText' => 'Visit Website'
            ]
        );
        return $featureStacked;
    }

    private function createFeatureColumns() {
        $featureColumns = $this->featureColumnsFactory->create(
            [
                'title' => 'Feature Columns'
            ]
        );
        $itemFactory = $this->featureColumnsFactory->getItemFactory();
        $itemFactory->create(
            [
                'title' => 'Nuxt',
                'description' => 'Server-side rendering for VueJS',
                'url' => 'https://nuxtjs.org/',
                'filePath' => $this->projectDir . '/public/images/nuxt.svg',
                'parentComponent' => $featureColumns
            ]
        );
        $itemFactory->create(
            [
                'title' => 'API Platform',
                'description' => 'API Framework built on Symfony',
                'url' => 'https://api-platform.com/',
                'filePath' => $this->projectDir . '/public/images/api-platform-spider.svg',
                'parentComponent' => $featureColumns
            ]
        );
        $itemFactory->create(
            [
                'title' => 'Bulma',
                'description' => 'Light-weight CSS Framework',
                'url' => 'http://bulma.io/',
                'filePath' => $this->projectDir . '/public/images/bulma.svg',
                'parentComponent' => $featureColumns
            ]
        );

        return $featureColumns;
    }

    private function createFeatureTextList() {
        $featureTextList = $this->featureTextListFactory->create(
            [
                'title' => 'Feature Text List'
            ]
        );

        $itemFactory = $this->featureTextListFactory->getItemFactory();

        $itemFactory->create(
            [
                'title' => 'Google',
                'url' => 'https://www.google.co.uk',
                'parentComponent' => $featureTextList
            ]
        );
        $pageHome = $this->getReference('page.home');
        $itemFactory->create(
            [
                'title' => 'Home',
                'route' => $pageHome->getRoutes()->first(),
                'parentComponent' => $featureTextList
            ]
        );
        $x = 7;
        while($x > 0) {
            $itemFactory->create(
                [
                    'title' => 'Feature ' . $x,
                    'parentComponent' => $featureTextList
                ]
            );
            $x--;
        }

        return $featureTextList;
    }

    public function getDependencies()
    {
        return [
            HomePageFixture::class
        ];
    }
}
