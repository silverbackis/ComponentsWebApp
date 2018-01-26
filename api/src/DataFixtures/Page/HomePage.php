<?php

namespace App\DataFixtures\Page;

use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\DataFixtures\Component\ContentComponent;
use Silverback\ApiComponentBundle\DataFixtures\Component\HeroComponent;
use Silverback\ApiComponentBundle\DataFixtures\Page\AbstractPage;
use Silverback\ApiComponentBundle\Entity\Route;

class HomePage extends AbstractPage
{
    /**
     * @param ObjectManager $manager
     * @throws \BadMethodCallException
     */
    public function load(ObjectManager $manager)
    {
        parent::load($manager);

        $this->entity->setTitle('British Websites');
        $this->entity->setMetaDescription('Welcome to the BW Starter Website built with the best and latest frameworks. Front-end uses NuxtJS (VueJS) and Bulma. The API uses API Platform (Symfony 4).');
        $this->entity->addRoute(new Route('/'));

        $this->createComponent(HeroComponent::class, $this->entity, [
            'title' => 'Home Page',
            'subtitle' => 'Welcome to the BW Starter Website'
        ]);
        $this->createComponent(ContentComponent::class, $this->entity);

        $this->flush();
        $this->addReference('page.home', $this->entity);
    }
}
