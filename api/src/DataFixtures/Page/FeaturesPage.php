<?php

namespace App\DataFixtures\Page;

use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\DataFixtures\Component\FeatureColumnsComponent;
use Silverback\ApiComponentBundle\DataFixtures\Component\FeatureStackedComponent;
use Silverback\ApiComponentBundle\DataFixtures\Component\FeatureTextListComponent;
use Silverback\ApiComponentBundle\DataFixtures\Component\Helper\FeatureHelper;
use Silverback\ApiComponentBundle\DataFixtures\Component\HeroComponent;
use Silverback\ApiComponentBundle\DataFixtures\ComponentServiceLocator;
use Silverback\ApiComponentBundle\DataFixtures\Page\AbstractPage;
use Silverback\ApiComponentBundle\Entity\Component\Feature\Columns\FeatureColumns;
use Silverback\ApiComponentBundle\Entity\Component\Feature\Columns\FeatureColumnsItem;
use Silverback\ApiComponentBundle\Entity\Component\Feature\Stacked\FeatureStacked;
use Silverback\ApiComponentBundle\Entity\Component\Feature\Stacked\FeatureStackedItem;
use Silverback\ApiComponentBundle\Entity\Component\Feature\TextList\FeatureTextList;

class FeaturesPage extends AbstractPage
{
    private $featureHelper;

    public function __construct(
        ComponentServiceLocator $serviceLocator,
        FeatureHelper $featureHelper
    )
    {
        parent::__construct($serviceLocator);
        $this->featureHelper = $featureHelper;
    }

    /**
     * @param ObjectManager $manager
     * @throws \BadMethodCallException
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function load(ObjectManager $manager)
    {
        parent::load($manager);

        $this->entity->setTitle('Feature Components');
        $this->entity->setMetaDescription('We have 3 ways of listing features to choose from');

        $this->createComponent(HeroComponent::class, $this->entity, [
            'title' => 'Feature Components',
            'subtitle' => 'We have 3 ways of listing features to choose from',
            'className' => 'is-warning is-bold'
        ]);

        /**
         * @var $feature FeatureTextList
         */
        $feature = $this->createComponent(FeatureTextListComponent::class, $this->entity);
        $feature->setTitle('Features from the API');
        $this->featureHelper->createItem($feature, 'Google', 'https://www.google.co.uk');
        $this->featureHelper->createItem($feature, 'Home', '/');
        $this->featureHelper->createItem($feature, 'Another feature', '/feature-components');
        $this->featureHelper->createItem($feature, 'And some that are not links...');
        $this->featureHelper->createItem($feature, 'Better than you thought');
        $this->featureHelper->createItem($feature, 'Superb stuff whenever');
        $this->featureHelper->createItem($feature, 'Amazing deals wherever');
        $this->featureHelper->createItem($feature, 'At your fingertips');
        $this->featureHelper->createItem($feature, 'Before you even thought about it');

        /**
         * @var $feature FeatureColumns
         */
        $feature = $this->createComponent(FeatureColumnsComponent::class, $this->entity);
        $feature->setTitle('Say hello to my little friends');
        /**
         * @var FeatureColumnsItem $item
         */
        $item = $this->featureHelper->createItem($feature, 'Nuxt', 'https://nuxtjs.org');
        $item->setImage('images/nuxt.svg');
        $item->setDescription('Server-Side Rendering for VueJS');

        $item = $this->featureHelper->createItem($feature, 'API Platform', 'https://api-platform.com/');
        $item->setImage('images/api-platform-spider.svg');
        $item->setDescription('API Framework built on Symfony');

        $item = $this->featureHelper->createItem($feature, 'Bulma', 'http://bulma.io/');
        $item->setImage('images/bulma.svg');
        $item->setDescription('Light-weight CSS Framework');

        /**
         * @var $feature FeatureStacked
         */
        $feature = $this->createComponent(FeatureStackedComponent::class, $this->entity);
        // $feature->setReverse(true);
        /**
         * @var FeatureStackedItem $item
         */
        $item = $this->featureHelper->createItem($feature, 'VueJS', 'https://nuxtjs.org');
        $item->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ornare magna eros, eu pellentesque tortor vestibulum ut. Maecenas non massa sem. Etiam finibus odio quis feugiat facilisis.');
        $item->setButtonText('Visit website');
        $item->setImage('images/nuxt.svg');

        $item = $this->featureHelper->createItem($feature, 'API Platform', 'https://api-platform.com/');
        $item->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ornare magna eros, eu pellentesque tortor vestibulum ut. Maecenas non massa sem. Etiam finibus odio quis feugiat facilisis.');
        $item->setButtonText('Visit website');
        $item->setImage('images/api-platform-spider.svg');

        $item = $this->featureHelper->createItem($feature, 'Bulma', 'http://bulma.io/');
        $item->setDescription('Lorem ipsum dolor sit amet, consectetur adipiscing elit. Proin ornare magna eros, eu pellentesque tortor vestibulum ut. Maecenas non massa sem. Etiam finibus odio quis feugiat facilisis.');
        $item->setButtonText('Visit website');
        $item->setImage('images/bulma.svg');

        $this->flush();
        $this->addReference('page.features', $this->entity);
    }
}
