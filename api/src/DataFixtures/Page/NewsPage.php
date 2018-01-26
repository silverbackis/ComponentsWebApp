<?php

namespace App\DataFixtures\Page;

use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\DataFixtures\Component\HeroComponent;
use Silverback\ApiComponentBundle\DataFixtures\Component\NewsComponent;
use Silverback\ApiComponentBundle\DataFixtures\Page\AbstractPage;

class NewsPage extends AbstractPage
{
    /**
     * @param ObjectManager $manager
     * @throws \BadMethodCallException
     */
    public function load(ObjectManager $manager)
    {
        parent::load($manager);

        $this->entity->setTitle('News / Blog');
        $this->entity->setMetaDescription('News or blog components');

        $this->createComponent(HeroComponent::class, $this->entity, [
            'title' => 'News / Blog',
            'subtitle' => 'An example of a news/blog component',
            'className' => 'is-light is-bold'
        ]);
        $this->createComponent(NewsComponent::class, $this->entity);

        $this->flush();
        $this->addReference('page.news', $this->entity);
    }
}
