<?php

namespace App\DataFixtures\Page;

use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\DataFixtures\Page\AbstractPage;
use Silverback\ApiComponentBundle\Factory\Component\HeroFactory;
use Silverback\ApiComponentBundle\Factory\Component\NewsFactory;

class NewsPage extends AbstractPage
{
    /**
     * @param ObjectManager $manager
     * @throws \BadMethodCallException
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function load(ObjectManager $manager)
    {
        parent::load($manager);

        $this->entity->setTitle('News / Blog');
        $this->entity->setMetaDescription('News or blog components');

        $this->createComponent(HeroFactory::class, $this->entity, [
            'title' => 'News / Blog',
            'subtitle' => 'An example of a news/blog component',
            'className' => 'is-light is-bold'
        ]);
        $this->createComponent(NewsFactory::class, $this->entity);

        $this->flush();
        $this->addReference('page.news', $this->entity);
    }
}
