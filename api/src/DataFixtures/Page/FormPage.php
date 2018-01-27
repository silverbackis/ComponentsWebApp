<?php

namespace App\DataFixtures\Page;

use App\Form\Handler\ContactHandler;
use App\Form\Type\ContactType;
use Doctrine\Common\Persistence\ObjectManager;
use Silverback\ApiComponentBundle\DataFixtures\Page\AbstractPage;
use Silverback\ApiComponentBundle\Factory\Component\ContentFactory;
use Silverback\ApiComponentBundle\Factory\Component\FormFactory;
use Silverback\ApiComponentBundle\Factory\Component\HeroFactory;

class FormPage extends AbstractPage
{
    /**
     * @param ObjectManager $manager
     * @throws \BadMethodCallException
     * @throws \Psr\Container\ContainerExceptionInterface
     */
    public function load(ObjectManager $manager)
    {
        parent::load($manager);

        $this->entity->setTitle('Forms');
        $this->entity->setMetaDescription('Forms can be handles by the BW Starter Website API including validation');

        $this->createComponent(HeroFactory::class, $this->entity, [
            'title' => 'Forms',
            'subtitle' => 'An example of a Symfony form served and handled by the API with validation',
            'className' => 'is-success is-bold'
        ]);
        $this->createComponent(ContentFactory::class, $this->entity, [
            'lipsum' => ['2', 'short', 'decorate']
        ]);
        $this->createComponent(FormFactory::class, $this->entity, [
            'formType' => ContactType::class,
            'successHandler' => ContactHandler::class
        ]);

        $this->flush();
        $this->addReference('page.forms', $this->entity);
    }
}
